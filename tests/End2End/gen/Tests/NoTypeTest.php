<?php

declare(strict_types = 1);

namespace Tests;

class NoTypeTest
{

    /**
     * commented member
     * @var mixed|null
     */
    private  $privateProperty;

    /**
     * commented member
     * @var mixed|null
     */
    protected  $protectedProperty;

    /**
     * commented member
     * @var mixed|null
     */
    public  $publicProperty;

    /**
     * @param mixed $param1
     * 
     * @return void
     */
    public function test($param1)
    {
    }
}