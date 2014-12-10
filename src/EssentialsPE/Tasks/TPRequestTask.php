<?php
namespace EssentialsPE\Tasks;

use EssentialsPE\Loader;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;

class TPRequestTask extends PluginTask{
    /** @var Player  */
    protected $requester;
    /** @var Loader  */
    protected $plugin;

    public function __construct(Loader $plugin, Player $requester){
        parent::__construct($plugin);
        $this->plugin = $plugin;
        $this->$requester = $requester;
    }

    public function onRun($currentTick){
        $this->owner->getServer()->getLogger()->debug(TextFormat::RED . "Running EssentialsPE's TPRequestTask");
        $this->plugin->removeTPRequest($this->requester);
    }
} 