<?php

declare(strict_types = 1);

namespace NitriaTest\Functional;

use Nitria\StringUtil;

class StringUtilTest extends \PHPUnit_Framework_TestCase
{

    public function testEndsWith()
    {
        $this->assertTrue(StringUtil::endsWith("test.php", ".php"));
        $this->assertTrue(StringUtil::endsWith("test.php", ""));
        $this->assertFalse(StringUtil::endsWith("test.php", ".java"));
    }


    public function testGetEndAfterLast() {
        $this->assertSame("Hello", StringUtil::getEndAfterLast("test\\test\\Hello", "\\"));
        $this->assertSame("test\\test\\Hello", StringUtil::getEndAfterLast("test\\test\\Hello", "x"));

    }

}