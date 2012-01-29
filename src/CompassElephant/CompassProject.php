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
    private $stalenessChecker;
    private $configFile = null;

    /**
     * Class constructor
     *
     * @param CommandCaller $commandCaller a CommandCaller instance
     */
    public function __construct(CommandCaller $commandCaller)
    {
        $this->nativeStalenessChecker = true;
        $this->commandCaller = $commandCaller;
    }

    /**
     * Create a new compass project
     */
    public function init()
    {
        $this->commandCaller->init();
    }

    /**
     * Check if the project is up-to-date or needs to be recompiled
     *
     * @return bool
     */
    public function isClean()
    {
        return $this->stalenessChecker->isClean();
    }

    /**
     * Compile the project
     */
    public function compile()
    {
        $this->commandCaller->compile();
    }

    /**
     * nativeStalenessChecker setter
     *
     * @param bool $stalenessChecker native or not
     */
    public function setStalenessChecker($stalenessChecker)
    {
        if ($stalenessChecker == 'finder') {
            $this->stalenessChecker = new FinderStalenessChecker($this->commandCaller->getProjectPath(), $this->configFile);
        } else if ($stalenessChecker == 'native') {
            $this->stalenessChecker = new NativeStalenessChecker($this->commandCaller);
        } else {
            throw new \InvalidParameterException(sprintf('The stalenessCheker should be "finder" or "native", %s given', $stalenessChecker));
        }
    }

    /**
     * nativeStalenessChecker getter
     *
     * @return bool
     */
    public function getStalenessChecker()
    {
        return $this->stalenessChecker;
    }

    /**
     * configFile setter
     *
     * @param string $configFile the compass config file name
     */
    public function setConfigFile($configFile)
    {
        $this->configFile = $configFile;
    }

    /**
     * configFile getter
     *
     * @return null
     */
    public function getConfigFile()
    {
        return $this->configFile;
    }
}
