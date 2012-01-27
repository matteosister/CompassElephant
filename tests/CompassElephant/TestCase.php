<?php

/**
 * This file is part of the GitElephant package.
 *
 * (c) Matteo Giachino <matteog@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Just for fun...
 */

namespace CompassElephant;

class TestCase extends \PHPUnit_Framework_TestCase
{
    private $path;
    private $binary;
    private $commandCaller;
    private $compassProject;


    public function initProject()
    {
        $tempDir = realpath(sys_get_temp_dir()).'compass_elephant_'.md5(uniqid(rand(),1));
        $tempName = tempnam($tempDir, 'compass_elephant');
        $this->path = $tempName;
        unlink($this->path);
        mkdir($this->path);
        $this->binary = new CompassBinary(null);
        $this->commandCaller = new CommandCaller($this->binary, $this->path);
        $this->compassProject = new CompassProject($this->commandCaller);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return \CompassElephant\CompassBinary
     */
    public function getBinary()
    {
        return $this->binary;
    }

    /**
     * @return \CompassElephant\CommandCaller
     */
    public function getCommandCaller()
    {
        return $this->commandCaller;
    }

    /**
     * @return \CompassElephant\CompassProject
     */
    public function getCompassProject()
    {
        return $this->compassProject;
    }
}
