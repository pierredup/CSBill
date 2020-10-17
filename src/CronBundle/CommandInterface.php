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

namespace SolidInvoice\CronBundle;

interface CommandInterface
{
    /**
     * Checks if command is due to run.
     */
    public function isDue(): bool;

    /**
     * Process command.
     */
    public function process();
}
