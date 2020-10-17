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

namespace SolidInvoice\InvoiceBundle\Tests\Manager;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery as M;
use Money\Currency;
use Money\Money;
use SolidInvoice\ClientBundle\Entity\Client;
use SolidInvoice\CoreBundle\Entity\Discount;
use SolidInvoice\InvoiceBundle\Listener\WorkFlowSubscriber;
use SolidInvoice\InvoiceBundle\Manager\InvoiceManager;
use SolidInvoice\NotificationBundle\Notification\NotificationManager;
use SolidInvoice\QuoteBundle\Entity\Item;
use SolidInvoice\QuoteBundle\Entity\Quote;
use SolidInvoice\TaxBundle\Entity\Tax;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Workflow\Definition;
use Symfony\Component\Workflow\MarkingStore\MethodMarkingStore;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Component\Workflow\Transition;

class InvoiceManagerTest extends KernelTestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var InvoiceManager
     */
    private $manager;

    /**
     * @var \Mockery\Mock
     */
    private $entityManager;

    public function setUp(): void
    {
        $this->entityManager = M::mock('Doctrine\ORM\EntityManagerInterface');
        $doctrine = M::mock('Doctrine\Common\Persistence\ManagerRegistry', ['getManager' => $this->entityManager]);
        $notification = M::mock('SolidInvoice\NotificationBundle\Notification\NotificationManager');

        $notification->shouldReceive('sendNotification')
            ->andReturn(null);

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new WorkFlowSubscriber($doctrine, M::mock(NotificationManager::class)));
        $stateMachine = new StateMachine(
            new Definition(
                ['new', 'draft'],
                [new Transition('new', 'new', 'draft')]
            ),
            new MethodMarkingStore(true, 'status'),
            $dispatcher,
            'invoice'
        );

        $this->manager = new InvoiceManager($doctrine, new EventDispatcher(), $stateMachine, $notification);

        $this
            ->entityManager
            ->shouldReceive('persist', 'flush')
            ->zeroOrMoreTimes();
    }

    public function testCreateFromQuote()
    {
        $currency = new Currency('USD');

        $client = new Client();
        $client->setName('Test Client');
        $client->setWebsite('http://example.com');
        $client->setCreated(new \DateTime('NOW'));

        $tax = new Tax();
        $tax->setName('VAT');
        $tax->setRate(14.00);
        $tax->setType(Tax::TYPE_INCLUSIVE);

        $item = new Item();
        $item->setTax($tax);
        $item->setDescription('Item Description');
        $item->setCreated(new \DateTime('now'));
        $item->setPrice(new Money(120, $currency));
        $item->setQty(10);
        $item->setTotal(new Money((12 * 10), $currency));

        $quote = new Quote();
        $quote->setBaseTotal(new Money(123, $currency));
        $discount = new Discount();
        $discount->setType(Discount::TYPE_PERCENTAGE);
        $discount->setValue(12);
        $quote->setDiscount($discount);
        $quote->setNotes('Notes');
        $quote->setTax(new Money(432, $currency));
        $quote->setTerms('Terms');
        $quote->setTotal(new Money(987, $currency));
        $quote->setClient($client);
        $quote->addItem($item);

        $invoice = $this->manager->createFromQuote($quote);

        $this->assertEquals($quote->getTotal(), $invoice->getTotal());
        $this->assertEquals($quote->getBaseTotal(), $invoice->getBaseTotal());
        $this->assertSame($quote->getDiscount(), $invoice->getDiscount());
        $this->assertSame($quote->getNotes(), $invoice->getNotes());
        $this->assertSame($quote->getTerms(), $invoice->getTerms());
        $this->assertEquals($quote->getTax(), $invoice->getTax());
        $this->assertSame($client, $invoice->getClient());
        $this->assertNull($invoice->getStatus());

        $this->assertNotSame($quote->getUuid(), $invoice->getUuid());
        $this->assertNull($invoice->getId());

        $this->assertCount(1, $invoice->getItems());

        /** @var \SolidInvoice\InvoiceBundle\Entity\item[] $invoiceItem */
        $invoiceItem = $invoice->getItems();
        $this->assertInstanceOf('SolidInvoice\InvoiceBundle\Entity\item', $invoiceItem[0]);

        $this->assertSame($item->getTax(), $invoiceItem[0]->getTax());
        $this->assertSame($item->getDescription(), $invoiceItem[0]->getDescription());
        $this->assertInstanceOf('DateTime', $invoiceItem[0]->getCreated());
        $this->assertEquals($item->getPrice(), $invoiceItem[0]->getPrice());
        $this->assertSame($item->getQty(), $invoiceItem[0]->getQty());
    }
}
