<?php

declare(strict_types=1);

namespace NitriaTest\End2End;

use Nitria\ClassGenerator;

class End2EndTest extends \PHPUnit_Framework_TestCase
{

    const BASE_DIR = __DIR__ . '/gen/';


    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->deleteGeneratedCode(self::BASE_DIR);
    }


    /**
     *
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->deleteGeneratedCode(self::BASE_DIR);
    }


    /**
     *
     */
    protected function deleteGeneratedCode(string $dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . "/" . $object)) {
                    $this->deleteGeneratedCode($dir . "/" . $object);
                } else {
                    unlink($dir . "/" . $object);
                }
            }
        }
        rmdir($dir);
    }


    /**
     * @param ClassGenerator $classGenerator
     *
     * @return object
     * @throws \ReflectionException
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
     * @throws \ReflectionException
     */
    protected function getReflectClass(ClassGenerator $classGenerator): \ReflectionClass
    {
        require_once self::BASE_DIR . $classGenerator->getPSR0File();

        $reflectClass = new \ReflectionClass($classGenerator->getClassName());
        $this->assertNotNull($reflectClass);

        $object = $reflectClass->newInstance();
        $this->assertNotNull($object);

        return $reflectClass;
    }


    /**
     * @param ClassGenerator $classGenerator
     * @param string $psr4Prefix
     *
     * @return \ReflectionClass
     * @throws \ReflectionException
     */
    protected function getReflectClassPSR4(ClassGenerator $classGenerator, string $psr4Prefix): \ReflectionClass
    {
        require_once self::BASE_DIR . $classGenerator->getPSR4File($psr4Prefix);

        $reflectClass = new \ReflectionClass($classGenerator->getClassName());
        $this->assertNotNull($reflectClass);

        $object = $reflectClass->newInstance();
        $this->assertNotNull($object);

        return $reflectClass;
    }
}