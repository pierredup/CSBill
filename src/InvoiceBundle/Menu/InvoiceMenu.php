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

namespace SolidInvoice\InvoiceBundle\Menu;

/**
 * Menu items for invoices.
 */
class InvoiceMenu
{
    public static function list(): array
    {
        return [
            'invoice.menu.list',
            [
                'route' => '_invoices_index',
                'extras' => [
                    'icon' => 'file-text-o',
                ],
            ],
        ];
    }

    public static function create(): array
    {
        return [
            'client.menu.create.invoice',
            [
                'extras' => [
                    'icon' => 'file-text-o',
                ],
                'route' => '_invoices_create',
            ],
        ];
    }
}
