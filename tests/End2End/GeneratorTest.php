<?php

declare(strict_types = 1);

namespace NitriaTest\End2End;

use Nitria\ClassGenerator;

class GeneratorTest extends End2EndTest
{

    const OTHER_CLASS_1 = 'NitriaTest\End2End\Asset\SomeClass';

    const INTERFACE_NAME_1 = 'NitriaTest\End2End\Asset\SomeInterface';

    const INTERFACE_NAME_2 = 'NitriaTest\End2End\Asset\SomeOtherInterface';

    /**
     *
     */
    public function testClassGeneration()
    {
        $className = 'Tests\ClassGenerationTest';

        $classGenerator = new ClassGenerator($className, true);
        $classGenerator->writeToPSR0(self::BASE_DIR);

        $reflectClass = $this->getReflectClass($classGenerator);
        $this->assertSame($className, $reflectClass->getName());
    }

    public function testSameNamespaceImport()
    {
        $className = 'SameNamespace\ClassGenerationTest';
        $classGenerator = new ClassGenerator($className, true);
        $classGenerator->writeToPSR0(self::BASE_DIR);

        $classGenerator = new ClassGenerator('SameNamespace\OtherClass', true);
        $classGenerator->addUsedClassName($className);
        $classGenerator->writeToPSR0(self::BASE_DIR);
    }

    public function testAsImport()
    {
        $className = 'Tests\UseAsClass';

        $classGenerator = new ClassGenerator($className, true);
        $classGenerator->addUsedClassName('Nitria\StringUtil', 'MyStringUtil');
        $classGenerator->addUsedClassName('Nitria\Constant', 'MyConstant');
        $classGenerator->addUsedClassName('Nitria\Property', 'MyProperty');

        $classGenerator->addProtectedProperty("myProp", "MyStringUtil");

        $method = $classGenerator->addMethod("testMethod");
        $method->addParameter("MyConstant", "paramName");
        $method->setReturnType("MyProperty", false);

        $classGenerator->writeToPSR0(self::BASE_DIR);

        $classContent = file_get_contents(self::BASE_DIR . DIRECTORY_SEPARATOR . $classGenerator->getPSR0File());
        $this->assertTrue(strpos($classContent, 'Nitria\StringUtil as MyStringUtil') !== false);
        $this->assertTrue(strpos($classContent, 'Nitria\Constant as MyConstant') !== false);
        $this->assertTrue(strpos($classContent, 'Nitria\Property as MyProperty') !== false);

        $reflection = $this->getReflectClass($classGenerator);

        $property = $reflection->getProperty("myProp");
        $this->assertNotNull($property);
        $this->assertTrue(strpos($property->getDocComment(), '@var MyStringUtil') !== false);

        $method = $reflection->getMethod("testMethod");
        $this->assertNotNull($method);
        $this->assertTrue(strpos($method->getDocComment(), '@param MyConstant $paramName') !== false);
    }

    /**
     *
     */
    public function testExtends()
    {

        $classGenerator = new ClassGenerator("Tests\\ExtendsTest", true);
        $classGenerator->setExtends(self::OTHER_CLASS_1);
        $classGenerator->writeToPSR0(self::BASE_DIR);

        $reflectClass = $this->getReflectClass($classGenerator);
        $this->assertTrue($reflectClass->isSubclassOf(self::OTHER_CLASS_1));
    }

    /**
     *
     */
    public function testImplements()
    {
        $classGenerator = new ClassGenerator('Tests\ImplementsTest', true);
        $classGenerator->addImplements(self::INTERFACE_NAME_1);
        $classGenerator->addImplements(self::INTERFACE_NAME_2);
        $classGenerator->writeToPSR0(self::BASE_DIR);

        $reflectClass = $this->getReflectClass($classGenerator);
        $this->assertTrue($reflectClass->implementsInterface(self::INTERFACE_NAME_1));
        $this->assertTrue($reflectClass->implementsInterface(self::INTERFACE_NAME_2));
    }

    /**
     *
     */
    public function testConstant()
    {
        $classGenerator = new ClassGenerator('Tests\ConstantTest', true);
        $classGenerator->addConstant("CONSTANT_STRING", '"hello"');
        $classGenerator->addConstant("CONSTANT_INT", '2');
        $classGenerator->addConstant("CONSTANT_ARRAY", '[1,2,3]');
        $classGenerator->writeToPSR0(self::BASE_DIR);

        $reflectClass = $this->getReflectClass($classGenerator);
        $this->assertSame("hello", $reflectClass->getConstant("CONSTANT_STRING"));
        $this->assertSame(2, $reflectClass->getConstant("CONSTANT_INT"));
        $this->assertSame([
            1,
            2,
            3
        ], $reflectClass->getConstant("CONSTANT_ARRAY"));
    }

    /**
     *
     */
    public function testMember()
    {
        $classGenerator = new ClassGenerator('Tests\MemberTest', true);
        $classGenerator->addPrivateProperty("iAmPrivat", self::INTERFACE_NAME_1);
        $classGenerator->addProtectedProperty("iAmProtected", self::OTHER_CLASS_1);
        $classGenerator->addPublicProperty("iAmPublic", "float");

        $classGenerator->writeToPSR0(self::BASE_DIR);

        $reflectClass = $this->getReflectClass($classGenerator);

        $privateProperty = $reflectClass->getProperty("iAmPrivat");
        $this->assertNotNull($privateProperty);
        $this->assertTrue($privateProperty->isPrivate());

        $protectedPropery = $reflectClass->getProperty("iAmProtected");
        $this->assertNotNull($protectedPropery);
        $this->assertTrue($protectedPropery->isProtected());

        $publicProperty = $reflectClass->getProperty("iAmPublic");
        $this->assertNotNull($publicProperty);
        $this->assertTrue($publicProperty->isPublic());

    }

    /**
     *
     */
    public function testMemberWithValue()
    {
        $classGenerator = new ClassGenerator('Tests\MemberWithValueTest', true);
        $classGenerator->addPrivateProperty("iAmPrivat", "string", '"K&D"');
        $classGenerator->addProtectedProperty("iAmProtected", "array", "[1,2,3]");
        $classGenerator->addPublicProperty("iAmPublic", "float", "19.08");
        $classGenerator->writeToPSR0(self::BASE_DIR);

        $reflectClass = $this->getReflectClass($classGenerator);

        $privateProperty = $reflectClass->getProperty("iAmPrivat");
        $this->assertNotNull($privateProperty);
        $this->assertTrue($privateProperty->isPrivate());

        $protectedPropery = $reflectClass->getProperty("iAmProtected");
        $this->assertNotNull($protectedPropery);
        $this->assertTrue($protectedPropery->isProtected());

        $publicProperty = $reflectClass->getProperty("iAmPublic");
        $this->assertNotNull($publicProperty);
        $this->assertTrue($publicProperty->isPublic());
    }

    /**
     *
     */
    public function testStaticMember()
    {
        $classGenerator = new ClassGenerator('Tests\StaticMemberTest', true);
        $classGenerator->addUsedClassName("\\DateTime");
        $classGenerator->addPrivateStaticProperty("iAmPrivat", self::INTERFACE_NAME_1);
        $classGenerator->addProtectedStaticProperty("iAmProtected", self::OTHER_CLASS_1);
        $classGenerator->addPublicStaticProperty("iAmPublic", "float");
        $classGenerator->addPublicStaticProperty("iAmPublicToo", "\\DateTime");

        $classGenerator->writeToPSR0(self::BASE_DIR);

        $reflectClass = $this->getReflectClass($classGenerator);
        $staticProperties = $reflectClass->getStaticProperties();
        $this->assertSame(4, sizeof($staticProperties));
        $this->assertTrue(array_key_exists("iAmPrivat", $staticProperties));
        $this->assertTrue(array_key_exists("iAmProtected", $staticProperties));
        $this->assertTrue(array_key_exists("iAmPublic", $staticProperties));
        $this->assertTrue(array_key_exists("iAmPublicToo", $staticProperties));

    }

    /**
     *
     */
    public function testStaticMemberWithValue()
    {
        $classGenerator = new ClassGenerator('Tests\StaticMemberWithValueTest', true);
        $classGenerator->addPrivateStaticProperty("iAmPrivat", "string", '"K&D"');
        $classGenerator->addProtectedStaticProperty("iAmProtected", "array", "[1,2,3]");
        $classGenerator->addPublicStaticProperty("iAmPublic", "float", "23.122");
        $classGenerator->writeToPSR0(self::BASE_DIR);

        $reflectClass = $this->getReflectClass($classGenerator);
        $staticProperties = $reflectClass->getStaticProperties();
        $this->assertSame(3, sizeof($staticProperties));
        $this->assertTrue(array_key_exists("iAmPrivat", $staticProperties));
        $this->assertTrue(array_key_exists("iAmProtected", $staticProperties));
        $this->assertTrue(array_key_exists("iAmPublic", $staticProperties));

        $this->assertSame(23.122, $reflectClass->getStaticPropertyValue("iAmPublic"));
    }

    /**
     *
     */
    public function testMethod()
    {
        $classGenerator = new ClassGenerator('Tests\MethodTest', true);
        $classGenerator->addPrivateMethod("iAmPrivate");
        $classGenerator->addProtectedMethod("iAmProtected");
        $classGenerator->addPublicMethod("iAmPublic");

        $classGenerator->addConstructor();

        $classGenerator->writeToPSR0(self::BASE_DIR);

        $reflectClass = $this->getReflectClass($classGenerator);

        $privateMethod = $reflectClass->getMethod("iAmPrivate");
        $this->assertTrue($privateMethod->isPrivate());

        $protectedMethod = $reflectClass->getMethod("iAmProtected");
        $this->assertTrue($protectedMethod->isProtected());

        $publicMethod = $reflectClass->getMethod("iAmPublic");
        $this->assertTrue($publicMethod->isPublic());

        $constructor = $reflectClass->getConstructor();
        $this->assertTrue($constructor->isPublic());

    }

    /**
     *
     */
    public function testStaticMethod()
    {
        $classGenerator = new ClassGenerator('StaticMethodTest', false);
        $classGenerator->addPrivateStaticMethod("iAmPrivate");
        $classGenerator->addProtectedStaticMethod("iAmProtected");
        $classGenerator->addPublicStaticMethod("iAmPublic");

        $classGenerator->addUsedClassName("\\DateTime");
        $classGenerator->writeToPSR0(self::BASE_DIR);

        $reflectClass = $this->getReflectClass($classGenerator);

        $privateMethod = $reflectClass->getMethod("iAmPrivate");
        $this->assertTrue($privateMethod->isPrivate());
        $this->assertTrue($privateMethod->isStatic());

        $protectedMethod = $reflectClass->getMethod("iAmProtected");
        $this->assertTrue($protectedMethod->isProtected());
        $this->assertTrue($protectedMethod->isStatic());

        $publicMethod = $reflectClass->getMethod("iAmPublic");
        $this->assertTrue($publicMethod->isPublic());
        $this->assertTrue($publicMethod->isStatic());
    }

    /**
     *
     */
    public function testMethodWithSignature()
    {
        $classGenerator = new ClassGenerator('Tests\MethodSignatureTest', true);

        $method = $classGenerator->addPublicMethod("iHaveASignature");
        $method->addParameter("string", "stringValue", '"default"');
        $method->addParameter(self::OTHER_CLASS_1, "classValue", 'null');
        $method->setReturnType("string", false);
        $method->addCodeLine('return $stringValue;');

        $classGenerator->writeToPSR0(self::BASE_DIR);

        $reflectClass = $this->getReflectClass($classGenerator);

        $reflectMethod = $reflectClass->getMethod("iHaveASignature");
        $parameterList = $reflectMethod->getParameters();

        $this->assertSame(2, sizeof($parameterList));
        $this->assertFalse($parameterList[0]->allowsNull());
        $this->assertSame("default", $parameterList[0]->getDefaultValue());
        $this->assertSame(self::OTHER_CLASS_1, $parameterList[1]->getClass()->getName());
        $this->assertTrue($parameterList[1]->allowsNull());

    }

    /**
     *
     */
    public function testMethodReturnType()
    {
        $classGenerator = new ClassGenerator('Tests\MethodReturnTypeTest', true);

        $method = $classGenerator->addPublicMethod("notNullableReturnType");
        $method->setReturnType(self::OTHER_CLASS_1 . '[]', false);
        $method->addCodeLine('return [];');

        $method = $classGenerator->addPublicMethod("nullableReturnType");
        $method->setReturnType(self::OTHER_CLASS_1 . '[]', true);
        $method->addCodeLine('return [];');

        $method = $classGenerator->addPublicMethod("classReturnType");
        $method->setReturnType(self::OTHER_CLASS_1, false);
        $method->addCodeLine('return new SomeClass();');

        $method = $classGenerator->addPublicMethod("classReturnTypeNullable");
        $method->setReturnType(self::OTHER_CLASS_1, true);
        $method->addCodeLine('return new SomeClass();');

        $method = $classGenerator->addPublicMethod("notNullableReturnTypeSimple");
        $method->setReturnType('int[]', false);
        $method->addCodeLine('return [];');

        $method = $classGenerator->addPublicMethod("nullableReturnTypeSimple");
        $method->setReturnType('int[]', true);
        $method->addCodeLine('return [];');

        $method = $classGenerator->addPublicMethod("simpleReturnType");
        $method->setReturnType("int", false);
        $method->addCodeLine('return 19;');

        $method = $classGenerator->addPublicMethod("simpleReturnTypeNullable");
        $method->setReturnType("int", true);
        $method->addCodeLine('return 7;');
        $this->assertTrue($method->hasReturnType());

        $method = $classGenerator->addPublicMethod("defaultNamespaceReturnType");
        $method->setReturnType("\\PHPUnit_Framework_TestCase", true);
        $method->addCodeLine('return null;');

        $method = $classGenerator->addPublicMethod("mixedType");
        $method->setReturnType(null, false);
        $method->addParameter(null, "mixed");
        $method->addCodeLine('return [];');
        $this->assertFalse($method->hasReturnType());

        $classGenerator->writeToPSR0(self::BASE_DIR);
    }

    /**
     *
     */
    public function testDocComment()
    {

        $classGenerator = new ClassGenerator('Tests\CommentTest', true);

        $classGenerator->addPrivateProperty("comment", "string", null, "commented member");

        $method = $classGenerator->addPublicMethod("commentedMethod");
        $method->setDocBlockComment("commented method");
        $method->setReturnType(self::OTHER_CLASS_1 . '[]', false);
        $method->addInlineComment("this is really important");
        $method->addCodeLine('return [];');
        $method->addException('\Exception');
        $method->addException('Codeception\Exception\TestRuntimeException');
        $classGenerator->writeToPSR0(self::BASE_DIR);

        $reflection = $this->getReflectClass($classGenerator);

        $property = $reflection->getProperty("comment");
        $this->assertNotNull($property);
        $this->assertTrue(strpos($property->getDocComment(), "commented member") !== false);

        $method = $reflection->getMethod("commentedMethod");
        $this->assertNotNull($method);
        $this->assertTrue(strpos($method->getDocComment(), "commented method") !== false);
    }

}