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

namespace SolidInvoice\DataGridBundle\Repository;

use SolidInvoice\DataGridBundle\Exception\InvalidGridException;
use SolidInvoice\DataGridBundle\GridInterface;

class GridRepository
{
    /**
     * @var GridInterface[]
     */
    private $grids = [];

    public function addGrid(string $name, GridInterface $grid)
    {
        $this->grids[$name] = $grid;
    }

    /**
     * @throws InvalidGridException
     */
    public function find(string $name): GridInterface
    {
        if (!array_key_exists($name, $this->grids)) {
            throw new InvalidGridException($name);
        }

        return $this->grids[$name];
    }
}
