<?php

declare(strict_types = 1);

namespace Tests;

use NitriaTest\End2End\Asset\SomeClass;
use NitriaTest\End2End\Asset\SomeInterface;

/**
 */
class MemberTest
{

    /**
     * @var SomeInterface
     */
    private $iAmPrivat;

    /**
     * @var SomeClass
     */
    protected $iAmProtected;

    /**
     * @var float
     */
    public $iAmPublic;
}