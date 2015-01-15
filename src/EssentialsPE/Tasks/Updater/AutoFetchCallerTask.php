<?php
namespace EssentialsPE\Tasks\Updater;

use EssentialsPE\Loader;
use pocketmine\scheduler\PluginTask;

class AutoFetchCallerTask extends PluginTask{
    /** @var Loader */
    protected $plugin;

    public function __construct(Loader $plugin){
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }

    public function onRun($currentTick){
        $this->plugin->fetchEssentialsPEUpdate(false);
    }
}