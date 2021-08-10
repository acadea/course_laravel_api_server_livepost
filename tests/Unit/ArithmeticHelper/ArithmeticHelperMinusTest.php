<?php

namespace Tests\Unit\ArithmeticHelper;

use App\Helpers\Math\ArithmeticHelper;
use PHPUnit\Framework\TestCase;

class ArithmeticHelperMinusTest extends TestCase
{
    public function test_minus_can_subtract_numbers()
    {
        $num1 = 5;
        $num2 = 10;
        $subtraction = $num1 - $num2;

        $result = ArithmeticHelper::minus($num1, $num2);

        $this->assertSame($subtraction, $result, 'Does not minus numbers correctly.');
    }

    public function test_minus_can_take_in_multiple_numbers()
    {
        $num1 = 5;
        $num2 = 10;
        $num3 = 120;
        $sum = $num1 - $num2 - $num3;

        $result = ArithmeticHelper::minus($num1, $num2, $num3);

        $this->assertSame($sum, $result, "Sum does not work when has multiple arguments");
    }

    public function test_minus_cannot_take_in_string_arguments()
    {
        $this->expectException(\InvalidArgumentException::class);

        $result = ArithmeticHelper::minus("abc");
    }

    public function test_minus_cannot_take_in_array_argument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $result = ArithmeticHelper::minus(["abc"]);
    }

    public function test_minus_cannot_take_in_null_argument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $result = ArithmeticHelper::minus(null);
    }

    public function test_minus_cannot_take_in_boolean_argument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $result = ArithmeticHelper::minus(true);
    }

    public function test_minus_cannot_take_in_function_argument()
    {
        $this->expectException(\InvalidArgumentException::class);
        $result = ArithmeticHelper::minus(fn() => true);
    }

    public function test_minus_needs_at_least_one_argument()
    {
        $this->expectException(\InvalidArgumentException::class);

        $result = ArithmeticHelper::minus();

    }


}
