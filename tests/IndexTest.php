<?php
use PHPUnit\Framework\TestCase;

class Index extends TestCase
{
    public function testTrueAssetsToTrue()
    {
        $condition = true;
        $this->assertTrue($condition, 'Test ran successfully');
    }
}
