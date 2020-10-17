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

namespace SolidInvoice\UserBundle\Tests\Form\Type;

use SolidInvoice\CoreBundle\Tests\FormTestCase;
use SolidInvoice\UserBundle\Entity\User;
use SolidInvoice\UserBundle\Form\Type\ProfileType;

class ProfileTypeTest extends FormTestCase
{
    public function testSubmit()
    {
        $mobile = $this->faker->phoneNumber;

        $formData = [
            'mobile' => $mobile,
        ];

        $object = new User();
        $object->setMobile($mobile);

        $this->assertFormData(ProfileType::class, $formData, $object);
    }
}
