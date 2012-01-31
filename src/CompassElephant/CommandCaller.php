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

use Symfony\Component\Process\Process;
use CompassElephant\CompassBinary;

/**
 * Caller
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class CommandCaller
{
    /**
     * @var \CompassElephant\CompassBinary
     */
    private $binary;
    /**
     * @var string
     */
    private $projectPath;
    /**
     * @var string
     */
    private $output;

    /**
     * Class constructor
     *
     * @param string        $projectPath the path of the compass project
     * @param CompassBinary $binary      a CompassBinary instance
     */
    public function __construct($projectPath, CompassBinary $binary = null)
    {
        if ($binary == null) {
            $binary = new CompassBinary();
        }
        $this->binary = $binary;
        $this->projectPath = realpath($projectPath);
    }

    /**
     * build the init command
     *
     * @return CommandCaller
     */
    public function init()
    {
        $cmd = $this->binary->getPath().' create --boring';
        $this->execute($cmd);
        return $this;
    }

    /**
     * build the checkState command, useful for native staleness check implementation
     *
     * @return CommandCaller
     */
    public function checkState()
    {
        $cmd = $this->binary->getPath().' compile --dry-run --boring';
        $this->execute($cmd);
        return $this;
    }

    /**
     * build a compile command
     *
     * @return CommandCaller
     */
    public function compile()
    {
        $cmd = $this->binary->getPath().' compile --boring';
        $this->execute($cmd);
        return $this;
    }

    /**
     * Execute a command
     *
     * @param string $cmd the command
     */
    private function execute($cmd)
    {
        $process = new Process(escapeshellcmd($cmd), $this->projectPath);
        $process->run();
        $this->output = trim($process->getOutput(), PHP_EOL);
    }

    /**
     * gets the command output
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * get the project path
     *
     * @return string
     */
    public function getProjectPath()
    {
        return $this->projectPath;
    }
}
