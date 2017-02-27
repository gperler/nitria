<?php

declare(strict_types = 1);

namespace Nitria;

class CodeWriter
{

    /**
     * @var string
     */
    protected $indent;

    /**
     * @var string[]
     */
    protected $codeLineList;

    /**
     * IndentWriter constructor.
     *
     * @param string $indent
     */
    public function __construct(string $indent)
    {
        $this->indent = $indent;
        $this->codeLineList = [];
    }

    /**
     * @return string[]
     */
    public function getCodeLineList() : array
    {
        return $this->codeLineList;
    }

    /**
     * @return string
     */
    public function getCode() : string
    {
        return implode(PHP_EOL, $this->codeLineList);
    }

    /**
     * @param string $content
     * @param int $indentCount
     * @param int $lineBreakCount
     */
    public function addCodeLine(string $content, int $indentCount, int $lineBreakCount = 1)
    {
        $codeLine = '';
        for ($i = 0; $i < $indentCount; $i++) {
            $codeLine .= $this->indent;
        }
        $this->codeLineList[] = $codeLine . $content;
        for ($i = 1; $i < $lineBreakCount; $i++) {
            $this->codeLineList[] = "";
        }
    }

    /**
     * @param array $codeLineList
     */
    public function addCodeLineList(array $codeLineList)
    {
        $this->codeLineList = array_merge($this->codeLineList, $codeLineList);
    }

    /**
     *
     */
    public function addEmptyLine()
    {
        $this->codeLineList[] = "";
    }

    /**
     *
     */
    public function addPHPDeclaration()
    {
        $this->addCodeLine('<?php', 0, 2);
    }

    /**
     * @param bool $strict
     */
    public function addStrictStatement(bool $strict)
    {
        if (!$strict) {
            return;
        }
        $this->addCodeLine('declare(strict_types = 1);', 0, 2);
    }

    /**
     * @param string $namespace
     */
    public function addNamespace(string $namespace = null)
    {
        if ($namespace === null) {
            return;
        }
        $this->addCodeLine('namespace ' . $namespace . ';', 0, 2);
    }

    /**
     * @param string[] $useStatementList
     */
    public function addUseStatementList(array $useStatementList)
    {
        $useStatementList = array_unique($useStatementList);
        sort($useStatementList);
        foreach ($useStatementList as $useStatement) {
            $this->addCodeLine('use ' . $useStatement . ';', 0, 1);
        }
        if (sizeof($useStatementList) !== 0) {
            $this->addEmptyLine();
        }
    }

    /**
     * @param array $docElementList
     * @param int $indentCount
     */
    public function addDocBlock(array $docElementList, int $indentCount)
    {
        $this->addCodeLine("/**", $indentCount);
        foreach ($docElementList as $lineItem) {
            $this->addCodeLine(" * " . $lineItem, $indentCount);
        }
        $this->addCodeLine(" */", $indentCount);
    }

    /**
     * @param string $classShortName
     * @param string $extends
     * @param string[] $implementsList
     */
    public function addClassStart(string $classShortName, string $extends = null, array $implementsList = null)
    {
        $line = 'class ' . trim($classShortName, "\\");

        if ($extends !== null) {
            $line .= ' extends ' . $extends;
        }

        if ($implementsList !== null && sizeof($implementsList) !== 0) {
            $line .= ' implements ' . implode(", ", $implementsList);
        }

        $this->addCodeLine($line, 0);
        $this->addCodeLine("{", 0, 1);
    }

    /**
     *
     */
    public function addClassEnd()
    {
        $this->addCodeLine("}", 0);
    }

}