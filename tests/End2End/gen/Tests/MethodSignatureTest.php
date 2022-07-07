<?php

declare(strict_types = 1);

namespace Tests;

use NitriaTest\End2End\Asset\SomeClass;

class MethodSignatureTest
{

    /**
     * @param string $stringValue
     * @param SomeClass|null $classValue
     * 
     * @return string
     */
    public function iHaveASignature(string $stringValue = "default", SomeClass $classValue = null) : string
    {
        return $stringValue;
    }
}