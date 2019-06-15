<?php

declare(strict_types=1);

namespace Nitria;

/**
 * @author Gregor Müller
 */
class MethodParameter
{

    /**
     * @var Type
     */
    private $type;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $defaultValue;

    /**
     * @var string
     */
    private $docComment;

    /**
     * @var bool
     */
    private $allowsNull;

    /**
     * MethodParameter constructor.
     * @param Type $type
     * @param string $name
     * @param string|null $defaultValue
     * @param null $docComment
     * @param bool $allowsNull
     */
    public function __construct(Type $type, string $name, string $defaultValue = null, $docComment = null, $allowsNull = false)
    {
        $this->type = $type;
        $this->name = $name;
        $this->defaultValue = $defaultValue;
        $this->docComment = $docComment;
        $this->allowsNull = $allowsNull;
    }

    /**
     * @return string
     */
    public function getPHPDocLine()
    {
        $docBlockType = ($this->getAllowsNull() || $this->defaultValue === 'null') ? $this->type->getDocBlockType() . '|null' : $this->type->getDocBlockType();

        $docBlockLine = '@param ' . $docBlockType . ' $' . $this->name;
        if ($this->docComment !== null) {
            $docBlockLine .= ' ' . $this->docComment;
        }
        return $docBlockLine;
    }

    /**
     * @return string
     */
    public function getSignaturePart(): string
    {
        $codeType = $this->type->getCodeType();
        if ($codeType === null) {
            return '$' . $this->name;
        }
        if ($this->getAllowsNull() && ($this->defaultValue === null) ) {
            return '?' . $codeType . ' $' . $this->name;
        }

        $optional = ($this->defaultValue !== null) ? ' = ' . $this->defaultValue : '';
        return $codeType . ' $' . $this->name . $optional;
    }


    /**
     * @return bool
     */
    public function getAllowsNull(): bool
    {
        return $this->allowsNull;
    }
}