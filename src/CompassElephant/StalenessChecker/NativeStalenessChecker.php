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

use CompassElephant\StalenessChecker\StalenessCheckerInterface,
    CompassElephant\CommandCaller;

/**
 * NativeStalenessChecker
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class NativeStalenessChecker implements StalenessCheckerInterface
{
    private $caller;

    public function __construct(CommandCaller $caller)
    {
        $this->caller = $caller;
    }

    /**
     * return true if the project do not need to be recompiled
     *
     * @return boolean
     */
    public function isClean()
    {
        $state = $this->caller->checkState()->getOutput();
        foreach (explode(PHP_EOL, $state) as $line) {
            if (preg_match('/^overwrite(.*)/', $line)) {
                return false;
            }
        }
        return true;
    }
}
