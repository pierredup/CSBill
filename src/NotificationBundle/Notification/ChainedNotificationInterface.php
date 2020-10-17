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

use Namshi\Notificator\NotificationInterface;

interface ChainedNotificationInterface extends NotificationInterface
{
    /**
     * Returns an array of all the notifications to publish.
     *
     * @return NotificationInterface[]
     */
    public function getNotifications(): array;

    /**
     * Add a notification to the chain.
     */
    public function addNotification(NotificationInterface $notification);
}
