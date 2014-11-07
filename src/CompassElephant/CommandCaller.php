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
use CompassElephant\Exception\CompassException;

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
     * @var CommandGenerator
     */
    private $commandGenerator;
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
        $this->commandGenerator = new CommandGenerator($binary);
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
        $this->execute($this->commandGenerator->init());

        return $this;
    }

    /**
     * build a compile command
     *
     * @param string $configFile config file name
     * @param bool   $force      force recompile
     * @param string $target     target file
     *
     * @return CommandCaller
     */
    public function compile($configFile, $force, $target)
    {
        $this->execute($this->commandGenerator->compile($configFile, $force, $target));

        return $this;
    }

    /**
     * Execute a command
     *
     * @param string $cmd the command
     *
     * @throws Exception\CompassException
     */
    private function execute($cmd)
    {
        $process = new Process(escapeshellcmd($cmd), $this->projectPath);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new CompassException($process->getErrorOutput());
        }
        $this->output = $process->getOutput();
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
