<?php
namespace EssentialsPE\BaseFiles;

use EssentialsPE\Loader;
use pocketmine\event\plugin\PluginEvent;

abstract class BaseCustomEvent extends PluginEvent{
    /** @var BaseAPI */
    private $api;

    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api->getEssentialsPEPlugin());
        $this->api = $api;
    }

    /**
     * @return Loader
     */
    public final function getPlugin(){
        return $this->getAPI()->getEssentialsPEPlugin();
    }

    /**
     * @return BaseAPI
     */
    public final function getAPI(){
        return $this->api;
    }
}