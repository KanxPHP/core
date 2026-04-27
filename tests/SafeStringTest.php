<?php
namespace KanxPHP\Core\Tests;

use PHPUnit\Framework\TestCase;
use KanxPHP\Core\SafeString;

class SafeStringTest extends TestCase 
{
    public function testLimitTruncatesLongString() 
    {
        $input = "This is a very long string that should be cut off.";
        $result = SafeString::limit($input, 10);
        $this->assertEquals("This is...", $result);
    }

    public function testHashReturnsValidVerifyableString() 
    {
        $pass = "kanx-secret-123";
        $hash = SafeString::hash($pass);
        $this->assertIsString($hash);
        $this->assertTrue(password_verify($pass, $hash));
    }

    public function testVerifyReturnsTrueOnMatch() 
    {
        $pass = "kanx-password";
        $hash = SafeString::hash($pass);
        
        $this->assertTrue(SafeString::verify($pass, $hash));
        $this->assertFalse(SafeString::verify("wrong-password", $hash));
    }
}