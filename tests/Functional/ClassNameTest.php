<?php

declare(strict_types = 1);

namespace NitriaTest\Functional;

use Nitria\ClassName;

class ClassNameTest extends \PHPUnit_Framework_TestCase
{

    public function testClassName()
    {
        $className = '\Random\Class\Name';

        $className = new ClassName($className);
        $this->assertSame('Random\Class\Name', $className->getClassName());
        $this->assertSame('Random\Class', $className->getNamespaceName());
        $this->assertSame('Name', $className->getClassShortName());

        $className = 'Random\Class\Name';

        $className = new ClassName($className);
        $this->assertSame('Random\Class\Name', $className->getClassName());
        $this->assertSame('Random\Class', $className->getNamespaceName());
        $this->assertSame('Name', $className->getClassShortName());

    }

    public function testDefaultNamespaceClass()
    {
        $className = '\PHPUnit_Framework_TestCase';

        $className = new ClassName($className);
        $this->assertSame('\PHPUnit_Framework_TestCase', $className->getClassName());
        $this->assertSame(null, $className->getNamespaceName());
        $this->assertSame('\PHPUnit_Framework_TestCase', $className->getClassShortName());

        $className = 'PHPUnit_Framework_TestCase';

        $className = new ClassName($className);
        $this->assertSame('\PHPUnit_Framework_TestCase', $className->getClassName());
        $this->assertSame(null, $className->getNamespaceName());
        $this->assertSame('\PHPUnit_Framework_TestCase', $className->getClassShortName());

    }
}