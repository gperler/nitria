<?php

namespace Nitria;

enum ScalarType: string
{

    case Bool = 'bool';

    case Int = 'int';

    case Float = 'float';

    case String = 'string';

    case Array = 'array';

    case Callable = 'callable';
}