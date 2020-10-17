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

namespace SolidInvoice\ClientBundle\Form\Handler;

class ContactAddFormHandler extends AbstractContactFormHandler
{
    public function getTemplate(): string
    {
        return '@SolidInvoiceClient/Ajax/contact_add.html.twig';
    }
}
