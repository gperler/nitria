<?php

declare(strict_types=1);

namespace NitriaTest\Functional;

use Codeception\Test\Unit;
use Nitria\MethodReturnType;
use Nitria\Type;

class MethodReturnTypeTest extends Unit
{

    /**
     * @return void
     */
    public function testNoReturnType(): void
    {
        $methodReturnType = new MethodReturnType(null);
        $this->assertSame("@return void", $methodReturnType->getDocBlockReturnType());
        $this->assertSame(": void", $methodReturnType->getSignatureReturnType());
        $this->assertFalse($methodReturnType->hasReturnType());
    }


    /**
     * @return void
     */
    public function testReturnType(): void
    {
        $type = new Type("int");
        $methodReturnType = new MethodReturnType($type, false);
        $this->assertSame("@return int", $methodReturnType->getDocBlockReturnType());
        $this->assertSame(": int", $methodReturnType->getSignatureReturnType());
        $this->assertTrue($methodReturnType->hasReturnType());
    }


    /**
     * @return void
     */
    public function testOptionalReturnType(): void
    {
        $type = new Type("int");
        $methodReturnType = new MethodReturnType($type, true);
        $this->assertSame("@return int|null", $methodReturnType->getDocBlockReturnType());
        $this->assertSame(": ?int", $methodReturnType->getSignatureReturnType());
        $this->assertTrue($methodReturnType->hasReturnType());
    }


    /**
     * @return void
     */
    public function testArrayReturnType(): void
    {
        $type = new Type("SomeClass[]");
        $methodReturnType = new MethodReturnType($type, false);
        $this->assertSame("@return \\SomeClass[]", $methodReturnType->getDocBlockReturnType());
        $this->assertSame(": array", $methodReturnType->getSignatureReturnType());
        $this->assertTrue($methodReturnType->hasReturnType());
    }


}