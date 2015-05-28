<?php
namespace EssentialsPE\Tasks\AFK;

use EssentialsPE\BaseFiles\BaseTask;
use EssentialsPE\Loader;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class AFKKickTask extends BaseTask{
    /** @var Player  */
    protected $player;

    public function __construct(Loader $plugin, Player $player){
        parent::__construct($plugin);
        $this->player = $player;
    }

    public function onRun($currentTick){
        $this->getPlugin()->getServer()->getLogger()->debug(TextFormat::YELLOW . "Running EssentialsPE's AFKKickTask");
        if($this->getPlugin()->isAFK($this->player) && !$this->player->hasPermission("essentials.afk.kickexempt") && time() - $this->getPlugin()->getLastPlayerMovement($this->player) >= $this->getPlugin()->getConfig()->getNested("afk.auto-set")){
            $this->player->kick("You have been kicked for idling more than " . (($time = floor($this->getPlugin()->getConfig()->get("auto-afk-kick"))) / 60 >= 1 ? ($time / 60) . " minutes" : $time . " seconds"));
        }
    }
} 