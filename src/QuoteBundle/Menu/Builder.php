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

namespace SolidInvoice\QuoteBundle\Menu;

use SolidInvoice\MenuBundle\Core\AuthenticatedMenu;
use SolidInvoice\MenuBundle\ItemInterface;

/**
 * Menu items for quotes.
 */
class Builder extends AuthenticatedMenu
{
    /**
     * Menu builder for the quotes index.
     *
     * @throws \InvalidArgumentException
     */
    public function sidebar(ItemInterface $menu)
    {
        $menu->addHeader('quotes');
        $menu->addChild(QuoteMenu::list());
        $menu->addChild(QuoteMenu::create());

        $menu->addDivider();
    }
}
