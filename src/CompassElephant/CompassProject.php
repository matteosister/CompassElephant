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

use CompassElephant\CommandCaller,
    CompassElephant\StalenessChecker\NativeStalenessChecker,
    CompassElephant\StalenessChecker\FinderStalenessChecker;

/**
 * CompassElephant
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class CompassProject
{
    private $commandCaller;
    private $nativeStalenessChecker;
    private $configFile = null;

    public function __construct(CommandCaller $commandCaller)
    {
        $this->nativeStalenessChecker = true;
        $this->commandCaller = $commandCaller;
    }

    public function init()
    {
        $this->commandCaller->init();
    }

    public function isClean()
    {
        return $this->getStalenessChecker()->isClean();
    }

    public function compile()
    {
        $this->commandCaller->compile();
    }

    private function getStalenessChecker()
    {
        if ($this->nativeStalenessChecker) {
            return new NativeStalenessChecker($this->commandCaller);
        } else {
            return new FinderStalenessChecker($this->commandCaller->getProjectPath(), $this->configFile);
        }
    }

    public function setNativeStalenessChecker($nativeStalenessChecker)
    {
        $this->nativeStalenessChecker = $nativeStalenessChecker;
    }

    public function getNativeStalenessChecker()
    {
        return $this->nativeStalenessChecker;
    }

    public function setConfigFile($configFile)
    {
        $this->configFile = $configFile;
    }

    public function getConfigFile()
    {
        return $this->configFile;
    }
}
