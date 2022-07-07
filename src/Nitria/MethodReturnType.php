<?php

declare(strict_types=1);

namespace Nitria;

/**
 * @author Gregor Müller
 */
class MethodReturnType
{
    /**
     * @var Type|null
     */
    protected ?Type $type;

    /**
     * @var bool
     */
    protected bool $nullAble;

    /**
     * @var bool
     */
    protected bool $isConstructor;


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
        $this->isConstructor = false;
    }


    /**
     * @return string
     */
    public function getDocBlockReturnType(): string
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
    public function getSignatureReturnType(): ?string
    {
        if ($this->isConstructor) {
            return '';
        }
        if ($this->type !== null && $this->type->getDocBlockType() === 'mixed') {
            return ': mixed';
        }
        if ($this->type === null || $this->type->getCodeType() === null) {
            return ': void';
        }
        if ($this->nullAble) {
            return ': ?' . $this->type->getCodeType();
        }

        return ': ' . $this->type->getCodeType();
    }


    /**
     * @return bool
     */
    public function hasReturnType(): bool
    {
        return ($this->type !== null) && $this->type->getCodeType() !== null;
    }


    /**
     * @return bool
     */
    public function isConstructor(): bool
    {
        return $this->isConstructor;
    }


    /**
     * @param bool $isConstructor
     */
    public function setIsConstructor(bool $isConstructor): void
    {
        $this->isConstructor = $isConstructor;
    }


}