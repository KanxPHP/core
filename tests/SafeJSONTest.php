<?php

namespace KanxPHP\Core\Tests;

use PHPUnit\Framework\TestCase;
use KanxPHP\Core\SafeJSON;
use KanxPHP\Core\Exceptions\IntegrityException;

class SafeJSONTest extends TestCase
{
    public function testParseReturnsArrayOnValidJson()
    {
        $json = '{"name": "KanxPHP", "status": "secure"}';
        $result = SafeJSON::parse($json);
        
        $this->assertIsArray($result);
        $this->assertEquals("KanxPHP", $result['name']);
    }

    public function testParseThrowsExceptionOnInvalidJson()
    {
        $this->expectException(\JsonException::class);
        SafeJSON::parse('{"invalid": json}');
    }

    public function testParseThrowsIntegrityExceptionOnNonArray()
    {
        $this->expectException(IntegrityException::class);
        // Valid JSON but returns a string, not an array
        SafeJSON::parse('"Just a string"');
    }

    public function testParseThrowsExceptionOnNonArray()
    {
        $this->expectException(IntegrityException::class);
        $this->expectExceptionMessage("JSON parsed successfully but did not return an array structure.");

        // Valid JSON, but it's a string, not an array/object
        SafeJSON::parse('"Just a string"');
    }

}