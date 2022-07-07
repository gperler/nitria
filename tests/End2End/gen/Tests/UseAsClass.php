<?php

declare(strict_types = 1);

namespace Tests;

use Nitria\Constant as MyConstant;
use Nitria\Property as MyProperty;
use Nitria\StringUtil as MyStringUtil;

class UseAsClass
{

    /**
     * @var MyStringUtil|null
     */
    protected ?MyStringUtil $myProp;

    /**
     * @param MyConstant $paramName
     * 
     * @return MyProperty
     */
    public function testMethod(MyConstant $paramName) : MyProperty
    {
    }
}