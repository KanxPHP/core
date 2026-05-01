<?php

namespace KanxPHP\Core\Tests;

use PHPUnit\Framework\TestCase;
use KanxPHP\Core\SafeCache;

class SafeCacheTest extends TestCase 
{
    public function testSetAndGet() 
    {
        $data = ['domain' => 'example.com', 'trust' => 100];
        SafeCache::set('test_key', $data, '+1 minute');
        $this->assertEquals($data, SafeCache::get('test_key'));
    }
    public function testExpiration() {
        SafeCache::set('expired_key', 'data', '-1 minute');
        $this->assertNull(SafeCache::get('expired_key'));
    }
}
