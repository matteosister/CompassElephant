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

/**
 * CompassBinary
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class CompassBinary
{
    private $path;

    public function __construct($path = null)
    {
        $this->path = $path;
        if ($path == null) {
            $this->tryToFindCompass();
        }
    }

    private function tryToFindCompass()
    {
        $this->path = trim(exec('which compass'), PHP_EOL);
    }

    public function getPath()
    {
        return $this->path;
    }
}
