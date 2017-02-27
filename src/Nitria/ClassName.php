<?php

declare(strict_types = 1);

namespace Nitria;

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
     * PHPClass constructor.
     *
     * @param string $className
     */
    public function __construct(string $className)
    {

        $slashCount = substr_count($className, self::BS);

        if ($slashCount === 0) {
            $this->className = self::BS . $className;
            $this->classShortName = self::BS . $className;
            $this->namespaceName = null;
            return;
        }

        if ($slashCount === 1 && strpos($className, self::BS) === 0) {

            $this->classShortName = $className;
            $this->className = $className;
            $this->namespaceName = null;
            return;
        }

        $this->className = trim($className, self::BS);
        $this->namespaceName = StringUtil::getStartBeforeLast($this->className, self::BS);
        $this->classShortName = StringUtil::getEndAfterLast($this->className, self::BS);
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
     * @param ClassName $otherClass
     *
     * @return bool
     */
    public function isNamespaceIdentical(ClassName $otherClass) : bool
    {
        return $this->namespaceName === $otherClass->getNamespaceName();
    }

}