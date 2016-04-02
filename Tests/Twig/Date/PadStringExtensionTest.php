<?php

namespace DMS\Bundle\TwigExtensionBundle\Tests\Twig\Text;

use DMS\Bundle\TwigExtensionBundle\Twig\Text\PadStringExtension;

class PadStringExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PadStringExtension
     */
    protected $extension;

    public function setUp()
    {
        $this->extension = new PadStringExtension();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider provideForException
     */
    public function testPadStringFilterInputValidation($value, $padCharacter, $maxLength, $padType = 'STR_PAD_RIGHT')
    {
        $result = $this->extension->padStringFilter($value, $padCharacter, $maxLength, $padType);
    }

    public function provideForException()
    {
        return [
            'max length must be an integer'        => ['ps', 'o', '4', 'STR_PAD_LEFT'],
            'pad character cannot be null'         => ['woof', null, 6, 'STR_PAD_BOTH'],
            'pad character cannot be empty'        => ['squ', '', 2, 'STR_PAD_RIGHT'],
            'invalid pad character and max length' => ['NO', '', '5'],
        ];
    }

    /**
     * @param $value
     * @param $padCharacter
     * @param $maxLength
     * @param string $padType
     * @param $expectedOutput
     *
     * @dataProvider provideForPadTest
     */
    public function testPadStringFilterOutput($expectedOutput, $value, $padCharacter, $maxLength, $padType = 'STR_PAD_RIGHT')
    {
        $result = $this->extension->padStringFilter($value, $padCharacter, $maxLength, $padType);
        $this->assertEquals($expectedOutput, $result);
    }

    public function provideForPadTest()
    {
        return [
            ['oops', 'ps', 'o', 4, 'STR_PAD_LEFT'],
            ['-woof-', 'woof', '-', 6, 'STR_PAD_BOTH'],
            ['squeeeee', 'squ', 'e', 8, 'STR_PAD_RIGHT'],
            ['NOOOO', 'NO', 'O', 5],
            ['hahahahaha', '', 'ha', 10],
        ];
    }
}
