<?php
namespace EssentialsPE;

class BaseSession {

    /**
     * @param array $values
     */
    public function __construct($values = []){
        if(count($values) > 0){
            //AFK mode
            $this->isAFK = $values["isAFK"];
            $this->kickAFK = $values["kickAFK"];
            $this->autoAFK = $values["autoAFK"];
            //Back
            $this->lastPosition = $values["lastPosition"];
            $this->lastRotation = $values["lastRotation"];
            //God mode
            $this->isGod = $values["isGod"];
            //PowerTool
            $this->ptCommands = $values["ptCommands"];
            $this->ptChatMacro = $values["ptChatMacros"];
            //Player vs Player
            $this->isPvPEnabled = $values["isPvPEnabled"];
            //Teleport Requests
            $this->requestTo = $values["requestTo"];
            $this->requestToAction = $values["requestToAction"];
            $this->requestToTask = $values["requestToTask"];
            $this->latestRequestFrom = $values["latestRequestFrom"];
            $this->requestsFrom = $values["requestsFrom"];
            //Unlimited mode
            $this->isUnlimitedEnabled = $values["isUnlimitedEnabled"];
            //Vanish mode
            $this->isVanished = $values["isVanished"];
        }
    }

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
    public $ptChatMacro = false;

    //Player vs Player (PvP)
    public $isPvPEnabled = true;

    //Teleport Requests
        //Request to:
        public $requestTo = false;
        public $requestToAction = false;
        public $requestToTask = null;

        //Requests from:
        public $latestRequestFrom = null;
        public $requestsFrom = [];
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
}