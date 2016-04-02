<?php

namespace DMS\Bundle\TwigExtensionBundle\Tests\Twig\Date;

use DMS\Bundle\TwigExtensionBundle\Twig\Date\TextualDateExtension;

class TextualDateExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TextualDateExtension
     */
    protected $extension;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $translator;

    public function setUp()
    {
        $this->buildDependencies();
        $this->extension = new TextualDateExtension($this->translator);
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider provideForException
     */
    public function testTextualDateFilterInputValidation($date)
    {
        $result = $this->extension->textualDateFilter($date);
    }

    public function provideForException()
    {
        return [
            [date('ymd')],
            ['02/02/2012'],
            [time()],
        ];
    }

    /**
     * @param $dateDescription
     * @param $expectedOutput
     *
     * @return void
     *
     * @dataProvider provideForTextual
     */
    public function testTextualDateFilter($dateDescription, $expectedOutput)
    {
        $date = new \DateTime($dateDescription);

        $this->translator->expects($this->once())
            ->method('transChoice')
            ->will($this->returnCallback(function () { return func_get_args(); }));

        $result = $this->extension->textualDateFilter($date);

        $this->assertEquals($expectedOutput, $result[0]);
    }

    public function provideForTextual()
    {
        return [
            ['-16 seconds', 'ago.s'],
            ['-1 minute',   'ago.i'],
            ['-5 minutes',  'ago.i'],
            ['-1 hour',     'ago.h'],
            ['-5 hour',     'ago.h'],
            ['-30 hour',    'date.yesterday'],
            ['-31 hour',    'date.yesterday'],
            ['+31 hour',    'date.tomorrow'],
            ['-1 day',      'date.yesterday'],
            ['+1 day',      'date.tomorrow'],
            ['-2 day',      'ago.d'],
            ['-3 day',      'ago.d'],
            ['-31 day',     'ago.m'],
            ['-367 day',    'ago.y'],
            ['+367 day',    'next.y'],
            ['+40 days',    'next.m'],
            ['+48 hours',   'next.d'],
            ['+5 hour',     'next.h'],
            ['+5 minutes',  'next.i'],
            ['+25 seconds',  'next.s'],
            ['now',         'date.just_now'],
        ];
    }

    protected function buildDependencies()
    {
        $this->translator = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Translation\Translator')
            ->disableOriginalConstructor()->getMock();
    }
}
