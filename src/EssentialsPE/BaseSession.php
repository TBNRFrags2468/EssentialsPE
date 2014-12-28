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

    /**
     *            ______ _  __
     *      /\   |  ____| |/ /
     *     /  \  | |__  | ' /
     *    / /\ \ |  __| |  <
     *   / ____ \| |    | . \
     *  /_/    \_|_|    |_|\_\
     */

    public $isAFK = false;
    public $kickAFK = null;
    public $autoAFK = null;



    /**  ____             _
     *  |  _ \           | |
     *  | |_) | __ _  ___| | __
     *  |  _ < / _` |/ __| |/ /
     *  | |_) | (_| | (__|   <
     *  |____/ \__,_|\___|_|\_\
     */

    public $lastPosition = null;
    public $lastRotation = null;

    /**   _____           _
     *   / ____|         | |
     *  | |  __  ___   __| |
     *  | | |_ |/ _ \ / _` |
     *  | |__| | (_) | (_| |
     *   \_____|\___/ \__,_|
     */

    public $isGod = false;

    /**  _____                    _______          _
     *  |  __ \                  |__   __|        | |
     *  | |__) _____      _____ _ __| | ___   ___ | |
     *  |  ___/ _ \ \ /\ / / _ | '__| |/ _ \ / _ \| |
     *  | |  | (_) \ V  V |  __| |  | | (_) | (_) | |
     *  |_|   \___/ \_/\_/ \___|_|  |_|\___/ \___/|_|
     */

    public $ptCommands = false;
    public $ptChatMacro = false;

    /**  _____        _____
     *  |  __ \      |  __ \
     *  | |__) __   _| |__) |
     *  |  ___/\ \ / |  ___/
     *  | |     \ V /| |
     *  |_|      \_/ |_|
     */

    public $isPvPEnabled = true;

    /**  _______ _____  _____                           _
     *  |__   __|  __ \|  __ \                         | |
     *     | |  | |__) | |__) |___  __ _ _   _  ___ ___| |_ ___
     *     | |  |  ___/|  _  // _ \/ _` | | | |/ _ / __| __/ __|
     *     | |  | |    | | \ |  __| (_| | |_| |  __\__ | |_\__ \
     *     |_|  |_|    |_|  \_\___|\__, |\__,_|\___|___/\__|___/
     *                                | |
     *                                |_|
     */

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

    /**  _    _       _ _           _ _           _   _____ _
     *  | |  | |     | (_)         (_| |         | | |_   _| |
     *  | |  | |_ __ | |_ _ __ ___  _| |_ ___  __| |   | | | |_ ___ _ __ ___  ___
     *  | |  | | '_ \| | | '_ ` _ \| | __/ _ \/ _` |   | | | __/ _ | '_ ` _ \/ __|
     *  | |__| | | | | | | | | | | | | ||  __| (_| |  _| |_| ||  __| | | | | \__ \
     *   \____/|_| |_|_|_|_| |_| |_|_|\__\___|\__,_| |_____|\__\___|_| |_| |_|___/
     */

    public $isUnlimitedEnabled = false;

    /** __      __         _     _
     *  \ \    / /        (_)   | |
     *   \ \  / __ _ _ __  _ ___| |__
     *    \ \/ / _` | '_ \| / __| '_ \
     *     \  | (_| | | | | \__ | | | |
     *      \/ \__,_|_| |_|_|___|_| |_|
     */

    public $isVanished = false;
}