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
use Symfony\Component\Finder\Finder;

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

    /**
     * class constructor
     *
     * @param string $projectPath the path to the compass project
     * @param string $configFile  the config file name
     */
    public function __construct($projectPath, $configFile)
    {
        $this->projectPath = $projectPath;
        $this->configFile  = $configFile;
    }

    /**
     * return true if the project do not need to be recompiled
     *
     * @return boolean
     */
    public function isClean()
    {
        if ($this->getConfigFileAge() > max($this->getSassMaxAge(), $this->getStylesheetsMaxAge())) {
            return false;
        }
        return $this->getSassMaxAge() <= $this->getStylesheetsMaxAge();
    }

    /**
     * check if sass and stylesheets paths are writable
     *
     * @throws \RuntimeException
     */
    private function checkPaths()
    {
        if (!is_writable($this->sassPath)) {
            throw new \RuntimeException(sprintf('The path %s should be writable (by the webserver user) for CompassElephant to compile the project', realpath($this->sassPath)));
        }
        if (!is_writable($this->cssPath)) {
            throw new \RuntimeException(sprintf('The path %s should be writable (by the webserver user) for CompassElephant to compile the project', realpath($this->cssPath)));
        }
    }

    /**
     * find sass and stylesheets files parsing the config file
     *
     * @throws \FileNotFoundException
     */
    private function findPaths()
    {
        $configFilename = $this->projectPath . DIRECTORY_SEPARATOR . $this->configFile;
        $handle         = fopen($configFilename, 'r');
        if (false === $handle) {
            throw new \FileNotFoundException($configFilename . ' could not be found');
        }
        $contents = fread($handle, filesize($configFilename));
        foreach (explode(PHP_EOL, $contents) as $line) {
            if (preg_match('/sass_dir ?= ?"(.*)"/', $line, $matches) !== 0) {
                $this->sassPath = $this->projectPath . DIRECTORY_SEPARATOR . $matches[1];
            }
            if (preg_match('/css_dir ?= ?"(.*)"/', $line, $matches) !== 0) {
                $this->cssPath = $this->projectPath . DIRECTORY_SEPARATOR . $matches[1];
            }
        }
    }

    /**
     * Get the max_age of stylesheets files
     *
     * @return int
     */
    private function getStylesheetsMaxAge()
    {
        if ($this->cssPath == null) {
            $this->findPaths();
            $this->checkPaths();
        }
        return $this->getFilesMaxAge($this->cssPath, array('*.css'));
    }

    /**
     * get the max_age of sass/scss files
     *
     * @return int
     */
    private function getSassMaxAge()
    {
        if ($this->sassPath == null) {
            $this->findPaths();
            $this->checkPaths();
        }
        return $this->getFilesMaxAge($this->sassPath, array('*.sass', '*.scss'));
    }

    /**
     * Get max_age with a Finder instance
     *
     * @param string $path    the path for the Finder instance
     * @param string $names   the file names
     * @param int    $default the default time if no files are found
     *
     * @return int
     */
    private function getFilesMaxAge($path, $names, $default = 0)
    {
        $finder = new Finder();
        $finder->files()->in(realpath($path))->ignoreDotFiles(true);
        foreach ($names as $name) {
            $finder->name($name);
        }
        $ages = array();
        foreach ($finder as $file) {
            $ages[] = filemtime($file);
        }
        return max(array_merge($ages, array($default)));
    }

    /**
     * get max_age for config file
     *
     * @return int
     */
    private function getConfigFileAge()
    {
        return filemtime(realpath($this->projectPath . DIRECTORY_SEPARATOR . $this->configFile));
    }
}
