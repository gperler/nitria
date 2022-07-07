<?php

declare(strict_types=1);

namespace Nitria;

/**
 * @author Gregor Müller
 */
class Constant
{

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var string
     */
    protected string $value;

    /**
     * @var CodeWriter
     */
    protected CodeWriter $codeWriter;


    /**
     * Constant constructor.
     *
     * @param string $name
     * @param string $value
     * @param string $indent
     */
    public function __construct(string $name, string $value, string $indent)
    {
        $this->name = $name;
        $this->value = $value;
        $this->codeWriter = new CodeWriter($indent);
    }


    /**
     * @return string[]
     */
    public function getCodeLineList(): array
    {
        $constDefinition = "const " . $this->name . " = " . $this->value . ";";
        $this->codeWriter->addEmptyLine();
        $this->codeWriter->addCodeLine($constDefinition, 1, 1);
        return $this->codeWriter->getCodeLineList();
    }
}