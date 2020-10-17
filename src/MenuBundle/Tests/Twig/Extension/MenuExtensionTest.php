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

namespace SolidInvoice\MenuBundle\Tests\Twig\Extension;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery as M;
use PHPUnit\Framework\TestCase;
use SolidInvoice\MenuBundle\Twig\Extension\MenuExtension;

class MenuExtensionTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var \Twig\Extension\ExtensionInterface
     */
    private $extension;

    protected function setUp(): void
    {
        $this->extension = new MenuExtension();
    }

    public function testGetName()
    {
        $this->assertSame('solidinvoice_menu.twig.extension', $this->extension->getName());
    }

    public function testGetFunctions()
    {
        $functions = $this->extension->getFunctions();

        $this->assertIsArray($functions);

        $this->assertContainsOnlyInstancesOf('Twig_SimpleFunction', $functions);
    }

    public function testRenderMenu()
    {
        $provider = M::mock('Knp\Menu\Provider\MenuProviderInterface');
        $this->extension->setProvider($provider);

        $renderer = M::mock('SolidInvoice\MenuBundle\RendererInterface');
        $this->extension->setRenderer($renderer);

        $location = 'abc';
        $menu = new \SplPriorityQueue();

        $provider->shouldReceive('get')
            ->once()
            ->with($location)
            ->andReturn($menu);

        $renderer->shouldReceive('build')
            ->once()
            ->with($menu, ['a' => 'b'])
            ->andReturn('123');

        $this->assertSame('123', $this->extension->renderMenu($location, ['a' => 'b']));

        $provider->shouldHaveReceived('get')
            ->once()
            ->with($location);

        $renderer->shouldHaveReceived('build')
            ->once()
            ->with($menu, ['a' => 'b']);
    }
}
