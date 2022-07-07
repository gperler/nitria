<?php

declare(strict_types = 1);

namespace Tests;

class StaticMemberWithValueTest
{

    /**
     * @var string|null
     */
    private static ?string $iAmPrivat = "K&D";

    /**
     * @var array|null
     */
    protected static ?array $iAmProtected = [1,2,3];

    /**
     * @var float|null
     */
    public static ?float $iAmPublic = 23.122;
}