<?php

declare(strict_types=1);

namespace Nitria;

class Property
{

    /**
     * @var string
     */
    protected string $modifier;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var Type
     */
    protected Type $type;

    /**
     * @var bool
     */
    protected bool $static;

    /**
     * @var string|null
     */
    protected ?string $value;

    /**
     * @var string|null
     */
    protected ?string $docComment;

    /**
     * @var CodeWriter
     */
    protected CodeWriter $codeWriter;


    /**
     * Property constructor.
     *
     * @param string $modifier
     * @param string $name
     * @param Type $type
     * @param bool $static
     * @param string $indent
     * @param string|null $value
     * @param string|null $docComment
     */
    public function __construct(string $modifier, string $name, Type $type, bool $static, string $indent, string $value = null, string $docComment = null)
    {
        $this->modifier = $modifier;
        $this->name = $name;
        $this->type = $type;
        $this->static = $static;
        $this->value = $value;
        $this->docComment = $docComment;
        $this->codeWriter = new CodeWriter($indent);
    }


    /**
     * @return boolean
     */
    public function isStatic(): bool
    {
        return $this->static;
    }


    /**
     * @return string[]
     */
    public function getCodeLineList(): array
    {
        $this->codeWriter->addEmptyLine();

        $docBlock = ($this->docComment !== null) ? [$this->docComment] : [];
        $docBlock[] = "@var " . $this->type->getDocBlockType() . '|null';

        $this->codeWriter->addDocBlock($docBlock, 1);

        $static = $this->getStatic();
        $codeType = $this->getCodeType();

        $memberDefinition = $this->modifier . $static . $codeType . ' $' . $this->name;

        if ($this->value !== null) {
            $memberDefinition .= " = " . $this->value . ";";
        } else {
            $memberDefinition .= ";";
        }

        $this->codeWriter->addCodeLine($memberDefinition, 1);

        return $this->codeWriter->getCodeLineList();
    }


    /**
     * @return string
     */
    private function getStatic(): string
    {
        return $this->static ? ' static' : '';
    }


    /**
     * @return string
     */
    private function getCodeType(): string
    {
        $codeType = $this->type->getCodeType();
        if (!$codeType) {
            return ' ';
        }
        return ' ?' . $codeType;
    }

}