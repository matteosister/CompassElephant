<?php
/**
 * User: matteo
 * Date: 22/08/12
 * Time: 12.53
 *
 * Just for fun...
 */

namespace CompassElephant;

use CompassElephant\TestCase;

class CommandGeneratorTest extends TestCase
{
    /**
     * @var CommandGenerator
     */
    private $generator;
    private $gitPath;

    public function setUp()
    {
        $this->initProject();
        $this->getCompassProject()->init();
        $this->generator = new CommandGenerator($this->getCompassProject()->getCompassBinary());
        $this->gitPath = $this->getCompassProject()->getCompassBinary()->getPath();
    }

    public function testInit()
    {
        $this->assertEquals($this->gitPath.' create --boring', $this->generator->init());
    }

    public function testCheckState()
    {
        $this->assertEquals($this->gitPath.' compile --dry-run --boring', $this->generator->checkState());
    }

    public function testCompile()
    {
        $this->assertEquals($this->gitPath.' compile --config config.rb --boring', $this->generator->compile('config.rb', false, null));
        $this->assertEquals($this->gitPath.' compile --config test.rb --boring', $this->generator->compile('test.rb', false, null));
        $this->assertEquals($this->gitPath.' compile --config test.rb --force --boring', $this->generator->compile('test.rb', true, null));
        $this->assertEquals($this->gitPath.' compile screen.scss --config test.rb --force --boring', $this->generator->compile('test.rb', true, 'screen.scss'));
        $this->assertEquals($this->gitPath.' compile screen.scss --config test.rb --boring', $this->generator->compile('test.rb', false, 'screen.scss'));
    }
}
