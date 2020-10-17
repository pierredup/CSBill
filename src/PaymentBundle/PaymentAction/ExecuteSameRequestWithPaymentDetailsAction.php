<?php

declare(strict_types=1);

/*
 * This file is part of SolidInvoice project.
 *
 * (c) 2013-2017 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SolidInvoice\PaymentBundle\PaymentAction;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Generic;
use SolidInvoice\PaymentBundle\Entity\Payment;

class ExecuteSameRequestWithPaymentDetailsAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritdoc}
     *
     * @param $request Generic
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var Payment $payment */
        $payment = $request->getModel();
        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        try {
            $request->setModel($details);
            $this->gateway->execute($request);
            $payment->setDetails($details);
        } catch (\Exception $e) {
            $payment->setDetails($details);

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Generic &&
            $request->getModel() instanceof Payment;
    }
}
