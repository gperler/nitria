<?php

declare(strict_types = 1);

namespace Nitria;

class Method
{

    /**
     * @var ClassGenerator
     */
    protected $classGenerator;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $modifier;

    /**
     * @var bool
     */
    protected $static;

    /**
     * @var bool
     */
    protected $constructor;

    /**
     * @var string
     */
    protected $indent;

    /**
     * @var MethodReturnType
     */
    protected $methodReturnType;

    /**
     * @var MethodParameter[]
     */
    protected $methodParameterList;

    /**
     * @var CodeWriter
     */
    protected $methodSignature;

    /**
     * @var CodeWriter
     */
    protected $methodBody;

    /**
     * @var int
     */
    protected $currentIndent;

    /**
     * @var string
     */
    protected $docBlockComment;

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
        $this->methodReturnType = new MethodReturnType();
        $this->methodParameterList = [];
        $this->currentIndent = 2;
        $this->constructor = $name === '__construct';

        $this->indent = $classGenerator->getIndent();
        $this->methodSignature = new CodeWriter($this->indent);
        $this->methodBody = new CodeWriter($this->indent);
    }

    /**
     * @param string|null $typeName
     * @param string $name
     * @param string|null $defaultValue
     * @param string $docComment
     */
    public function addParameter(string $typeName = null, string $name, string $defaultValue = null, string $docComment = null)
    {
        $type = new Type($typeName);
        $this->classGenerator->addUseClassForType($type);
        $this->methodParameterList[] = new MethodParameter($type, $name, $defaultValue, $docComment);
    }

    /**
     * @param string|null $typeName
     * @param bool $nullAble
     */
    public function setReturnType(string $typeName = null, bool $nullAble = true)
    {
        $type = new Type($typeName);
        $this->classGenerator->addUseClassForType($type);

        $this->methodReturnType = new MethodReturnType($type, $nullAble);
    }

    /**
     * @return bool
     */
    public function hasReturnType() : bool
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
    public function isConstructor(): bool
    {
        return $this->constructor;
    }

    /**
     * @param $content
     * @param int $lineBreaks
     */
    public function addCodeLine(string $content, int $lineBreaks = 1)
    {
        $this->methodBody->addCodeLine($content, $this->currentIndent, $lineBreaks);
    }

    /**
     * @param $condition
     */
    public function addIfStart(string $condition)
    {
        $this->addCodeLine("if ($condition) {");
        $this->currentIndent++;
    }

    /**
     *
     */
    public function addIfElse()
    {
        $this->currentIndent--;
        $this->addCodeLine("} else {");
        $this->currentIndent++;
    }

    /**
     * @param string $condition
     */
    public function addIfElseIf(string $condition)
    {
        $this->currentIndent--;
        $this->addCodeLine("} else if ($condition){");
        $this->currentIndent++;
    }

    /**
     *
     */
    public function addIfEnd()
    {
        $this->currentIndent--;
        $this->addCodeLine("}");
    }

    /**
     * @param string $condition
     */
    public function addWhileStart(string $condition)
    {
        $this->addCodeLine("while ($condition) {", 1);
        $this->currentIndent++;
    }

    /**
     *
     */
    public function addWhileEnd()
    {
        $this->currentIndent--;
        $this->addCodeLine("}");
    }

    /**
     * @param string $condition
     */
    public function addForeachStart(string $condition)
    {
        $this->addCodeLine("foreach ($condition) {", 1);
        $this->currentIndent++;
    }

    /**
     *
     */
    public function addForeachEnd()
    {
        $this->currentIndent--;
        $this->addCodeLine("}");
    }

    /**
     * @param string $variableName
     */
    public function addSwitch(string $variableName)
    {
        $this->addCodeLine("switch ($variableName) {");
        $this->currentIndent++;
    }

    /**
     * @param string $value
     */
    public function addSwitchCase(string $value)
    {
        $this->addCodeLine("case $value:");
        $this->currentIndent++;
    }

    public function addSwitchBreak()
    {
        $this->addCodeLine("break;");
        $this->currentIndent--;
    }

    public function addSwitchReturnBreak()
    {
        $this->currentIndent--;
    }

    public function addSwitchDefault()
    {
        $this->addCodeLine("default:");
        $this->currentIndent++;
    }

    public function addSwitchEnd()
    {
        $this->currentIndent--;
        $this->addCodeLine("}");

    }

    /**
     * @return string[]
     */
    public function getCodeLineList() : array
    {
        $this->generateMethod();
        $signatureLineList = $this->methodSignature->getCodeLineList();
        $bodyCodeLineList = $this->methodBody->getCodeLineList();
        return array_merge($signatureLineList, $bodyCodeLineList);
    }

    /**
     *
     */
    protected function generateMethod()
    {
        $this->generateDocBlock();
        $this->generateMethodDefinition();
        $this->methodBody->addCodeLine("}", 1);
    }

    /**
     *
     */
    protected function generateDocBlock()
    {
        $phpDocBlock = $this->docBlockComment !== null ? [$this->docBlockComment] : [];
        foreach ($this->methodParameterList as $parameter) {
            $phpDocBlock[] = $parameter->getPHPDocLine();
        }
        $phpDocBlock[] = '';
        if (!$this->constructor) {
            $phpDocBlock[] = $this->methodReturnType->getDocBlockReturnType();
        }
        $this->methodSignature->addEmptyLine();
        $this->methodSignature->addDocBlock($phpDocBlock, 1);
    }

    /**
     *
     */
    protected function generateMethodDefinition()
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
    protected function createSignature() : string
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
    public function setDocBlockComment(string $docBlockComment)
    {
        $this->docBlockComment = $docBlockComment;
    }

    /**
     * @param string $inlineComment
     */
    public function addInlineComment(string $inlineComment)
    {
        $this->addCodeLine("// " . $inlineComment);
    }

}