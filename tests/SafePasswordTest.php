<?php
namespace KanxPHP\Core\Tests;

use PHPUnit\Framework\TestCase;
use KanxPHP\Core\SafePassword;

class SafePasswordTest extends TestCase 
{
    public function testHashReturnsValidVerifyableString() 
    {
        $pass = "kanx-secret-123";
        $hash = SafePassword::hash($pass);
        $this->assertIsString($hash);
        $this->assertTrue(password_verify($pass, $hash));
    }

    public function testVerifyReturnsTrueOnMatch() 
    {
        $pass = "kanx-password";
        $hash = SafePassword::hash($pass);
        
        $this->assertTrue(SafePassword::verify($pass, $hash));
        $this->assertFalse(SafePassword::verify("wrong-password", $hash));
    }

    public function testNeedsRehashDetectsOutdatedAlgorithms()
    {
        // Create an old-style Bcrypt hash manually
        $oldHash = password_hash("password", PASSWORD_BCRYPT);
        
        // It should return true because our standard is PASSWORD_ARGON2ID
        $this->assertTrue(SafePassword::needsRehash($oldHash));
        
        // Create a modern hash
        $modernHash = SafePassword::hash("password");
        
        // It should return false as it already matches our standard
        $this->assertFalse(SafePassword::needsRehash($modernHash));
    }

}