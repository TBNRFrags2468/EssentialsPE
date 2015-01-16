<?php
namespace EssentialsPE;

use pocketmine\scheduler\PluginTask;

abstract class BaseTask extends PluginTask{
    /** @var Loader */
    private $plugin;

    public function __construct(Loader $plugin){
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }

    /**
     * @return Loader
     */
    public final function getPlugin(){
        return $this->plugin;
    }
}