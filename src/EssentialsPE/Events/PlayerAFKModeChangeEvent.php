<?php
namespace EssentialsPE\Events;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCustomEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerAFKModeChangeEvent extends BaseCustomEvent implements Cancellable{
    public static $handlerList = null;

    /** @var Player */
    protected $player;
    /** @var bool */
    protected $isAFK;
    /** @var bool */
    protected $mode;
    /** @var bool */
    protected $broadcast;

    /**
     * @param BaseAPI $api
     * @param Player $player
     * @param bool $mode
     * @param bool $broadcast
     */
    public function __construct(BaseAPI $api, Player $player, $mode, $broadcast){
        parent::__construct($api);
        $this->player = $player;
        $this->isAFK = $api->isAFK($player);
        $this->mode = $mode;
        $this->broadcast = $broadcast;
    }

    /**
     * Return the player to be used
     *
     * @return Player
     */
    public function getPlayer(){
        return $this->player;
    }

    /**
     * Tell if the player is already AFK or not
     *
     * @return bool
     */
    public function isAFK(){
        return $this->isAFK;
    }

    /**
     * Tell the mode will to be set
     *
     * @return bool
     */
    public function getAFKMode(){
        return $this->mode;
    }

    /**
     * Change the mode to be set
     * false = Player will not be AFK
     * true = Player will be AFK
     *
     * @param bool $mode
     */
    public function setAFKMode($mode){
        if(is_bool($mode)){
            $this->mode = $mode;
        }
    }

    /**
     * Tell if the AFK status will be broadcast
     *
     * @return bool
     */
    public function getBroadcast(){
        return $this->broadcast;
    }

    /**
     * Specify if the AFK status will be broadcast
     *
     * @param bool $mode
     */
    public function setBroadcast($mode){
        $this->broadcast = $mode;
    }
} 