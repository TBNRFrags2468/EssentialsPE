<?php
namespace EssentialsPE\Tasks;

use EssentialsPE\Loader;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;

class TPRequestTask extends PluginTask{
    /** @var Player  */
    protected $requester;
    /** @var Loader  */
    protected $plugin;

    public function __construct(Loader $plugin, Player $requester){
        $this->plugin = $plugin;
        $this->$requester = $requester;
    }

    public function onRun($currentTick){
        $this->plugin->removeTPRequest($this->requester);
    }
} 