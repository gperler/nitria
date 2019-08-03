<?php

declare(strict_types = 1);

namespace Tests;

/**
 */
class ForeachStatementTest
{

    /**
     * @param array $list
     * 
     * @return string
     */
    public function sayForeach(array $list) : string
    {
        $string = "";
        foreach ($list as $item) {
            $string .= $item;
        }
        return $string;
    }
}