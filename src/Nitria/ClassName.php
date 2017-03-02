<?php

declare(strict_types = 1);

namespace Nitria;


use Civis\Common\StringUtil;

class ClassName
{
    const BS = "\\";

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $classShortName;

    /**
     * @var string
     */
    protected $namespaceName;

    /**
     * @var string
     */
    protected $as;

    /**
     * ClassName constructor.
     *
     * @param string $className
     * @param string|null $as
     */
    public function __construct(string $className, string $as = null)
    {
        if ($as !== null) {
            $this->initializeAs($className, $as);
            return;
        }

        $slashCount = substr_count($className, self::BS);
        if ($slashCount === 0 || ($slashCount === 1 && strpos($className, self::BS) === 0)) {
            $this->initializeDefaultNamespace($className);
            return;
        }

        $this->className = trim($className, self::BS);
        $this->namespaceName = StringUtil::getStartBeforeLast($this->className, self::BS);
        $this->classShortName = StringUtil::getEndAfterLast($this->className, self::BS);
    }

    /**
     * @param string $className
     */
    protected function initializeDefaultNamespace(string $className)
    {
        $className = self::BS . trim($className, "\\");
        $this->className = $className;
        $this->classShortName = $className;
        $this->namespaceName = null;
    }

    /**
     * @param string $className
     * @param string $as
     */
    protected function initializeAs(string $className, string $as)
    {
        $this->className = trim($className, self::BS);
        $this->namespaceName = StringUtil::getStartBeforeLast($this->className, self::BS);
        $this->classShortName = $as;
        $this->as = $as;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function getClassShortName(): string
    {
        return $this->classShortName;
    }

    /**
     * @return string|null
     */
    public function getNamespaceName()
    {
        return $this->namespaceName;
    }

    /**
     * @return string|null
     */
    public function getAs()
    {
        return $this->as;
    }

    /**
     * @param ClassName $otherClass
     *
     * @return bool
     */
    public function isNamespaceIdentical(ClassName $otherClass) : bool
    {
        return $this->namespaceName === $otherClass->getNamespaceName();
    }

    /**
     * @return null|string
     */
    public function getUseStatment()
    {
        if ($this->as === null) {
            return $this->className;
        }
        return $this->className . " as " . $this->as;

    }

    /**
     * @return bool
     */
    public function needsUseStatement() : bool
    {
        return $this->namespaceName !== null;
    }
}