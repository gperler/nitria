<?php

declare(strict_types = 1);

namespace Tests;

use NitriaTest\End2End\Asset\SomeClass;
use NitriaTest\End2End\Asset\SomeInterface;

/**
 */
class StaticMemberTest
{

    /**
     * @var SomeInterface
     */
    private static $iAmPrivat;

    /**
     * @var SomeClass
     */
    protected static $iAmProtected;

    /**
     * @var float
     */
    public static $iAmPublic;

    /**
     * @var \DateTime
     */
    public static $iAmPublicToo;
}