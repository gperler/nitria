<?php

declare(strict_types=1);

namespace Nitria;

class Method
{

    /**
     * @var ClassGenerator
     */
    protected ClassGenerator $classGenerator;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $modifier;

    /**
     * @var bool
     */
    protected bool $static;

    /**
     * @var bool
     */
    protected bool $isConstructor;

    /**
     * @var string
     */
    protected string $indent;

    /**
     * @var MethodReturnType
     */
    protected MethodReturnType $methodReturnType;

    /**
     * @var MethodParameter[]
     */
    protected array $methodParameterList;

    /**
     * @var CodeWriter
     */
    protected CodeWriter $methodSignature;

    /**
     * @var CodeWriter
     */
    protected CodeWriter $methodBody;

    /**
     * @var int
     */
    protected int $currentIndent;

    /**
     * @var string[]
     */
    protected array $docBlockComment;

    /**
     * @var Type[]
     */
    protected array $exceptionList;


    /**
     * Method constructor.
     *
     * @param ClassGenerator $classGenerator
     * @param string $name
     * @param string $modifier
     * @param bool $static
     */
    public function __construct(ClassGenerator $classGenerator, string $name, string $modifier, bool $static)
    {
        $this->classGenerator = $classGenerator;
        $this->name = $name;
        $this->modifier = $modifier;
        $this->static = $static;
        $this->isConstructor = $name === '__construct';

        $this->methodReturnType = new MethodReturnType();
        $this->methodReturnType->setIsConstructor($this->isConstructor);
        $this->methodParameterList = [];
        $this->currentIndent = 2;
        $this->exceptionList = [];
        $this->docBlockComment = [];

        $this->indent = $classGenerator->getIndent();
        $this->methodSignature = new CodeWriter($this->indent);
        $this->methodBody = new CodeWriter($this->indent);
    }


    /**
     * @param string $className
     */
    public function addUsedClassName(string $className): void
    {
        $this->classGenerator->addUsedClassName($className);
    }


    /**
     * @param string|null $typeName
     * @param string $name
     * @param string|null $defaultValue
     * @param string|null $docComment
     * @param bool $allowsNull
     */
    public function addParameter(?string $typeName, string $name, string $defaultValue = null, string $docComment = null, bool $allowsNull = false): void
    {
        $type = new Type($typeName, $this->classGenerator->getUseStatementList());
        $this->classGenerator->addUseClassForType($type);
        $this->methodParameterList[] = new MethodParameter($type, $name, $defaultValue, $docComment, $allowsNull);
    }


    /**
     * @param string|null $typeName
     * @param bool $nullAble
     */
    public function setReturnType(string $typeName = null, bool $nullAble = true): void
    {
        $type = new Type($typeName, $this->classGenerator->getUseStatementList());
        $this->classGenerator->addUseClassForType($type);

        $this->methodReturnType = new MethodReturnType($type, $nullAble);
    }


    /**
     * @param string $typeName
     */
    public function addException(string $typeName): void
    {
        $type = new Type($typeName, $this->classGenerator->getUseStatementList());
        $this->classGenerator->addUseClassForType($type);
        $this->exceptionList[] = $type;
    }


    /**
     * @return bool
     */
    public function hasReturnType(): bool
    {
        return $this->methodReturnType->hasReturnType();
    }


    /**
     * @return bool
     */
    public function isStatic(): bool
    {
        return $this->static;
    }


    /**
     * @return bool
     */
    public function isIsConstructor(): bool
    {
        return $this->isConstructor;
    }


    /**
     * @param string $content
     * @param int $lineBreaks
     */
    public function addCodeLine(string $content, int $lineBreaks = 1): void
    {
        $this->methodBody->addCodeLine($content, $this->currentIndent, $lineBreaks);
    }


    /**
     * @return void
     */
    public function addNewLine(): void
    {
        $this->methodBody->addCodeLine('', 0, 1);
    }


    /**
     * @return void
     */
    public function incrementIndent(): void
    {
        $this->currentIndent++;
    }


    /**
     * @return void
     */
    public function decrementIndent(): void
    {
        $this->currentIndent--;
    }


    /**
     * @return void
     */
    public function addTry(): void
    {
        $this->addCodeLine('try {');
        $this->incrementIndent();
    }


    /**
     * @param string $className
     * @param string $variableName
     *
     * @return void
     */
    public function addCatchStart(string $className, string $variableName): void
    {
        $type = new Type($className, $this->classGenerator->getUseStatementList());
        $this->classGenerator->addUseClassForType($type);

        $this->decrementIndent();
        $this->addCodeLine('} catch(' . $type->getCodeType() . ' $' . $variableName . ') {');
        $this->incrementIndent();
    }


    /**
     * @return void
     */
    public function addCatchEnd(): void
    {
        $this->decrementIndent();
        $this->addCodeLine('}');
    }


    /**
     * @param string $condition
     */
    public function addIfStart(string $condition): void
    {
        $this->addCodeLine("if ($condition) {");
        $this->currentIndent++;
    }


    /**
     *
     */
    public function addIfElse(): void
    {
        $this->currentIndent--;
        $this->addCodeLine("} else {");
        $this->currentIndent++;
    }


    /**
     * @param string $condition
     */
    public function addIfElseIf(string $condition): void
    {
        $this->currentIndent--;
        $this->addCodeLine("} else if ($condition){");
        $this->currentIndent++;
    }


    /**
     *
     */
    public function addIfEnd(): void
    {
        $this->currentIndent--;
        $this->addCodeLine("}");
    }


    /**
     * @param string $condition
     */
    public function addWhileStart(string $condition): void
    {
        $this->addCodeLine("while ($condition) {", 1);
        $this->currentIndent++;
    }


    /**
     *
     */
    public function addWhileEnd(): void
    {
        $this->currentIndent--;
        $this->addCodeLine("}");
    }


    /**
     * @param string $condition
     */
    public function addForeachStart(string $condition): void
    {
        $this->addCodeLine("foreach ($condition) {", 1);
        $this->currentIndent++;
    }


    /**
     *
     */
    public function addForeachEnd(): void
    {
        $this->currentIndent--;
        $this->addCodeLine("}");
    }


    /**
     * @param string $variableName
     */
    public function addSwitch(string $variableName): void
    {
        $this->addCodeLine("switch ($variableName) {");
        $this->currentIndent++;
    }


    /**
     * @param string $value
     */
    public function addSwitchCase(string $value): void
    {
        $this->addCodeLine("case $value:");
        $this->currentIndent++;
    }


    /**
     * @return void
     */
    public function addSwitchBreak(): void
    {
        $this->addCodeLine("break;");
        $this->currentIndent--;
    }


    /**
     * @return void
     */
    public function addSwitchReturnBreak(): void
    {
        $this->currentIndent--;
    }


    /**
     * @return void
     */
    public function addSwitchDefault(): void
    {
        $this->addCodeLine("default:");
        $this->currentIndent++;
    }


    /**
     * @return void
     */
    public function addSwitchEnd(): void
    {
        $this->currentIndent--;
        $this->addCodeLine("}");
    }


    /**
     * @return string[]
     */
    public function getCodeLineList(): array
    {
        $this->generateMethod();
        $signatureLineList = $this->methodSignature->getCodeLineList();
        $bodyCodeLineList = $this->methodBody->getCodeLineList();
        return array_merge($signatureLineList, $bodyCodeLineList);
    }


    /**
     *
     */
    protected function generateMethod(): void
    {
        $this->generateDocBlock();
        $this->generateMethodDefinition();
        $this->methodBody->addCodeLine("}", 1);
    }


    /**
     */
    protected function generateDocBlock(): void
    {
        $phpDocBlock = $this->docBlockComment;
        foreach ($this->methodParameterList as $parameter) {
            $phpDocBlock[] = $parameter->getPHPDocLine();
        }
        $phpDocBlock[] = '';
        if (!$this->isConstructor) {
            $phpDocBlock[] = $this->methodReturnType->getDocBlockReturnType();
        }
        foreach ($this->exceptionList as $exception) {
            $phpDocBlock[] = "@throws " . $exception->getDocBlockType();
        }
        $this->methodSignature->addEmptyLine();
        $this->methodSignature->addDocBlock($phpDocBlock, 1);
    }


    /**
     *
     */
    protected function generateMethodDefinition(): void
    {
        $static = $this->static ? " static " : " ";
        $definition = $this->modifier . $static . "function " . $this->name;
        $signature = $this->createSignature();
        $returnType = $this->methodReturnType->getSignatureReturnType();

        $this->methodSignature->addCodeLine($definition . "($signature)$returnType", 1);
        $this->methodSignature->addCodeLine("{", 1);
    }


    /**
     *
     */
    protected function createSignature(): string
    {
        $parameterList = [];
        foreach ($this->methodParameterList as $parameter) {
            $parameterList[] = $parameter->getSignaturePart();
        }
        return implode(", ", $parameterList);
    }


    /**
     * @param string $docBlockComment
     */
    public function setDocBlockComment(string $docBlockComment): void
    {
        $this->docBlockComment = [$docBlockComment];
    }


    /**
     * @param string $docBlockComment
     */
    public function addDocBlockComment(string $docBlockComment)
    {
        $this->docBlockComment[] = $docBlockComment;
    }


    /**
     * @param string $inlineComment
     */
    public function addInlineComment(string $inlineComment)
    {
        $this->addCodeLine("// " . $inlineComment);
    }

}