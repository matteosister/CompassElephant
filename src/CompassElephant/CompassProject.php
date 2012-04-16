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
     * @var string a project name
     */
    private $name;

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
     * @param string                              $projectPath      the path to the compass project
     * @param null                                $name             the project name
     * @param \CompassElephant\CompassBinary|null $compassBinary    a CompassBinary instance
     * @param mixed                               $stalenessChecker a StalenessCheckerInterface instance
     * @param string                              $configFile       the compass config file name
     * @param bool                                $autoInit         whether to call init() on an empty folder project
     *
     * @internal param \CompassElephant\CommandCaller $commandCaller a CommandCaller instance
     */
    public function __construct($projectPath, $name = null, CompassBinary $compassBinary = null, $stalenessChecker = null, $configFile = 'config.rb', $autoInit = true)
    {
        $this->name = $name;
        if (!is_writable($projectPath)) {
            throw new \InvalidArgumentException(sprintf('CompassElephant is not able to write in the given path %s', $projectPath));
        }
        $this->projectPath = $projectPath;
        if ($compassBinary == null) {
            $compassBinary = new CompassBinary();
        }
        $this->compassBinary = $compassBinary;
        $this->commandCaller = new CommandCaller($projectPath, $this->compassBinary);
        if ($stalenessChecker == null) {
            $stalenessChecker = new FinderStalenessChecker($projectPath, $configFile);
        } else {
            if ($stalenessChecker instanceof StalenessCheckerInterface) {
                $this->stalenessChecker = $stalenessChecker;
            } else {
                if (is_string($stalenessChecker)) {
                    $this->stalenessChecker = new $stalenessChecker;
                } else {
                    throw new \InvalidArgumentException('the parameter $stalenessChecker for CompassProject class should be a string or an instance of StalenessCheckerInterface');
                }
            }
        }
        $this->stalenessChecker = $stalenessChecker;
        $this->configFile       = $configFile;

        if (!$this->isInitiated()) {
            if ($autoInit) {
                $this->init();
            } else {
                throw new \RuntimeException(sprintf('The compass project in path %s seems not initiated. CompassElephant is unable to find the config file %s. You can call ->init() on the CompassProject instance, or go to the folder and call "compass create"', $this->projectPath, $this->configFile));
            }
        }
    }

    /**
     * return true if the project has a config file
     *
     * @return bool
     */
    public function isInitiated()
    {
        return is_file($this->projectPath.DIRECTORY_SEPARATOR.$this->configFile);
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

    /**
     * Name getter
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
