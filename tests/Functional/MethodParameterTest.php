<?php

declare(strict_types=1);

namespace NitriaTest\Functional;

use Codeception\Test\Unit;
use Nitria\MethodParameter;
use Nitria\Type;

class MethodParameterTest extends Unit
{

    /**
     *
     */
    public function testMethodParameter()
    {
        $type = new Type("string");
        $methodParameter = new MethodParameter($type, "hello");

        $this->assertSame('@param string $hello', $methodParameter->getPHPDocLine());
        $this->assertSame('string $hello', $methodParameter->getSignaturePart());
    }

    /**
     *
     */
    public function testMethodParameterOptional()
    {
        $type = new Type("\\SomeClass");
        $methodParameter = new MethodParameter($type, "hello", 'null', "my doc comment");
        $this->assertSame('@param \\SomeClass|null $hello my doc comment', $methodParameter->getPHPDocLine());
        $this->assertSame('\\SomeClass $hello = null', $methodParameter->getSignaturePart());


        $type = new Type("\\SomeClass");
        $methodParameter = new MethodParameter($type, "hello", null, "my doc comment", true);
        $this->assertSame('@param \\SomeClass|null $hello my doc comment', $methodParameter->getPHPDocLine());
        $this->assertSame('?\\SomeClass $hello', $methodParameter->getSignaturePart());

        $type = new Type("\\SomeClass");
        $methodParameter = new MethodParameter($type, "hello", null, "my doc comment", false);
        $this->assertSame('@param \\SomeClass $hello my doc comment', $methodParameter->getPHPDocLine());
        $this->assertSame('\\SomeClass $hello', $methodParameter->getSignaturePart());


        $type = new Type("string");
        $methodParameter = new MethodParameter($type, "hello", 'null');
        $this->assertSame('@param string|null $hello', $methodParameter->getPHPDocLine());
        $this->assertSame('string $hello = null', $methodParameter->getSignaturePart());

        $type = new Type("string");
        $methodParameter = new MethodParameter($type, "hello", null, null, true);
        $this->assertSame('@param string|null $hello', $methodParameter->getPHPDocLine());
        $this->assertSame('?string $hello', $methodParameter->getSignaturePart());


        $type = new Type("string");
        $methodParameter = new MethodParameter($type, "hello", null, null, false);
        $this->assertSame('@param string $hello', $methodParameter->getPHPDocLine());
        $this->assertSame('string $hello', $methodParameter->getSignaturePart());

    }

}