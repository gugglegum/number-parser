<?php

declare(strict_types=1);

use gugglegum\NumberParser\NumberParser;
use PHPUnit\Framework\TestCase;

class ParseIntTest extends TestCase
{
    /**
     * @dataProvider validIntNumbersProvider
     */
    public function testParseInt($input, $expectedResult)
    {
        $this->assertSame($expectedResult, NumberParser::parseInt($input));
    }

    /**
     * @dataProvider invalidIntNumbersProvider
     */
    public function testParseIntErrors(string $str)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value \"{$str}\" - expected integer number");
        NumberParser::parseInt($str);
    }

    public function validIntNumbersProvider(): array
    {
        $positiveNumbers = [
            ["1", 1],
            ["12345", 12345],
            ["1234567890", 1234567890],
        ];

        $negativeNumbers = [];
        foreach ($positiveNumbers as $values) {
            $negativeNumbers[] = ['-' . $values[0], -$values[1]];
        }

        $zeroNumbers = [
            ['0', 0],
        ];

        return array_merge($positiveNumbers, $negativeNumbers, $zeroNumbers);
    }

    public function invalidIntNumbersProvider(): array
    {
        return [
            ['a567'],
            ['567a'],
            [' 567'],
            ['567 '],
            ['56_7'],
            ['-0'],
            ['00'],
            ['-00'],
            ['01'],
            ['+1'],
            ['.1'],
            ['1e2'],
            [''],
        ];
    }

}
