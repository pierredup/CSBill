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

namespace SolidInvoice\ClientBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ContactCollectionType extends AbstractType
{
    public function getParent(): string
    {
        return CollectionType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'contacts';
    }
}
