<?php

declare(strict_types=1);

namespace Tweakwise\Test;

use Emico\CodeCept\Test\Unit;
use Tweakwise\Test\Support\FunctionalTester;

class InitialTest extends Unit
{
    /**
     * @var FunctionalTester
     */
    protected FunctionalTester $tester;

    /**
     * @return void
     */
    public function testInitial(): void
    {
        $this->tester->assertTrue(true);
    }
}
