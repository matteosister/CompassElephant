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

use CompassElephant\CommandCaller;

/**
 * StalenessCheckerInterface
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

interface StalenessCheckerInterface
{
    /**
     * @abstract
     *
     * return true if the project do not need to be recompiled
     *
     * @return boolean
     */
    public function isClean();
}
