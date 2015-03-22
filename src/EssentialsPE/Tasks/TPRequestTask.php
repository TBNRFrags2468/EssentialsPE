<?php
namespace EssentialsPE\Tasks;

use EssentialsPE\BaseFiles\BaseTask;
use EssentialsPE\Loader;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPRequestTask extends BaseTask{
    /** @var Player  */
    protected $requester;

    public function __construct(Loader $plugin, Player $requester){
        parent::__construct($plugin);
        $this->requester = $requester;
    }

    public function onRun($currentTick){
        $this->getPlugin()->getServer()->getLogger()->debug(TextFormat::YELLOW . "Running EssentialsPE's TPRequestTask");
        $this->getPlugin()->removeTPRequest($this->requester);
    }
} 