<?php

use \Mockery as m;

class ValidateVatNumberTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_be_initialised()
    {
        $this->assertInstanceOf(
            \DrawMyAttention\ValidateVatNumber\ValidateVatNumber::class,
            new \DrawMyAttention\ValidateVatNumber\ValidateVatNumber()
        );
    }

    /** @test */
    public function it_removes_spaces_from_the_vat_number()
    {
        $validator = new \DrawMyAttention\ValidateVatNumber\ValidateVatNumber();
        $this->assertEquals('GB123456', $validator->cleanVatNumber('GB 123 456 '));
    }

}
