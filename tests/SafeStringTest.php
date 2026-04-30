<?php

namespace KanxPHP\Core\Tests;

use PHPUnit\Framework\TestCase;
use KanxPHP\Core\SafeString;

class SafeStringTest extends TestCase
{
    public function testLimitHandlesUtf8Safely()
    {
        $input = "Security is 🛡️ essential";
        // 12 (text) + 2 (emoji) + 3 (suffix) = 17
        $result = SafeString::limit($input, 17);
        
        $this->assertStringContainsString('🛡️', $result);
        $this->assertStringEndsWith('...', $result);
    }

    public function testEqualsProtectsAgainstTimingAttacks()
    {
        $secret = "kanx_api_key_123";
        
        $this->assertTrue(SafeString::equals($secret, $secret));
        $this->assertFalse(SafeString::equals($secret, "wrong_key"));
    }

    public function testRandomProducesSecureEntropy()
    {
        $token1 = SafeString::random(32);
        $token2 = SafeString::random(32);

        $this->assertEquals(32, strlen($token1));
        $this->assertNotEquals($token1, $token2); // Probability of collision is near zero
    }
}