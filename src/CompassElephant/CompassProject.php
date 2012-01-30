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
     * @var string the path to the compass project
     */
    private $projectPath;

    /**
     * @var \CompassElephant\CompassBinary
     */
    private $compassBinary;

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
    private $configFile;

    /**
     * Class constructor
     *
     * @param string                                                      $projectPath      the path to the compass project
     * @param \CompassElephant\CompassBinary|null                         $compassBinary    a CompassBinary instance
     * @param \CompassElephant\CommandCaller                              $commandCaller    a CommandCaller instance
     * @param \CompassElephant\StalenessChecker\StalenessCheckerInterface $stalenessChecker a StalenessCheckerInterface instance
     * @param string                                                      $configFile       the compass config file name
     */
    public function __construct($projectPath, CompassBinary $compassBinary = null, CommandCaller $commandCaller = null, StalenessCheckerInterface $stalenessChecker = null, $configFile = 'config.rb')
    {
        $this->projectPath = $projectPath;
        if ($compassBinary == null) {
            $compassBinary = new CompassBinary();
        }
        $this->compassBinary = $compassBinary;
        if ($commandCaller == null) {
            $commandCaller = new CommandCaller($projectPath, $this->compassBinary);
        }
        $this->commandCaller = $commandCaller;
        if ($stalenessChecker == null) {
            $stalenessChecker = new FinderStalenessChecker($projectPath, $configFile);
        }
        $this->stalenessChecker = $stalenessChecker;
        $this->configFile = $configFile;
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
     * projectPath setter
     *
     * @param string $projectPath the compass project path
     */
    public function setProjectPath($projectPath)
    {
        $this->projectPath = $projectPath;
    }

    /**
     * projectPath getter
     *
     * @return string
     */
    public function getProjectPath()
    {
        return $this->projectPath;
    }

    /**
     * compassBinary setter
     *
     * @param \CompassElephant\CompassBinary $compassBinary a CompassBinary instance
     */
    public function setCompassBinary($compassBinary)
    {
        $this->compassBinary = $compassBinary;
    }

    /**
     * compassBinary getter
     *
     * @return \CompassElephant\CompassBinary
     */
    public function getCompassBinary()
    {
        return $this->compassBinary;
    }

    /**
     * commandCaller setter
     *
     * @param \CompassElephant\CommandCaller $commandCaller a CommandCaller instance
     */
    public function setCommandCaller($commandCaller)
    {
        $this->commandCaller = $commandCaller;
    }

    /**
     * commandCaller getter
     *
     * @return \CompassElephant\CommandCaller
     */
    public function getCommandCaller()
    {
        return $this->commandCaller;
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
