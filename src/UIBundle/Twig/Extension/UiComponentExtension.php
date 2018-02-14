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

namespace SolidInvoice\UIBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use function _\escape;

class UiComponentExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('ui_component', function (string $component, array $props = []) {
                return '<component is="'.$component.'" v-bind="'.escape(json_encode($props)).'">';
            }, ['is_safe' => ['html']]),
        ];
    }
}