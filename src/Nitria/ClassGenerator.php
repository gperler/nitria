<?php

declare(strict_types=1);

namespace Nitria;

use Civis\Common\File;

class ClassGenerator
{

    const BACKSLASH = "\\";

    const PHP_SUFFIX = ".php";

    /**
     * @var string
     */
    private string $indent;

    /**
     * @var ClassName
     */
    private ClassName $className;

    /**
     * @var ClassType
     */
    private ClassType $classType;


    /**
     * @var ScalarType|null
     */
    private ?ScalarType $enumType;

    /**
     * @var Constant[]
     */
    private array $constantList;

    /**
     * @var Property[]
     */
    private array $propertyList;

    /**
     * @var Method[]
     */
    private array $methodList;

    /**
     * @var ClassName[]
     */
    private array $usedClassNameList;

    /**
     * @var string|null
     */
    private ?string $extendsClassShortName;

    /**
     * @var string[]
     */
    private array $implementClassNameList;

    /**
     * @var bool
     */
    private bool $strictTypes;

    /**
     * @var CodeWriter
     */
    private CodeWriter $codeWriter;

    /**
     * @var string[]
     */
    private array $docBlockComment;


    /**
     * @var array
     */
    private array $enumCaseList;

    /**
     * ClassGenerator constructor.
     *
     * @param string $className
     * @param bool $strictTypes
     * @param string $indent
     */
    public function __construct(string $className, bool $strictTypes = true, string $indent = "    ")
    {
        $this->className = new ClassName($className);
        $this->classType = ClassType::ClassType;
        $this->enumType = null;
        $this->usedClassNameList = [];
        $this->extendsClassShortName = null;
        $this->implementClassNameList = [];
        $this->constantList = [];
        $this->propertyList = [];
        $this->methodList = [];
        $this->docBlockComment = [];
        $this->enumCaseList = [];
        $this->strictTypes = $strictTypes;
        $this->codeWriter = new CodeWriter($indent);
        $this->indent = $indent;
    }

    /**
     * @param ClassType $classType
     * @return void
     */
    public function setClassType(ClassType $classType): void
    {
        $this->classType = $classType;
    }

    /**
     * @param ScalarType|null $enumType
     * @return void
     */
    public function setEnumType(?ScalarType $enumType): void
    {
        $this->enumType = $enumType;
    }


    /**
     * @param string $caseName
     * @param string $caseValue
     * @return void
     */
    public function addEnumCase(string $caseName, string $caseValue): void
    {
        $this->enumCaseList[$caseName] = $caseValue;
    }

    /**
     * @param string $basePath
     */
    public function writeToPSR0(string $basePath): void
    {
        $this->generate();

        $basePath = rtrim($basePath, DIRECTORY_SEPARATOR);
        $targetFileName = $basePath . DIRECTORY_SEPARATOR . $this->getPSR0File();

        $file = new File($targetFileName);
        $file->putContents($this->codeWriter->getCode());
    }


    /**
     * @param string $basePath
     * @param string $psr4Prefix
     */
    public function writeToPSR4(string $basePath, string $psr4Prefix): void
    {
        $this->generate();

        $basePath = rtrim($basePath, DIRECTORY_SEPARATOR);
        $targetFileName = $basePath . DIRECTORY_SEPARATOR . $this->getPSR4File($psr4Prefix);

        $file = new File($targetFileName);
        $file->putContents($this->codeWriter->getCode());
    }


    /**
     * @param string $fileName
     */
    public function writeToFile(string $fileName): void
    {
        $this->generate();
        $file = new File($fileName);
        $file->putContents($this->codeWriter->getCode());
    }


    /**
     *
     */
    private function generate(): void
    {
        $this->codeWriter->addPHPDeclaration();

        $this->codeWriter->addStrictStatement($this->strictTypes);

        $this->codeWriter->addNamespace($this->className->getNamespaceName());

        $this->codeWriter->addUseStatementList($this->usedClassNameList);

        if (!empty($this->docBlockComment)) {
            $this->codeWriter->addDocBlock($this->docBlockComment, 0);
        }

        $this->codeWriter->addClassStart(
            $this->className->getClassShortName(),
            $this->extendsClassShortName,
            $this->implementClassNameList,
            $this->classType,
            $this->enumType,
        );

        foreach ($this->constantList as $constant) {
            $this->codeWriter->addCodeLineList($constant->getCodeLineList());
        }

        $this->generateProperty(true);

        $this->generateMethod(true);

        $this->generateProperty(false);

        $this->generateConstructor();

        $this->generateMethod(false);

        foreach ($this->enumCaseList as $caseName => $caseValue) {
            $this->codeWriter->addEnumCase($caseName, $caseValue);
        }

        $this->codeWriter->addClassEnd();
    }


    /**
     * @param bool $static
     */
    private function generateProperty(bool $static): void
    {
        foreach ($this->propertyList as $member) {
            if ($static !== $member->isStatic()) {
                continue;
            }
            $this->codeWriter->addCodeLineList($member->getCodeLineList());
        }
    }


    /**
     * @param bool $static
     */
    private function generateMethod(bool $static): void
    {
        foreach ($this->methodList as $method) {
            if ($static !== $method->isStatic() || $method->isIsConstructor()) {
                continue;
            }
            $this->codeWriter->addCodeLineList($method->getCodeLineList());
            $this->codeWriter->addEmptyLine();
        }
    }


    /**
     *
     */
    private function generateConstructor(): void
    {
        foreach ($this->methodList as $method) {
            if (!$method->isIsConstructor()) {
                continue;
            }
            $this->codeWriter->addCodeLineList($method->getCodeLineList());
        }
    }


    /**
     * @param string $className
     * @param string|null $as
     */
    public function addUsedClassName(string $className, string $as = null): void
    {
        $class = new ClassName($className, $as);
        $this->addUseClassForClassName($class);
    }


    /**
     * @param Type $type
     */
    public function addUseClassForType(Type $type): void
    {
        if (!$type->needsUseStatement()) {
            return;
        }
        $this->addUseClassForClassName($type->getClassName());
    }


    /**
     * @param ClassName $className
     */
    public function addUseClassForClassName(ClassName $className): void
    {
        if ($className->isNamespaceIdentical($this->className)) {
            return;
        }
        $this->usedClassNameList[] = $className;
    }


    /**
     * @param string $className
     */
    public function setExtends(string $className): void
    {
        $className = new ClassName($className);
        $this->extendsClassShortName = $className->getClassShortName();
        $this->addUseClassForClassName($className);
    }


    /**
     * @param string $interfaceName
     */
    public function addImplements(string $interfaceName): void
    {
        $className = new ClassName($interfaceName);
        $this->addUseClassForClassName($className);
        $this->implementClassNameList[] = $className->getClassShortName();
    }


    /**
     * @param string $name
     * @param string $value
     */
    public function addConstant(string $name, string $value, ScalarType $type = null): void
    {
        $this->constantList[] = new Constant($name, $value, $this->indent, $type);
    }


    /**
     * @param string $name
     * @param string|null $type
     * @param string|null $value
     * @param string|null $docComment
     */
    public function addPublicStaticProperty(string $name, ?string $type, string $value = null, string $docComment = null): void
    {
        $this->addProperty($name, $type, "public", true, $value, $docComment);
    }


    /**
     * @param string $name
     * @param string|null $type
     * @param string|null $value
     * @param string|null $docComment
     */
    public function addProtectedStaticProperty(string $name, ?string $type, string $value = null, string $docComment = null): void
    {
        $this->addProperty($name, $type, "protected", true, $value, $docComment);
    }


    /**
     * @param string $name
     * @param string|null $type
     * @param string|null $value
     * @param string|null $docComment
     */
    public function addPrivateStaticProperty(string $name, ?string $type, string $value = null, string $docComment = null): void
    {
        $this->addProperty($name, $type, "private", true, $value, $docComment);
    }


    /**
     * @param string $name
     * @param string|null $type
     * @param string|null $value
     * @param string|null $docComment
     */
    public function addPublicProperty(string $name, ?string $type, string $value = null, string $docComment = null): void
    {
        $this->addProperty($name, $type, "public", false, $value, $docComment);
    }


    /**
     * @param string $name
     * @param string|null $type
     * @param string|null $value
     * @param string|null $docComment
     */
    public function addProtectedProperty(string $name, ?string $type, string $value = null, string $docComment = null): void
    {
        $this->addProperty($name, $type, "protected", false, $value, $docComment);
    }


    /**
     * @param string $name
     * @param string|null $type
     * @param string|null $value
     * @param string|null $docComment
     */
    public function addPrivateProperty(string $name, ?string $type, string $value = null, string $docComment = null): void
    {
        $this->addProperty($name, $type, "private", false, $value, $docComment);
    }


    /**
     * @param string $name
     * @param string|null $typeName
     * @param string $modifier
     * @param bool $static
     * @param string|null $value
     * @param string|null $docComment
     */
    public function addProperty(string $name, ?string $typeName, string $modifier = "private", bool $static = false, string $value = null, string $docComment = null): void
    {
        $type = new Type($typeName, $this->usedClassNameList);
        $this->addUseClassForType($type);

        $this->propertyList[] = new Property($modifier, $name, $type, $static, $this->indent, $value, $docComment);
    }


    /**
     * @param string $name
     *
     * @return Method
     */
    public function addPublicStaticMethod(string $name): Method
    {
        return $this->addMethod($name, "public", true);
    }


    /**
     * @param string $name
     *
     * @return Method
     */
    public function addProtectedStaticMethod(string $name): Method
    {
        return $this->addMethod($name, "protected", true);
    }


    /**
     * @param string $name
     *
     * @return Method
     */
    public function addPrivateStaticMethod(string $name): Method
    {
        return $this->addMethod($name, "private", true);
    }


    /**
     * @param string $name
     *
     * @return Method
     */
    public function addPublicMethod(string $name): Method
    {
        return $this->addMethod($name);
    }


    /**
     * @param string $name
     *
     * @return Method
     */
    public function addProtectedMethod(string $name): Method
    {
        return $this->addMethod($name, "protected");
    }


    /**
     * @param string $name
     *
     * @return Method
     */
    public function addPrivateMethod(string $name): Method
    {
        return $this->addMethod($name, "private");
    }


    /**
     * @return Method
     */
    public function addConstructor(): Method
    {
        return $this->addMethod("__construct");
    }


    /**
     * @param string $name
     * @param string $modifier
     * @param bool $static
     *
     * @return Method
     */
    public function addMethod(string $name, string $modifier = "public", bool $static = false): Method
    {
        $method = new Method($this, $name, $modifier, $static);
        return $this->addMethodObject($method);
    }


    /**
     * @param Method $method
     *
     * @return Method
     */
    public function addMethodObject(Method $method): Method
    {
        $this->methodList[] = $method;
        return $method;
    }


    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className->getClassName();
    }


    /**
     * @return string
     */
    public function getClassShortName(): string
    {
        return $this->className->getClassShortName();
    }


    /**
     * @return string
     */
    public function getPSR0Path(): string
    {
        if ($this->className->getNamespaceName() === null) {
            return "";
        }
        return str_replace(self::BACKSLASH, DIRECTORY_SEPARATOR, $this->className->getNamespaceName()) . DIRECTORY_SEPARATOR;
    }


    /**
     * @return string
     */
    public function getPSR0File(): string
    {
        return $this->getPSR0Path() . $this->getClassShortName() . self::PHP_SUFFIX;
    }


    /**
     * @param string $psr4Prefix
     *
     * @return string
     */
    public function getPSR4Path(string $psr4Prefix): string
    {
        if ($this->className->getNamespaceName() === null) {
            return "";
        }
        $psr4Prefix = trim($psr4Prefix, self::BACKSLASH);
        $relevantNamespace = str_replace($psr4Prefix, "", $this->className->getNamespaceName());

        return str_replace(self::BACKSLASH, DIRECTORY_SEPARATOR, $relevantNamespace) . DIRECTORY_SEPARATOR;
    }


    /**
     * @param string $psr4Prefix
     *
     * @return string
     */
    public function getPSR4File(string $psr4Prefix): string
    {
        return $this->getPSR4Path($psr4Prefix) . $this->getClassShortName() . self::PHP_SUFFIX;
    }


    /**
     * @return string
     */
    public function getIndent(): string
    {
        return $this->indent;
    }


    /**
     * @return ClassName[]
     */
    public function getUseStatementList(): array
    {
        return $this->usedClassNameList;
    }


    /**
     * @param string $docBlockComment
     */
    public function addDocBlockComment(string $docBlockComment): void
    {
        $this->docBlockComment[] = $docBlockComment;
    }
}