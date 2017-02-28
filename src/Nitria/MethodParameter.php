<?php

declare(strict_types = 1);

namespace Nitria;

/**
 * @author Gregor Müller
 */
class MethodParameter
{

    /**
     * @var Type
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $defaultValue;

    /**
     * @var string
     */
    protected $docComment;

    /**
     * MethodParameter constructor.
     *
     * @param Type $type
     * @param string $name
     * @param string|null $defaultValue
     * @param string $docComment
     */
    public function __construct(Type $type, string $name, string $defaultValue = null, $docComment = null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->defaultValue = $defaultValue;
        $this->docComment = $docComment;
    }

    /**
     * @return string
     */
    public function getPHPDocLine()
    {
        $docBlockType = ($this->defaultValue === 'null') ? $this->type->getDocBlockType() . '|null' : $this->type->getDocBlockType();

        $docBlockLine = '@param ' . $docBlockType . ' $' . $this->name;
        if ($this->docComment !== null) {
            $docBlockLine .= ' ' . $this->docComment;
        }
        return $docBlockLine;
    }

    /**
     * @return string
     */
    public function getSignaturePart()
    {
        $codeType = $this->type->getCodeType();
        if ($codeType === null) {
            return '$' . $this->name;
        }
        $optional = ($this->defaultValue !== null) ? ' = ' . $this->defaultValue : '';
        return $codeType . ' $' . $this->name . $optional;
    }
}