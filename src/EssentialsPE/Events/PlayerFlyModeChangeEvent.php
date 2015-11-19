<?php
namespace EssentialsPE\Events;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCustomEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerFlyModeChangeEvent extends BaseCustomEvent implements Cancellable{
    public static $handlerList = null;

    /** @var Player */
    protected $player;
    /** @var bool */
    protected $isFlying;
    /** @var bool */
    protected $mode;

    /**
     * @param BaseAPI $api
     * @param Player $player
     * @param bool $mode
     */
    public function __construct(BaseAPI $api, Player $player, $mode){
        parent::__construct($api);
        $this->player = $player;
        $this->isFlying = $api->canFly($player);
        $this->mode = $mode;
    }

    /**
     * The player to work over
     *
     * @return Player
     */
    public function getPlayer(){
        return $this->player;
    }

    /**
     * The current "flying" status of the player
     *
     * @return bool
     */
    public function getCanFly(){
        return $this->isFlying;
    }

    /**
     * The "flying" status to set
     *
     * @return bool
     */
    public function willFly(){
        return $this->mode;
    }

    /**
     * Modify the "flying" status to be set
     *
     * @param bool $mode
     */
    public function setCanFly($mode){
        $this->mode = $mode;
    }
}