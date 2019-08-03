<?php

declare(strict_types = 1);

namespace Tests;

/**
 */
class StaticMemberWithValueTest
{

    /**
     * @var string
     */
    private static $iAmPrivat = "K&D";

    /**
     * @var array
     */
    protected static $iAmProtected = [1,2,3];

    /**
     * @var float
     */
    public static $iAmPublic = 23.122;
}