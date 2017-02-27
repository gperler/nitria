<?php

declare(strict_types = 1);

namespace NitriaTest\End2End;

use Nitria\ClassGenerator;
use Nitria\File;

class End2EndTest extends \PHPUnit_Framework_TestCase
{

    const BASE_DIR = __DIR__ . '/gen/';

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->deleteGeneratedCode();
    }

    /**
     *
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->deleteGeneratedCode();
    }

    /**
     *
     */
    protected function deleteGeneratedCode()
    {
        $file = new File(self::BASE_DIR);
        $file->deleteRecursively();
    }

    /**
     * @param ClassGenerator $classGenerator
     *
     * @return mixed
     */
    protected function getReflectInstance(ClassGenerator $classGenerator)
    {
        require_once self::BASE_DIR . $classGenerator->getPSR0File();

        $reflectClass = new \ReflectionClass($classGenerator->getClassName());
        $this->assertNotNull($reflectClass);

        $object = $reflectClass->newInstance();
        $this->assertNotNull($object);

        return $object;
    }

    /**
     * @param ClassGenerator $classGenerator
     *
     * @return \ReflectionClass
     */
    protected function getReflectClass(ClassGenerator $classGenerator)
    {
        require_once self::BASE_DIR . $classGenerator->getPSR0File();

        $reflectClass = new \ReflectionClass($classGenerator->getClassName());
        $this->assertNotNull($reflectClass);

        $object = $reflectClass->newInstance();
        $this->assertNotNull($object);

        return $reflectClass;
    }
}