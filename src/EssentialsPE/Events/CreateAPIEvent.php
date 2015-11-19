<?php
namespace EssentialsPE\Events;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\Loader;
use pocketmine\event\plugin\PluginEvent;

class CreateAPIEvent extends PluginEvent{
    /** @var BaseAPI */
    private $class;

    /**
     * @param Loader $plugin
     * @param BaseAPI::class $api
     */
    public function __construct(Loader $plugin, $api){
        parent::__construct($plugin);
        if(!is_a($api, BaseAPI::class, true)){
            throw new \RuntimeException("Class $api must extend " . BaseAPI::class);
        }
        $this->class = BaseAPI::class;
    }

    /**
     * @return BaseAPI
     */
    public function getClass(){
        return $this->class;
    }

    /**
     * @param BaseAPI::class $api
     */
    public function setClass($api){
        if(!is_a($api, BaseAPI::class, true)){
            throw new \RuntimeException("Class $api must extend " . BaseAPI::class);
        }
        $this->class = $api;
    }
}