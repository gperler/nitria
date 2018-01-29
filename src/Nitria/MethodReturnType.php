<?php

declare(strict_types = 1);

namespace Nitria;

/**
 * @author Gregor Müller
 */
class MethodReturnType
{
    /**
     * @var Type
     */
    protected $type;

    /**
     * @var bool
     */
    protected $nullAble;

    /**
     * MethodReturnType constructor.
     *
     * @param Type|null $type
     * @param bool $nullAble
     */
    public function __construct(Type $type = null, bool $nullAble = true)
    {
        $this->type = $type;
        $this->nullAble = $nullAble;
    }

    /**
     * @return string
     */
    public function getDocBlockReturnType()
    {
        if ($this->type === null) {
            return '@return void';
        }
        $optional = $this->nullAble ? '|null' : '';
        return '@return ' . $this->type->getDocBlockType() . $optional;
    }

    /**
     * @return null|string
     */
    public function getSignatureReturnType()
    {
        if ($this->type === null || $this->type->getCodeType() === null) {
            return '';
        }
        if ($this->nullAble) {
            return ' : ?' . $this->type->getCodeType();
        }

        return ' : ' . $this->type->getCodeType();
    }

    /**
     * @return bool
     */
    public function hasReturnType() : bool
    {
        return ($this->type !== null) && $this->type->getCodeType() !== null;
    }

}