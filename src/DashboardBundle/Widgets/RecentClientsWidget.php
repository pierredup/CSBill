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

namespace SolidInvoice\DashboardBundle\Widgets;

use Doctrine\Common\Persistence\ManagerRegistry;
use SolidInvoice\ClientBundle\Entity\Client;
use SolidInvoice\ClientBundle\Repository\ClientRepository;

class RecentClientsWidget implements WidgetInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;

    public function __construct(ManagerRegistry $registry)
    {
        $this->manager = $registry->getManager();
    }

    public function getData(): array
    {
        /** @var ClientRepository $clientRepository */
        $clientRepository = $this->manager->getRepository(Client::class);

        $clients = $clientRepository->getRecentClients();

        return ['clients' => $clients];
    }

    public function getTemplate(): string
    {
        return '@SolidInvoiceDashboard/Widget/recent_clients.html.twig';
    }
}
