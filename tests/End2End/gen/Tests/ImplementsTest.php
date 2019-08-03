<?php

declare(strict_types = 1);

namespace Tests;

use NitriaTest\End2End\Asset\SomeInterface;
use NitriaTest\End2End\Asset\SomeOtherInterface;

/**
 */
class ImplementsTest implements SomeInterface, SomeOtherInterface
{
}