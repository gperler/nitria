<?php

declare(strict_types = 1);

namespace NitriaTest\End2End;

use Nitria\ClassGenerator;
use NitriaTest\End2End\Asset\CustomMethod;

class CustomMethodTest extends End2EndTest
{


    public function testCustomMethod() {
        $className = 'Tests\CustomMethodTest';


        $classGenerator = new ClassGenerator($className, true);
        $classGenerator->addDocBlockComment("@Author: the maschine");

        $method = new CustomMethod($classGenerator, "myName", "public", false);
        $method->addMyCustomMethod();
        $method->setReturnType("float", false);
        $classGenerator->addMethodObject($method);

        $classGenerator->writeToPSR0(self::BASE_DIR);

        $instance = $this->getReflectInstance($classGenerator);
        //$instance->myName();

    }
}