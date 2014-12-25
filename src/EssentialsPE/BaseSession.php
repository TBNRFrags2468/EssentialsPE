<?php
namespace EssentialsPE;

class BaseSession {
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
}