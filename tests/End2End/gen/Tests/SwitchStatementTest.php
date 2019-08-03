<?php

declare(strict_types = 1);

namespace Tests;

/**
 */
class SwitchStatementTest
{

    /**
     * @param string $value
     * 
     * @return string
     */
    public function saySwitch(string $value) : string
    {
        switch ($value) {
            case "a":
                return "a";
                break;
            case "b":
                return "b";
            default:
                return "c";
                break;
        }
    }
}