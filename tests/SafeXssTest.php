<?php

namespace KanxPHP\Core\Tests;

use PHPUnit\Framework\TestCase;
use KanxPHP\Core\SafeXss;

class SafeXssTest extends TestCase 
{
    public function testCleanStripsTags() {
        $input = "<script>alert('xss')</script>Hello";
        $this->assertEquals('Hello', SafeXss::clean($input));
    }
    public function testDetection() {
        $this->assertTrue(SafeXss::detect("<iframe src='...'>"));
        $this->assertFalse(SafeXss::detect("Clean plain text"));
    }
}
