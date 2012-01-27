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

use CompassElephant\CommandCaller;

/**
 * CompassElephant
 *
 * @author Matteo Giachino <matteog@gmail.com>
 */

class CompassProject
{
    private $commandCaller;

    public function __construct(CommandCaller $command_caller)
    {
        $this->commandCaller = $command_caller;
    }

    public function init()
    {
        $this->commandCaller->init();
    }

    public function isClean()
    {
        $state = $this->commandCaller->checkState()->getOutputLines();
        $clean = true;
        foreach ($state as $output) {
            if (!preg_match('/^unchanged (.*)/', $output)) {
                $clean = false;
                break;
            }
        }
        return $clean;
    }

    public function compile()
    {
        $this->commandCaller->compile();
    }
}
