<?php

declare(strict_types=1);

namespace Nitria;

class CodeWriter
{

    /**
     * @var string
     */
    protected string $indent;

    /**
     * @var string[]
     */
    protected array $codeLineList;

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
    public function getCodeLineList(): array
    {
        return $this->codeLineList;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return implode(PHP_EOL, $this->codeLineList);
    }

    /**
     * @param string $content
     * @param int $indentCount
     * @param int $lineBreakCount
     */
    public function addCodeLine(string $content, int $indentCount, int $lineBreakCount = 1): void
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
    public function addCodeLineList(array $codeLineList): void
    {
        $this->codeLineList = array_merge($this->codeLineList, $codeLineList);
    }

    /**
     *
     */
    public function addEmptyLine(): void
    {
        $this->codeLineList[] = "";
    }

    /**
     *
     */
    public function addPHPDeclaration(): void
    {
        $this->addCodeLine('<?php', 0, 2);
    }

    /**
     * @param bool $strict
     */
    public function addStrictStatement(bool $strict): void
    {
        if (!$strict) {
            return;
        }
        $this->addCodeLine('declare(strict_types=1);', 0, 2);
    }


    /**
     * @param string|null $namespace
     */
    public function addNamespace(string $namespace = null): void
    {
        if ($namespace === null) {
            return;
        }
        $this->addCodeLine('namespace ' . $namespace . ';', 0, 2);
    }

    /**
     * @param ClassName[] $classNameList
     */
    public function addUseStatementList(array $classNameList): void
    {
        $useStatementList = [];
        foreach ($classNameList as $className) {
            if (!$className->needsUseStatement()) {
                continue;
            }
            $useStatementList[] = $className->getUseStatment();
        }

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
    public function addDocBlock(array $docElementList, int $indentCount): void
    {
        $this->addCodeLine("/**", $indentCount);
        foreach ($docElementList as $lineItem) {
            $this->addCodeLine(" * " . $lineItem, $indentCount);
        }
        $this->addCodeLine(" */", $indentCount);
    }


    /**
     * @param string $classShortName
     * @param string|null $extends
     * @param string[]|null $implementsList
     * @param ClassType|null $classType
     */
    public function addClassStart(string $classShortName, ?string $extends, ?array $implementsList, ?ClassType $classType, ?ScalarType $enumType): void
    {
        $line = $classType->value . ' ' . trim($classShortName, "\\");

        if ($enumType) {
            $line .= ': ' . $enumType->value;
        }

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
     * @param string $caseName
     * @param string $caseValue
     * @return void
     */
    public function addEnumCase(string $caseName, string $caseValue): void
    {
        $this->addCodeLine(
            sprintf(
                'case %s = %s;'
                , $caseName,
                $caseValue
            ),
            1
        );
    }


    /**
     *
     */
    public function addClassEnd(): void
    {
        $this->addCodeLine("}", 0);
    }

}