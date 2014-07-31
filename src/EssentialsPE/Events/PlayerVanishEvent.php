<?php
namespace EssentialsPE\Events;

use EssentialsPE\BaseEvent;
use EssentialsPE\Loader;
use pocketmine\Player;

class PlayerVanishEvent extends BaseEvent{
    public static  $handlerList = null;

    /** @var \pocketmine\Player  */
    public $player;
    /** @var bool  */
    public $isVanished;
    /** @var  bool */
    public $willVanish;

    /**
     * @param Loader $plugin
     * @param Player $player
     * @param $willVanish
     */
    public function __construct(Loader $plugin, Player $player, $willVanish){
        parent::__construct($plugin);
        $this->player = $player;
        $this->isVanished = $plugin->isVanished($player);
        $this->willVanish = $willVanish;
    }

    /**
     * @return Player
     */
    public function getPlayer(){
        return $this->player;
    }

    /**
     * @return bool
     */
    public function isVanished(){
        return $this->isVanished;
    }

    /**
     * @return bool
     */
    public function willVanish(){
        return $this->willVanish;
    }

    /**
     * @param $value
     */
    public function setVanished($value){
        if(is_bool($value)){
            $this->willVanish = $value;
        }
    }
} 