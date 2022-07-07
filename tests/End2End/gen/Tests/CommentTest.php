<?php

declare(strict_types = 1);

namespace Tests;

use Codeception\Exception\TestRuntimeException;
use NitriaTest\End2End\Asset\SomeClass;

class CommentTest
{

    /**
     * commented member
     * @var string|null
     */
    private ?string $comment;

    /**
     * commented method
     * 
     * @return SomeClass[]
     * @throws \Exception
     * @throws TestRuntimeException
     */
    public function commentedMethod() : array
    {
        // this is really important
        return [];
    }
}