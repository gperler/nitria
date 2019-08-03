<?php

declare(strict_types = 1);

namespace Tests;

use NitriaTest\End2End\Asset\CustomException;

/**
 */
class TryCatchTest
{

    /**
     * @param string $value
     * 
     * @return bool
     * @throws \Exception
     */
    public function sayTry(string $value) : bool
    {
        try {
            throw new CustomException();
        } catch(CustomException $e1) {
            return true;
        } catch(\Exception $e2) {
            return false;
        }
        return false;
    }
}