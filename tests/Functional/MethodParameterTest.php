<?php

declare(strict_types = 1);

namespace NitriaTest\Functional;

use Nitria\MethodParameter;
use Nitria\Type;

class MethodParameterTest extends \PHPUnit_Framework_TestCase
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
    }

}