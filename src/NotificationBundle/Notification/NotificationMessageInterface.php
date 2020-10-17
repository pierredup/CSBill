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

namespace SolidInvoice\NotificationBundle\Notification;

use SolidInvoice\UserBundle\Entity\User;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

interface NotificationMessageInterface
{
    public function getHtmlContent(Environment $twig): string;

    public function getTextContent(Environment $twig): string;

    public function getSubject(TranslatorInterface $translator): string;

    public function setUsers(array $users);

    /**
     * @return User[]
     */
    public function getUsers(): array;

    public function getParameters(): array;

    public function setParameters(array $parameters);
}
