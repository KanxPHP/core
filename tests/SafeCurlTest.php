<?php

namespace KanxPHP\Core\Tests;

use PHPUnit\Framework\TestCase;
use KanxPHP\Core\SafeCurl;

class SafeCurlTest extends TestCase 
{
    public function testBlocksLocalhost() 
    {
        // Proves that internal/private IPs are denied
        $this->assertFalse(SafeCurl::fetch('http://127.0.0.1'));
        $this->assertFalse(SafeCurl::fetch('http://169.254.169.254'));
    }
    public function testHandlesInvalidUrl() {
        $this->assertFalse(SafeCurl::fetch('not-a-url'));
    }
}
