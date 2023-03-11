<?php

declare(strict_types=1);

use gugglegum\NumberParser\NumberParser;
use PHPUnit\Framework\TestCase;

class ParseFloatTest extends TestCase
{
    /**
     * @dataProvider validFloatNumbersProvider
     */
    public function testParseFloat($input, $expectedResult)
    {
        $this->assertSame($expectedResult, NumberParser::parseFloat($input));
    }

    /**
     * @dataProvider validFloatNumbersProvider
     * @dataProvider validExponentNumbersProvider
     */
    public function testParseFloatExponent($input, $expectedResult)
    {
        $this->assertSame($expectedResult, NumberParser::parseFloatExponent($input));
    }

    /**
     * @dataProvider invalidFloatNumbersProvider
     * @dataProvider invalidExponentNumbersProvider
     * @dataProvider validExponentNumbersProvider
     */
    public function testParseFloatErrors(string $str)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value \"{$str}\" - expected float number");
        NumberParser::parseFloat($str);
    }

    /**
     * @dataProvider invalidFloatNumbersProvider
     * @dataProvider invalidExponentNumbersProvider
     */
    public function testParseFloatExponentErrors(string $str)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value \"{$str}\" - expected float number");
        NumberParser::parseFloatExponent($str);
    }

    public function validFloatNumbersProvider(): array
    {
        $positiveNumbers = [
            ['0.1', 0.1],
            ['0.10', 0.1],
            ['.432', 0.432],
            ['1', 1.0],
            ['1.2', 1.2],
            ['12345', 12345.0],
            ['12345.4', 12345.4],
            ['1234567890', 1234567890.0],
            ['1234567890.6', 1234567890.6],
        ];

        $negativeNumbers = [];
        foreach ($positiveNumbers as $values) {
            $negativeNumbers[] = ['-' . $values[0], -$values[1]];
        }

        $zeroNumbers = [
            ['0', 0.0],
            ['0.0', 0.0],
        ];

        return array_merge($positiveNumbers, $negativeNumbers, $zeroNumbers);
    }

    public function validExponentNumbersProvider(): array
    {
        $positiveNumbers = [
            ['1e2', 100.0],
            ['1e+2', 100.0],
            ['1e-2', 0.01],
            ['12e2', 1200.0],
            ['123e-1', 12.3],
            ['1.23e2', 123.0],
            ['12.3e1', 123.0],
            ['0.1e2', 10.0],
            ['0.1e-2', 0.001],
            ['.123e2', 12.3],
            ['.123e-2', 0.00123],
        ];

        $negativeNumbers = [];
        foreach ($positiveNumbers as $values) {
            $negativeNumbers[] = ['-' . $values[0], -$values[1]];
        }

        $zeroNumbers = [
            ['0e2', 0.0],
            ['0e0', 0.0],
        ];

        return array_merge($positiveNumbers, $negativeNumbers, $zeroNumbers);
    }

    public function invalidFloatNumbersProvider(): array
    {
        return [
            ['a56.7'],
            ['56.7a'],
            [' 56.7'],
            ['56.7 '],
            ['5_6.7'],
            ['00'],
            ['00.0'],
            ['-0'],
            ['-0.0'],
            ['-.0'],
            ['+1.2'],
            ['.'],
            [''],
        ];
    }

    public function invalidExponentNumbersProvider(): array
    {
        return [
            ['e'],
            ['1e'],
            ['1e+'],
            ['1e-'],
            ['e+2'],
            ['e-2'],
            ['.e2'],
        ];
    }
}
