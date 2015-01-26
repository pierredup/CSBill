<?php

namespace CSBill\PaymentBundle\Controller;

use CSBill\CoreBundle\Controller\BaseController;
use CSBill\InvoiceBundle\Model\Graph;
use CSBill\PaymentBundle\Action\Request\StatusRequest;
use CSBill\PaymentBundle\Entity\Payment;
use CSBill\PaymentBundle\Entity\Status;
use CSBill\PaymentBundle\Event\PaymentCompleteEvent;
use CSBill\PaymentBundle\Event\PaymentEvents;
use CSBill\PaymentBundle\Repository\PaymentMethod;
use Doctrine\ORM\Query\Expr;
use Rhumsaa\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

class PaymentController extends BaseController
{
    /**
     * @param Request $request
     * @param string  $uuid
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function preparePaymentAction(Request $request, $uuid)
    {
        $invoice = $this->getRepository('CSBillInvoiceBundle:Invoice')
            ->findOneBy(array('uuid' => Uuid::fromString($uuid)));

        if (null === $invoice) {
            throw $this->createNotFoundException();
        }

        if (Graph::STATUS_PENDING !== (string) $invoice->getStatus()) {
            throw new \Exception('This invoice cannot be paid');
        }

        $paymentManager = $this->get('csbill_payment.method.manager');

        /** @var \CSBill\PaymentBundle\Repository\PaymentMethod $paymentRepository */
        $paymentRepository = $this->getRepository('CSBillPaymentBundle:PaymentMethod');

        if (1 === $paymentRepository->getTotalMethodsConfigured() || 0 === count($paymentManager)) {
            throw new \Exception('No payment methods configured');
        }

        $builder = $this->createFormBuilder();

        $builder->add(
            'payment_method',
            'entity',
            array(
                'class' => 'CSBillPaymentBundle:PaymentMethod',
                'query_builder' => function (PaymentMethod $repository) {
                    $queryBuilder = $repository->createQueryBuilder('pm');
                    $expression = new Expr();
                    $queryBuilder->where($expression->eq('pm.enabled', 1));

                    // If user is not logged in, only show public exposed payment methods
                    if (null === $this->getUser()) {
                        $queryBuilder->AndWhere($expression->eq('pm.public', 1));
                    }

                    return $queryBuilder;
                },
                'required' => true,
                'constraints' => new NotBlank(),
                'placeholder' => 'Choose Payment Method',
                'attr' => array(
                    'class' => 'select2',
                ),
            )
        );

        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $paymentMethod = $paymentManager->getPaymentMethod($data['payment_method']->getPaymentMethod());

            $paymentName = $paymentMethod->getContext();

            $status = $this
                ->getRepository('CSBillPaymentBundle:Status')
                ->findOneBy(array('name' => Status::STATUS_NEW));

            $payment = new Payment();
            $invoice->addPayment($payment);
            $payment->setInvoice($invoice);
            $payment->setStatus($status);
            $payment->setMethod($data['payment_method']);
            $payment->setAmount($invoice->getTotal());
            $payment->setCurrency($this->container->getParameter('currency'));
            $payment->setClient($invoice->getClient());

            $this->save($payment);

            $captureToken = $this->get('payum.security.token_factory')->createCaptureToken(
                $paymentName,
                $payment->getDetails(),
                '_payments_done' // the route to redirect after capture;
            );

            return $this->redirect($captureToken->getTargetUrl());
        }

        return $this->render(
            'CSBillPaymentBundle:Payment:create.html.twig',
            array(
                'form' => $form->createView(),
                'invoice' => $invoice,
            )
        );
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     * @throws \Payum\Core\Reply\ReplyInterface
     */
    public function captureDoneAction(Request $request)
    {
        $entityManager = $this->getEm();

        /** @var \CSBill\PaymentBundle\Entity\SecurityToken $token */
        $token = $this->get('payum.security.http_request_verifier')->verify($request);

        /** @var \Payum\Core\Payment $payment */
        $payment = $this->get('payum')->getPayment($token->getPaymentName());

        $status = new StatusRequest($token);
        $payment->execute($status);

        /** @var Payment $paymentDetails */
        $paymentDetails = $status->getModel()->getPayment();

        $paymentStatus = $paymentDetails->getMethod()->getDefaultStatus();

        $paymentDetails->setStatus(
            $paymentStatus ?: $this
                ->getRepository('CSBillPaymentBundle:Status')
                ->findOneBy(array('name' => $status->getValue()))
        );

        $paymentDetails->setCompleted(new \DateTime('now'));

        $entityManager->persist($paymentDetails);

        /** @var \CSBill\InvoiceBundle\Entity\Invoice $invoice */
        $invoice = $paymentDetails->getInvoice();

        if ($status->isSuccess()) {
            $invoice->setPaidDate(new \DateTime('NOW'));
            $this->get('invoice.manager')->pay($invoice);
            $entityManager->persist($invoice);
            $this->flash('Payment success.', 'success');
        } elseif ($status->isPending()) {
            $this->flash('Payment is still pending.', 'warning');
        } else {
            $message = $paymentDetails->getMessage();
            $this->flash(sprintf('Payment failed: %s', $message), 'error');
        }

        $event = new PaymentCompleteEvent($paymentDetails);
        $this->get('event_dispatcher')->dispatch(PaymentEvents::PAYMENT_COMPLETE, $event);

        $security = $this->get('security.authorization_checker');

        if ($security->isGranted('ROLE_ADMIN')) {
            $url = $this->generateUrl('_invoices_view', array('id' => $invoice->getId()));
        } else {
            $url = $this->generateUrl('_view_invoice_external', array('uuid' => $invoice->getUuid()));
        }

        return $this->redirect($url);
    }
}
