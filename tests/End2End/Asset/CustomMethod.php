<?php

declare(strict_types = 1);

namespace NitriaTest\End2End\Asset;

use Nitria\Method;

class CustomMethod extends Method
{

    public function addMyCustomMethod()
    {
        $this->addCodeLine("return 19.08;");
    }
}