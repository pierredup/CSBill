<?php

declare(strict_types=1);

/*
 * This file is part of the SolidInvoice project.
 *
 * @author     pierre
 * @copyright  Copyright (c) 2019
 */

namespace SolidInvoice\PaymentBundle\Tests\Functional\Api;

use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use SolidInvoice\ApiBundle\Test\ApiTestCase;

/**
 * @group functional
 */
class PaymentTest extends ApiTestCase
{
    use FixturesTrait;

    public function setUp(): void
    {
        parent::setUp();
        StaticDriver::rollBack();
        $connection = self::bootKernel()->getContainer()->get('doctrine')->getConnection();
        $connection->executeQuery('ALTER TABLE clients AUTO_INCREMENT = 1000');
        $connection->executeQuery('ALTER TABLE contacts AUTO_INCREMENT = 1000');
        $connection->executeQuery('ALTER TABLE payments AUTO_INCREMENT = 1000');
        StaticDriver::beginTransaction();

        $this->loadFixtures([
            'SolidInvoice\ClientBundle\DataFixtures\ORM\LoadData',
            'SolidInvoice\PaymentBundle\DataFixtures\ORM\LoadData',
        ], true);
    }

    public function testGetAll()
    {
        $data = $this->requestGet('/api/payments');

        $this->assertSame(
            [
                [
                    'method' => null,
                    'status' => 'captured',
                    'message' => null,
                    'completed' => null,
                ],
            ],
            $data
        );
    }

    public function testGet()
    {
        $data = $this->requestGet('/api/payments/1000');

        $this->assertSame(
            [
                'method' => null,
                'status' => 'captured',
                'message' => null,
                'completed' => null,
            ],
            $data
        );
    }
}
