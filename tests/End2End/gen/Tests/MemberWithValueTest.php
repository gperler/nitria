<?php

declare(strict_types = 1);

namespace Tests;

/**
 */
class MemberWithValueTest
{

    /**
     * @var string
     */
    private $iAmPrivat = "K&D";

    /**
     * @var array
     */
    protected $iAmProtected = [1,2,3];

    /**
     * @var float
     */
    public $iAmPublic = 19.08;
}