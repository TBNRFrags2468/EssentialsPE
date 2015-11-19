<?php
namespace EssentialsPE\BaseFiles;

use EssentialsPE\Loader;
use pocketmine\event\Listener;

class BaseEventHandler implements Listener{
    /** @var BaseAPI */
    private $api;

    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
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