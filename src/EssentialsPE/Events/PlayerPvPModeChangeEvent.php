<?php
namespace EssentialsPE\Events;

use EssentialsPE\BaseEvent;
use EssentialsPE\Loader;
use pocketmine\Player;

class PlayerPvPModeChangeEvent extends BaseEvent{
    public static $handlerList = null;

    /** @var \pocketmine\Player  */
    protected $player;
    /** @var bool  */
    protected $isEnabled;
    /** @var bool  */
    protected $mode;

    public function __construct(Loader $plugin, Player $player, $mode){
        parent::__construct($plugin);
        $this->player = $player;
        $this->isEnabled = $plugin->isPvPEnabled($player);
        $this->mode = $mode;
    }
} 