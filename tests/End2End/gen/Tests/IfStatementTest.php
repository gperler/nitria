<?php

declare(strict_types = 1);

namespace Tests;

/**
 */
class IfStatementTest
{

    /**
     * @param int $int This explains Why
     * 
     * @return int
     */
    public function sayIf(int $int) : int
    {
        if ($int === 1) {
            return 1;
        } else if ($int === 2){
            return 2;
        } else {
            return 3;
        }
    }
}