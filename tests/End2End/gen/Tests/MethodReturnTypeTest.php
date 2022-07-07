<?php

declare(strict_types = 1);

namespace Tests;

use NitriaTest\End2End\Asset\SomeClass;

class MethodReturnTypeTest
{

    /**
     * 
     * @return SomeClass[]
     */
    public function notNullableReturnType() : array
    {
        return [];
    }

    /**
     * 
     * @return SomeClass[]|null
     */
    public function nullableReturnType() : ?array
    {
        return [];
    }

    /**
     * 
     * @return SomeClass
     */
    public function classReturnType() : SomeClass
    {
        return new SomeClass();
    }

    /**
     * 
     * @return SomeClass|null
     */
    public function classReturnTypeNullable() : ?SomeClass
    {
        return new SomeClass();
    }

    /**
     * 
     * @return int[]
     */
    public function notNullableReturnTypeSimple() : array
    {
        return [];
    }

    /**
     * 
     * @return int[]|null
     */
    public function nullableReturnTypeSimple() : ?array
    {
        return [];
    }

    /**
     * 
     * @return int
     */
    public function simpleReturnType() : int
    {
        return 19;
    }

    /**
     * 
     * @return int|null
     */
    public function simpleReturnTypeNullable() : ?int
    {
        return 7;
    }

    /**
     * 
     * @return \PHPUnit_Framework_TestCase|null
     */
    public function defaultNamespaceReturnType() : ?\PHPUnit_Framework_TestCase
    {
        return null;
    }

    /**
     * @param mixed $mixed
     * 
     * @return mixed
     */
    public function mixedType($mixed)
    {
        return [];
    }
}