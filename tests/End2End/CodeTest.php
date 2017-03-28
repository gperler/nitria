<?php

declare(strict_types = 1);

namespace NitriaTest\End2End;

use Nitria\ClassGenerator;

class CodeTest extends End2EndTest
{


    public function testIfStatement()
    {

        $classGenerator = new ClassGenerator('Tests\IfStatementTest', true);

        $method = $classGenerator->addPublicMethod("sayIf");
        $method->addParameter("int", "int", null, 'This explains Why');
        $method->setReturnType("int", false);

        $method->addIfStart('$int === 1');
        $method->addCodeLine('return 1;');

        $method->addIfElseIf('$int === 2');
        $method->addCodeLine('return 2;');

        $method->addIfElse();
        $method->addCodeLine('return 3;');
        $method->addIfEnd();

        $classGenerator->writeToPSR0(self::BASE_DIR);

        $ifObject = $this->getReflectInstance($classGenerator);

        $this->assertSame(1, $ifObject->sayIf(1));
        $this->assertSame(2, $ifObject->sayIf(2));
        $this->assertSame(3, $ifObject->sayIf(15));

        $reflectionClass = $this->getReflectClass($classGenerator);
        $reflectionMethod = $reflectionClass->getMethod("sayIf");

        $this->assertNotNull($reflectionMethod);

        $this->assertTrue(strpos($reflectionMethod->getDocComment(), 'This explains Why') !== false);
    }

    public function testWhileStatement()
    {
        $classGenerator = new ClassGenerator('Tests\WhileStatementTest', true);

        $method = $classGenerator->addPublicMethod("sayWhile");
        $method->addParameter("int", "int");
        $method->setReturnType("string", false);

        $method->addCodeLine('$string = "";');
        $method->addWhileStart('$int++ < 10');
        $method->addCodeLine('$string .= "x";');
        $method->addWhileEnd();
        $method->addCodeLine('return $string;');

        $classGenerator->writeToPSR0(self::BASE_DIR);

        $whileObject = $this->getReflectInstance($classGenerator);

        $this->assertSame("x", $whileObject->sayWhile(9));
        $this->assertSame(10, strlen($whileObject->sayWhile(0)));
        $this->assertSame(4, strlen($whileObject->sayWhile(6)));
        $this->assertSame(0, strlen($whileObject->sayWhile(15)));

    }

    public function testForeachStatement()
    {
        $classGenerator = new ClassGenerator('Tests\ForeachStatementTest', true);

        $method = $classGenerator->addPublicMethod("sayForeach");
        $method->addParameter("array", "list");
        $method->setReturnType("string", false);

        $method->addCodeLine('$string = "";');
        $method->addForeachStart('$list as $item');
        $method->addCodeLine('$string .= $item;');
        $method->addForeachEnd();
        $method->addCodeLine('return $string;');

        $classGenerator->writeToPSR0(self::BASE_DIR);

        $foreachObject = $this->getReflectInstance($classGenerator);

        $this->assertSame("abc", $foreachObject->sayForeach([
            'a',
            'b',
            'c'
        ]));
        $this->assertSame("abc", $foreachObject->sayForeach(['abc']));
        $this->assertSame("", $foreachObject->sayForeach([]));

    }

    public function testSwitchStatement()
    {
        $classGenerator = new ClassGenerator('Tests\SwitchStatementTest', true);

        $method = $classGenerator->addPublicMethod("saySwitch");
        $method->addParameter("string", "value");
        $method->setReturnType("string", false);

        $method->addSwitch('$value');

        $method->addSwitchCase('"a"');
        $method->addCodeLine('return "a";');
        $method->addSwitchBreak();

        $method->addSwitchCase('"b"');
        $method->addCodeLine('return "b";');
        $method->addSwitchReturnBreak();

        $method->addSwitchDefault();
        $method->addCodeLine('return "c";');
        $method->addSwitchBreak();

        $method->addSwitchEnd();

        $classGenerator->writeToPSR0(self::BASE_DIR);

        $switchObject = $this->getReflectInstance($classGenerator);

        $this->assertSame("a", $switchObject->saySwitch('a'));
        $this->assertSame("b", $switchObject->saySwitch('b'));
        $this->assertSame("c", $switchObject->saySwitch('c'));
        $this->assertSame("c", $switchObject->saySwitch('fff'));

    }


    public function testTryCatchStatement() {
        $classGenerator = new ClassGenerator('Tests\TryCatchTest', true);

        $method = $classGenerator->addPublicMethod("sayTry");
        $method->addParameter("string", "value");
        $method->setReturnType("bool", false);

        $method->addTry();
        $method->addCodeLine('throw new CustomException();');

        $method->addCatchStart('NitriaTest\End2End\Asset\CustomException', 'e1');
        $method->addCodeLine('return true;');
        $method->addCatchStart('\Exception', 'e2');
        $method->addCodeLine('return false;');
        $method->addCatchEnd();
        $method->addCodeLine('return false;');

        $classGenerator->writeToPSR0(self::BASE_DIR);


        $tryCatch = $this->getReflectInstance($classGenerator);

        $this->assertTrue($tryCatch->sayTry(""));

    }

}