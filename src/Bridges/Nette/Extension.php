<?php

namespace Authorizator\Bridges\Nette;

use Authorizator\DibiAuthorizator;
use Nette\DI\CompilerExtension;


/**
 * Class Extension
 *
 * @author  geniv
 * @package Authorizator\Bridges\Nette
 */
class Extension extends CompilerExtension
{
    /** @var array default values */
    private $defaults = [
        'policy'      => 'allow',   // allow | deny | none
        'tablePrefix' => null,
        'autowired'   => null,
    ];


    /**
     * Load configuration.
     */
    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults);

        // define authorizator
        $builder->addDefinition($this->prefix('default'))
            ->setClass(DibiAuthorizator::class, [$config]);

        // if define autowired then set value
        if (isset($config['autowired'])) {
            $builder->getDefinition($this->prefix('default'))
                ->setAutowired($config['autowired']);
        }
    }
}
