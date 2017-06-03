<?php

namespace DMS\Bundle\TwigExtensionBundle\Tests\Integration;

use DMS\Bundle\TwigExtensionBundle\DependencyInjection\DMSTwigExtensionExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Translation\TranslatorInterface;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerBuilder
     */
    private $container;

    /**
     * @before
     * @return void
     */
    public function buildContainer()
    {
        $this->container = new ContainerBuilder();

        // Cover external dependencies
        $this->container->set('translator', $this->prophesize(TranslatorInterface::class)->reveal());

        $extension = new DMSTwigExtensionExtension();
        $extension->load([], $this->container);

        $this->container->compile();
    }

    public function testContainerBoots()
    {
        $this->container->get('dms_twig_extension.dms.textual_date');
        $this->container->get('dms_twig_extension.fabpot.array');

        if (method_exists($this->container, 'isCompiled')) {
            self::assertTrue($this->container->isCompiled());
        } else {
            self::assertTrue($this->container->isFrozen());
        }

    }
}
