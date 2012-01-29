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

namespace CompassElephant\StalenessChecker;

use CompassElephant\StalenessChecker\StalenessCheckerInterface;

/**
 * FinderStalenessChecker
 *
 * a staleness checker using symfony finder component
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class FinderStalenessChecker implements StalenessCheckerInterface
{
    private $projectPath;
    private $configFile;

    private $sassPath;
    private $cssPath;

    public function __construct($projectPath, $configFile)
    {
        $this->projectPath = $projectPath;
        $this->configFile = $configFile;
        $this->findPaths();
    }

    /**
     * return true if the project do not need to be recompiled
     *
     * @return boolean
     */
    public function isClean()
    {
        return true;
    }

    private function findPaths()
    {
        $config_filename = $this->projectPath.DIRECTORY_SEPARATOR.$this->configFile;
        $handle = fopen($config_filename, 'r');
        if (false === $handle) {
            throw new \FileNotFoundException($config_filename.' could not be found');
        }
        $contents = fread($handle, filesize($config_filename));
        foreach(explode(PHP_EOL, $contents) as $line)
        {
            if (preg_match_all('/sass_dir = "(.*)"/', $line, $matches) !== 0) {
                $this->sassPath = $matches[1];
            }
            if (preg_match_all('/css_dir = "(.*)"/', $line, $matches) !== 0) {
                $this->cssPath = $matches[1];
            }
        }
        var_dump($this);
    }
}
