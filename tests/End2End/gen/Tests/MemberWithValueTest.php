<?php

declare(strict_types = 1);

namespace Tests;

class MemberWithValueTest
{

    /**
     * @var string|null
     */
    private ?string $iAmPrivat = "K&D";

    /**
     * @var array|null
     */
    protected ?array $iAmProtected = [1,2,3];

    /**
     * @var float|null
     */
    public ?float $iAmPublic = 19.08;
}