<?php

declare(strict_types = 1);

namespace NitriaTest\Functional;

use Nitria\MethodReturnType;
use Nitria\Type;

class MethodReturnTypeTest extends \PHPUnit_Framework_TestCase
{

    public function testNoReturnType()
    {
        $methodReturnType = new MethodReturnType(null);
        $this->assertSame("@return void", $methodReturnType->getDocBlockReturnType());
        $this->assertSame("", $methodReturnType->getSignatureReturnType());
        $this->assertFalse($methodReturnType->hasReturnType());
    }

    public function testReturnType()
    {
        $type = new Type("int");
        $methodReturnType = new MethodReturnType($type, false);
        $this->assertSame("@return int", $methodReturnType->getDocBlockReturnType());
        $this->assertSame(" : int", $methodReturnType->getSignatureReturnType());
        $this->assertTrue($methodReturnType->hasReturnType());
    }

    public function testOptionalReturnType()
    {
        $type = new Type("int");
        $methodReturnType = new MethodReturnType($type, true);
        $this->assertSame("@return int|null", $methodReturnType->getDocBlockReturnType());
        $this->assertSame(" : ?int", $methodReturnType->getSignatureReturnType());
        $this->assertTrue($methodReturnType->hasReturnType());
    }

    public function testArrayReturnType()
    {
        $type = new Type("SomeClass[]");
        $methodReturnType = new MethodReturnType($type, false);
        $this->assertSame("@return \\SomeClass[]", $methodReturnType->getDocBlockReturnType());
        $this->assertSame(" : array", $methodReturnType->getSignatureReturnType());
        $this->assertTrue($methodReturnType->hasReturnType());
    }



}