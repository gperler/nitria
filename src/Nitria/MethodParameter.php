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
     * MethodParameter constructor.
     *
     * @param Type $type
     * @param string $name
     * @param string $defaultValue
     */
    public function __construct(Type $type, string $name, string $defaultValue = null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @return string
     */
    public function getPHPDocLine()
    {
        return '@param ' . $this->type->getDocBlockType() . ' $' . $this->name;
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
        $optional = ($this->defaultValue !== null && $this->defaultValue !== '') ? ' = ' . $this->defaultValue : '';
        return $codeType . ' $' . $this->name . $optional;
    }
}