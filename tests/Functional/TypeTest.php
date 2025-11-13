<?php

declare(strict_types=1);

namespace NitriaTest\Functional;

use Codeception\Test\Unit;
use Nitria\Type;

class TypeTest extends Unit
{
    const CLASS_NAME = 'Some\\Random\\Class';

    public function testNoType()
    {
        $type = new Type(null);
        $this->assertSame(null, $type->getCodeType());
        $this->assertSame("mixed", $type->getDocBlockType());
        $this->assertFalse($type->needsUseStatement());
        $this->assertNull($type->getUseStatement());
    }

    public function testPrimitiveType()
    {
        $type = new Type("int");
        $this->assertSame("int", $type->getCodeType());
        $this->assertSame("int", $type->getDocBlockType());
        $this->assertFalse($type->needsUseStatement());
        $this->assertNull($type->getUseStatement());
    }

    public function testPrimitiveTypeArray()
    {
        $type = new Type("int[]");
        $this->assertSame("array", $type->getCodeType());
        $this->assertSame("int[]", $type->getDocBlockType());
        $this->assertFalse($type->needsUseStatement());
        $this->assertNull($type->getUseStatement());
    }

    public function testClassDefaultNamespaceType()
    {
        $type = new Type('MyClass');
        $this->assertSame("\\MyClass", $type->getCodeType());
        $this->assertSame("\\MyClass", $type->getDocBlockType());
        $this->assertFalse($type->needsUseStatement());
        $this->assertNull($type->getUseStatement());
    }

    public function testClassType()
    {
        $type = new Type(self::CLASS_NAME);
        $this->assertSame("Class", $type->getCodeType());
        $this->assertSame("Class", $type->getDocBlockType());
        $this->assertTrue($type->needsUseStatement());
        $this->assertSame(self::CLASS_NAME, $type->getUseStatement());
    }

    public function testClassTypeArray()
    {
        $type = new Type(self::CLASS_NAME . '[]');
        $this->assertSame("array", $type->getCodeType());
        $this->assertSame("Class[]", $type->getDocBlockType());
        $this->assertTrue($type->needsUseStatement());
        $this->assertSame(self::CLASS_NAME, $type->getUseStatement());
    }

}