<?php

/*
 * This file is part of the CSBill package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CSBill\PaymentBundle\Menu;

use Knp\Menu\ItemInterface;
use CSBill\CoreBundle\Menu\Core\AuthenticatedMenu;

class PaymentsMenu extends AuthenticatedMenu
{
    /**
     * Renders the top menu for payments
     *
     * @param ItemInterface $menu
     */
    public function topMenu(ItemInterface $menu)
    {
        $menu->addChild(
            'Payments',
            array(
                'route' => '_payments_index',
            )
        );
    }

    /**
     * Renders the top menu for payments
     *
     * @param ItemInterface $menu
     */
    public function topRightMenu(ItemInterface $menu)
    {
        $menu['system']->addChild(
            'Payment Methods',
            array(
                'route' => '_payment_settings_index',
                'extras' => array('icon' => 'credit-card')
            )
        );
    }
}