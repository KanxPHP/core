<?php

namespace KanxPHP\Core\Tests;

use PHPUnit\Framework\TestCase;
use KanxPHP\Core\SafeInput;

class SafeInputTest extends TestCase 
{
    public function testGetSanitizes() 
    {
        $_GET['test'] = "  <b>Vulnerable</b>  ";
        // SafeInput should automatically trim and strip tags
        $this->assertEquals('Vulnerable', SafeInput::get('test'));
    }
}
