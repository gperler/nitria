<?php

declare(strict_types = 1);

namespace Tests;

use NitriaTest\End2End\Asset\SomeClass;
use NitriaTest\End2End\Asset\SomeInterface;

class MemberTest
{

    /**
     * @var SomeInterface|null
     */
    private ?SomeInterface $iAmPrivat;

    /**
     * @var SomeClass|null
     */
    protected ?SomeClass $iAmProtected;

    /**
     * @var float|null
     */
    public ?float $iAmPublic;
}