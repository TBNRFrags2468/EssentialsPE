<?php
namespace EssentialsPE\Tasks;

use EssentialsPE\Loader;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;

class AFKKickTask extends PluginTask{
    /** @var \pocketmine\Player  */
    protected $player;
    /** @var \EssentialsPE\Loader  */
    protected $plugin;

    public function __construct(Loader $plugin, Player $player){
        parent::__construct($plugin);
        $this->plugin = $plugin;
        $this->player = $player;
    }

    public function onRun($currentTick){
        if($this->plugin->isAFK($this->player) && !$this->player->hasPermission("essentials.afk.kickexempt")){
            $this->player->kick("You have been kicked for idling more than " . (($time = floor($this->plugin->getConfig()->get("auto-afk-kick"))) / 60 >= 1 ? ($time / 60) . " minutes" : $time . " seconds"));
        }
    }
} 