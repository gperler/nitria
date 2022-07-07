<?php

declare(strict_types = 1);

namespace Tests;

use NitriaTest\End2End\Asset\SomeClass;
use NitriaTest\End2End\Asset\SomeInterface;

class StaticMemberTest
{

    /**
     * @var SomeInterface|null
     */
    private static ?SomeInterface $iAmPrivat;

    /**
     * @var SomeClass|null
     */
    protected static ?SomeClass $iAmProtected;

    /**
     * @var float|null
     */
    public static ?float $iAmPublic;

    /**
     * @var \DateTime|null
     */
    public static ?\DateTime $iAmPublicToo;
}