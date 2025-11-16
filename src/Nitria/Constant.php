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
     * @var ScalarType|null
     */
    protected ?ScalarType $type;

    /**
     * Constant constructor.
     *
     * @param string $name
     * @param string $value
     * @param string $indent
     * @param ScalarType|null $type
     */
    public function __construct(string $name, string $value, string $indent, ScalarType $type = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        $this->codeWriter = new CodeWriter($indent);
    }


    /**
     * @return string[]
     */
    public function getCodeLineList(): array
    {
        $type = $this->type !== null ? $this->type->value . ' ' : '';
        $constDefinition = "const " . $type . $this->name . " = " . $this->value . ";";
        $this->codeWriter->addEmptyLine();
        $this->codeWriter->addCodeLine($constDefinition, 1, 1);
        return $this->codeWriter->getCodeLineList();
    }
}