<?php
namespace EssentialsPE;

use EssentialsPE\Events\PlayerAFKModeChangeEvent;
use EssentialsPE\Tasks\AFKKickTask;
use pocketmine\Player;

class BaseSession {
    /**
     * For this, we should remove the ``setSession()`` function,
     * and only allow the usage of:
     * - ``createSession()``
     * - ``removeSession()``
     * and ``getSession()``
     */

    /** @var Player  */
    public $player;
    /** @var Loader  */
    public $plugin;

    //AFK mode
    public $isAFK = false;
    public $kickAFK = null;
    public $autoAFK = null;

    //Back
    public $lastPosition = null;
    public $lastRotation = null;

    //God mode
    public $isGod = false;

    //PowerTool
    public $ptCommands = false;
    public $ptChatMacros = false;

    //Player vs Player (PvP)
    public $isPvPEnabled = false;

    //Teleport Requests
        //Request to:
        public $requestTo = false;
        public $requestToAction = null;
        public $requestToTask = null;

        //Requests from:
        public $latestRequestFrom = null;
        public $requests = [];
        /** This is how it works per player:
         *
        * "iksaku" => "tpto"  <--- Type of request
        *    ^^^
        * Requester Name
        */

    //Unlimited mode
    public $isUnlimitedEnabled = false;

    //Vanish mode
    public $isVanished = false;

    /**
     * I'm not pretty sure about this, but an API re-write will be required for this.
     *
     * This may mean more organization for the API, but will break everything xD
     */

    /**
     * @return bool
     */
    public function isAFK(){
        return $this->isAFK();
    }

    /**
     * @param bool $state
     */
    public function setAFK($state){
        if(!is_bool($state)){
            return;
        }
        $this->plugin->getServer()->getPluginManager()->callEvent($ev = new PlayerAFKModeChangeEvent($this->plugin, $this->player, $state));
        if($ev->isCancelled()){
            return;
        }
        $this->isAFK = $ev->getAFKMode();

        if($ev->getAFKMode() === false && ($id = $this->getAFKKickTaskID()) !== false){
            $this->plugin->getServer()->getScheduler()->cancelTask($id);
            $this->kickAFK = null;
        }elseif($ev->getAFKMode() === true && $this->getAFKKickTaskTime() > 0 && !$this->player->hasPermission("essentials.afk.kickexempt")){
            $task = $this->plugin->getServer()->getScheduler()->scheduleDelayedTask(new AFKKickTask($this->plugin, $this->player), ($this->getAFKKickTaskTime() * 20));
            $this->setAFKKickTaskID($task->getTaskId());
        }
    }

    public function switchAFK(){
        $this->setAFK(($this->isAFK() ? false : true));
    }

    /**
     * @return bool
     */
    public function getAFKKickTaskID(){
        return ($this->kickAFK === null ? false : $this->kickAFK);
    }

    /**
     * @param int $id
     */
    public function setAFKKickTaskID($id){
        if(!is_int($id)){
            return;
        }
        $this->kickAFK = $id;
    }

    /**
     * @return bool|int
     */
    public function getAFKKickTaskTime(){
        return $this->plugin->getConfig()->get("auto-afk-kick");
    }


    //More...

    /**
     * @param Player $player
     * @param Loader $plugin
     */
    public function __construct(Player $player, Loader $plugin){
        $this->player = $player;
        $this->plugin = $plugin;
    }
}