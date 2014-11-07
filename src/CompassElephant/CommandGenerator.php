<?php
/**
 * User: matteo
 * Date: 22/08/12
 * Time: 13.16
 *
 * Just for fun...
 */

namespace CompassElephant;

use CompassElephant\CompassBinary;

class CommandGenerator
{
    /**
     * @var CompassBinary
     */
    private $binary;

    public function __construct(CompassBinary $binary)
    {
        $this->boring = true;
        $this->binary = $binary;
    }

    public function init()
    {
        return $this->generate('create');
    }

    public function compile($config_file, $force, $target)
    {
        $target = null === $target ? '' : $target;
        $force = $force ? '--force' : '';
        return $this->generate(sprintf('compile %s --config %s %s', $target, $config_file, $force));
    }

    private function generate($cmd)
    {
        $cmd = trim($cmd, ' ');
        $cmd = preg_replace('/\s+/', ' ', $cmd);
        return $this->binary->getPath().' '.$cmd.' --boring';
    }
}
