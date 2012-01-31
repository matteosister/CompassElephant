<?php

/**
 * This file is part of the CompassElephant package.
 *
 * (c) Matteo Giachino <matteog@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Just for fun...
 */

namespace CompassElephant\StalenessChecker;

use CompassElephant\TestCase;

/**
 * NativeStalenessCheckerTest
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class FinderStalenessCheckerTest extends TestCase
{
    public function setUp()
    {
        $this->initProject();
    }

    public function testIsClean()
    {
        $cp = $this->getCompassProject();
        $cp->init();
        $this->assertTrue($cp->isClean());
        $this->writeStyle('body { background-color: #000; }');
        $this->assertFalse($cp->isClean());
        $cp->compile();
        $this->assertTrue($cp->isClean());
        sleep(2);
        touch($this->getPath().DIRECTORY_SEPARATOR.$this->getCompassProject()->getConfigFile());
        $this->assertFalse($cp->isClean());
        $this->assertInstanceOf('CompassElephant\StalenessChecker\FinderStalenessChecker', $this->getCompassProject()->getStalenessChecker());
        $this->getCompassProject()->setConfigFile('new-config.rb');
        $this->assertEquals('new-config.rb', $this->getCompassProject()->getConfigFile());
    }
}
