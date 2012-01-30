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
CompassElephant\StalenessChecker\StalenessCheckerInterface,
CompassElephant\StalenessChecker\NativeStalenessChecker,
CompassElephant\StalenessChecker\FinderStalenessChecker;

/**
 * CompassElephant
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class CompassProject
{
    /**
     * @var \CompassElephant\CommandCaller
     */
    private $commandCaller;
    /**
     * @var \CompassElephant\StalenessChecker\StalenessCheckerInterface
     */
    private $stalenessChecker;
    /**
     * @var null|string
     */
    private $configFile = null;

    /**
     * Class constructor
     *
     * @param CommandCaller                                               $commandCaller    a CommandCaller instance
     * @param \CompassElephant\StalenessChecker\StalenessCheckerInterface $stalenessChecker A StalenessCheckerInterface instance
     */
    public function __construct(CommandCaller $commandCaller, StalenessCheckerInterface $stalenessChecker)
    {
        $this->commandCaller    = $commandCaller;
        $this->stalenessChecker = $stalenessChecker;
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
     * stalenessChecker setter
     *
     * @param CompassElephant\StalenessChecker\StalenessCheckerInterface $stalenessChecker the instance
     */
    public function setStalenessChecker($stalenessChecker)
    {
        $this->stalenessChecker = $stalenessChecker;
    }

    /**
     * stalenessChecker getter
     *
     * @return CompassElephant\StalenessChecker\StalenessCheckerInterface
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
