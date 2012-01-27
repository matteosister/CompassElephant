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
     * @var array
     */
    private $outputLines;

    /**
     * @param CompassBinary $binary      a CompassBinary instance
     * @param string        $projectPath the path of the compass project
     */
    public function __construct(CompassBinary $binary, $projectPath)
    {
        $this->binary = $binary;
        $this->projectPath = realpath($projectPath);
    }

    public function init()
    {
        $cmd = 'compass create';
        $this->execute($cmd);
        return $this;
    }

    public function checkState()
    {
        $cmd = 'compass compile --dry-run --boring';
        $this->execute($cmd);
        return $this;
    }

    public function compile()
    {
        $cmd = 'compass compile';
        $this->execute($cmd);
        return $this;
    }

    private function execute($cmd)
    {
        $process = new Process($cmd, $this->projectPath);
        $process->setTimeout(15000);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
        $this->outputLines = explode(PHP_EOL, trim($process->getOutput(), PHP_EOL));
    }

    public function getOutputLines()
    {
        return $this->outputLines;
    }
}
