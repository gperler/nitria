<?php

declare(strict_types = 1);

namespace Tests;

/**
 */
class WhileStatementTest
{

    /**
     * @param int $int
     * 
     * @return string
     */
    public function sayWhile(int $int) : string
    {
        $string = "";
        while ($int++ < 10) {
            $string .= "x";
        }
        return $string;
    }
}