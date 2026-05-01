<?php

namespace KanxPHP\Core\Tests;

use PHPUnit\Framework\TestCase;
use KanxPHP\Core\SafeProxy;

class SafeProxyTest extends TestCase 
{
    public function testRotation() 
    {
        $list = ['1.1.1.1:80', '2.2.2.2:80'];
        SafeProxy::load($list);
        $p1 = SafeProxy::get();
        $p2 = SafeProxy::get();
        // Verifies that the pointer moved to the next item
        $this->assertNotEquals($p1, $p2);
    }
}
