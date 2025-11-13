<?php

namespace Nitria;

enum ClassType: string
{
    case Interface = 'interface';

    case ClassType = 'class';

    case Enum = 'enum';
}