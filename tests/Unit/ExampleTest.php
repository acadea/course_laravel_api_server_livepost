<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
        $this->assertSame('abc', 'abc');
        $this->assertSame('heyaa', 'heyaa');
        $this->assertSame('hoho', 'hoho');
    }
}
