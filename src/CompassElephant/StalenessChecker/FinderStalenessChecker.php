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

    public function __construct($projectPath, $configFile)
    {
        $this->projectPath = $projectPath;
        $this->configFile = $configFile;
        $this->findPaths();
        $this->checkPaths();
    }

    /**
     * return true if the project do not need to be recompiled
     *
     * @return boolean
     */
    public function isClean()
    {
        return $this->getFilesMaxAge($this->sassPath, array('*.sass', '*.scss')) < $this->getFilesMaxAge($this->cssPath, array('*.css'));
    }

    private function checkPaths()
    {
        if (!is_writable($this->sassPath)) {
            throw new \RuntimeException(sprintf('The path %s should be writable (by the webserver user) for CompassElephant to compile the project', realpath($this->sassPath)));
        }
        if (!is_writable($this->cssPath)) {
            throw new \RuntimeException(sprintf('The path %s should be writable (by the webserver user) for CompassElephant to compile the project', realpath($this->cssPath)));
        }
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
            if (preg_match('/sass_dir = "(.*)"/', $line, $matches) !== 0) {
                $this->sassPath = $this->projectPath.DIRECTORY_SEPARATOR.$matches[1];
            }
            if (preg_match('/css_dir = "(.*)"/', $line, $matches) !== 0) {
                $this->cssPath = $this->projectPath.DIRECTORY_SEPARATOR.$matches[1];
            }
        }
    }

    private function getFilesMaxAge($path, $names)
    {
        $finder = new Finder();
        $finder->in(realpath($path));
        foreach($names as $name) {
            $finder->name($name);
        }
        if (count($finder) == 0) {
            return 0;
        }
        $ages = array();
        foreach($finder as $file)
        {
            $ages[] = filemtime($file);
        }
        return max($ages);
    }
}
