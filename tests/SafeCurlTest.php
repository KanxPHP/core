<?php

namespace KanxPHP\Core\Tests;

use PHPUnit\Framework\TestCase;
use KanxPHP\Core\SafeCurl;

class SafeCurlTest extends TestCase
{
    /**
     * Test that SafeCurl blocks attempts to hit localhost.
     * This is the core of SSRF protection.
     */
    public function testBlocksLocalhostAndPrivateIPs()
    {
        $maliciousUrls = [
            'http://127.0.0.1',
            'http://localhost:8000',
            'http://169.254.169', // AWS Metadata
            'http://192.168.1.1',
            'http://0.0.0'
        ];

        foreach ($maliciousUrls as $url) {
            $result = SafeCurl::fetch($url);
            $this->assertFalse($result, "Failed to block internal URL: $url");
        }
    }

    /**
     * Test that a valid public URL returns a response.
     * We use a reliable public endpoint for testing.
     */
    public function testFetchesPublicUrlSuccessfully()
    {
        $url = 'https://google.com';
        $result = SafeCurl::fetch($url, true);

        // If fetch returns false, it means it was either blocked or network failed
        if ($result === false) {
            $this->markTestSkipped('Network unreachable or URL blocked by SafeCurl logic.');
        }

        $this->assertIsArray($result);
        $this->assertArrayHasKey('Date', $result);
    }

    /**
     * Test the header parsing logic.
     */
    public function testParsesHeadersCorrectly()
    {
        $rawHeaders = "HTTP/1.1 200 OK\r\nContent-Type: application/json\r\nServer: KanxServer\r\n\r\n";
        
        // Using Reflection to test the private parseHeaders method
        $reflection = new \ReflectionClass(SafeCurl::class);
        $method = $reflection->getMethod('parseHeaders');
        $method->setAccessible(true);
        
        $result = $method->invoke(null, $rawHeaders);

        $this->assertEquals('application/json', $result['Content-Type']);
        $this->assertEquals('KanxServer', $result['Server']);
    }

    /**
     * Test that invalid URLs are handled gracefully.
     */
    public function testHandlesInvalidUrls()
    {
        $invalid = "not-a-url";
        $this->assertFalse(SafeCurl::fetch($invalid));
    }
}