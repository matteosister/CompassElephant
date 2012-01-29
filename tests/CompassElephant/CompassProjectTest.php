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

namespace CompassElephant;

use CompassElephant\TestCase;

/**
 * CompassElephantTest
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class CompassProjectTest extends TestCase
{
    public function setUp()
    {
        $this->initProject();
    }

    public function testBinary()
    {
        $this->assertNotNull($this->getBinary()->getPath());
    }

    public function testCaller()
    {
        $this->assertNotNull($this->getCommandCaller());
    }

    public function testProject()
    {
        $cp = $this->getCompassProject();
        $this->assertNotNull($cp);
        $cp->init();
        $this->assertTrue($cp->isClean());
        $this->writeStyle('body { background-color: #000; }');
        $this->assertFalse($cp->isClean());
        $cp->compile();
        $this->assertTrue($cp->isClean());
    }

    private function writeStyle($style)
    {
        $handle = fopen($this->getPath().'/sass/screen.scss', 'w');
        fwrite($handle, PHP_EOL.$style.PHP_EOL);
        fclose($handle);
    }
}
