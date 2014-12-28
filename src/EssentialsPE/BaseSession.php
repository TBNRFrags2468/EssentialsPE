<?php
namespace EssentialsPE;

use pocketmine\level\Position;

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

    private $isAFK = false;
    private $kickAFK = null;
    private $autoAFK = null;

    /**
     * @return bool
     */
    public function isAFK(){
        return $this->isAFK;
    }

    /**
     * @param $mode
     * @return bool
     */
    public function setAFK($mode){
        if(!is_bool($mode)){
            return false;
        }
        $this->isAFK = $mode;
        return true;
    }

    /**
     * @return bool|int
     */
    public function getAFKKickTaskID(){
        if(!$this->isAFK()){
            return false;
        }
        return $this->kickAFK;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function setAFKKickTaskID($id){
        if(!is_int($id)){
            return false;
        }
        $this->kickAFK = $id;
        return true;
    }

    public function removeAFKKickTaskID(){
        $this->kickAFK = null;
    }


    /**  ____             _
     *  |  _ \           | |
     *  | |_) | __ _  ___| | __
     *  |  _ < / _` |/ __| |/ /
     *  | |_) | (_| | (__|   <
     *  |____/ \__,_|\___|_|\_\
     */

    private $lastPosition = null;
    private $lastRotation = null;

    /**
     * @return bool|Position
     */
    public function getLastPosition(){
        if(!$this->lastPosition instanceof Position){
            return false;
        }
        return $this->lastPosition;
    }

    /**
     * @return array|bool
     */
    public function getLastRotation(){
        if(!is_array($this->lastRotation) && count($this->lastRotation) !== 2){
            return false;
        }
        return $this->lastRotation;
    }

    /**
     * @param Position $position
     * @param $yaw
     * @param $pitch
     */
    public function setLastPosition(Position $position, $yaw, $pitch){
        $this->lastPosition = $position;
        $this->lastRotation = [$yaw, $pitch];
    }

    public function removeLastPosition(){
        $this->lastPosition = null;
        $this->lastRotation = null;
    }

    /**   _____           _
     *   / ____|         | |
     *  | |  __  ___   __| |
     *  | | |_ |/ _ \ / _` |
     *  | |__| | (_) | (_| |
     *   \_____|\___/ \__,_|
     */

    private $isGod = false;

    /**
     * @return bool
     */
    public function isGod(){
        return $this->isGod;
    }

    /**
     * @param bool $mode
     * @return bool
     */
    public function setGod($mode){
        if(!is_bool($mode)){
            return false;
        }
        $this->isGod = $mode;
        return true;
    }

    /**  _____                    _______          _
     *  |  __ \                  |__   __|        | |
     *  | |__) _____      _____ _ __| | ___   ___ | |
     *  |  ___/ _ \ \ /\ / / _ | '__| |/ _ \ / _ \| |
     *  | |  | (_) \ V  V |  __| |  | | (_) | (_) | |
     *  |_|   \___/ \_/\_/ \___|_|  |_|\___/ \___/|_|
     */

    private $ptCommands = false;
    private $ptChatMacro = false;

    /**
     * @return bool
     */
    public function isPowerToolEnabled(){
        if(!$this->ptCommands && !$this->ptChatMacro){
            return false;
        }
        return true;
    }

    /**
     * @param int $itemId
     * @param string $command
     * @return bool
     */
    public function setPowerToolItemCommand($itemId, $command){
        if(!is_int($itemId) || $itemId === 0){
            return false;
        }
        if(!is_array($this->ptCommands[$itemId])){
            $this->ptCommands[$itemId] = $command;
        }else{
            $this->ptCommands[$itemId][] = $command;
        }
        return true;
    }

    /**
     * @param int $itemId
     * @return bool
     */
    public function getPowerToolItemCommand($itemId){
        if(!is_int($itemId) || $itemId === 0 || (!isset($this->ptCommands[$itemId]) || is_array($this->ptCommands[$itemId]))){
            return false;
        }
        return $this->ptCommands[$itemId];
    }

    /**
     * @param int $itemId
     * @param array $commands
     * @return bool
     */
    public function setPowerToolItemCommands($itemId, array $commands){
        if(!is_int($itemId) || $itemId === 0 || count($commands) < 1){
            return false;
        }
        $this->ptCommands[$itemId] = $commands;
        return true;
    }

    /**
     * @param int $itemId
     * @return bool
     */
    public function getPowerToolItemCommands($itemId){
        if(!isset($this->ptCommands[$itemId]) || !is_array($this->ptCommands[$itemId])){
            return false;
        }
        return $this->ptCommands[$itemId];
    }

    /**
     * @param int $itemId
     * @param string $command
     */
    public function removePowerToolItemCommand($itemId, $command){
        $commands = $this->getPowerToolItemCommands($itemId);
        if(is_array($commands)){
            foreach($commands as $c){
                if(stripos(strtolower($c), strtolower($command)) !== false){
                    unset($c);
                }
            }
        }
    }

    /**
     * @param int $itemId
     * @param string $chat_message
     * @return bool
     */
    public function setPowerToolItemChatMacro($itemId, $chat_message){
        if(!is_int($itemId) || $itemId === 0){
            return false;
        }
        $chat_message = str_replace("\\n", "\n", $chat_message);
        $this->ptChatMacro[$itemId] = $chat_message;
        return true;
    }

    /**
     * @param int $itemId
     * @return bool
     */
    public function getPowerToolItemChatMacro($itemId){
        if(!is_int($itemId) || $itemId === 0 || !isset($this->ptChatMacro[$itemId])){
            return false;
        }
        return $this->ptChatMacro[$itemId];
    }

    /**
     * @param int $itemId
     */
    public function disablePowerToolItem($itemId){
        unset($this->ptCommands[$itemId]);
        unset($this->ptChatMacro[$itemId]);
    }

    public function disablePowerTool(){
        $this->ptCommands = false;
        $this->ptChatMacro = false;
    }

    /**  _____        _____
     *  |  __ \      |  __ \
     *  | |__) __   _| |__) |
     *  |  ___/\ \ / |  ___/
     *  | |     \ V /| |
     *  |_|      \_/ |_|
     */

    private $isPvPEnabled = true;

    /**
     * @return bool
     */
    public function isPVPEnabled(){
        return $this->isPvPEnabled;
    }

    /**
     * @param bool $mode
     * @return bool
     */
    public function setPvP($mode){
        if(!is_bool($mode)){
            return false;
        }
        $this->isPvPEnabled = $mode;
        return true;
    }

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
    private $requestTo = false;
    private $requestToAction = false;
    private $requestToTask = null;

    //Requests from:
    private $latestRequestFrom = null;
    private $requestsFrom = [];
    /** This is how it works per player:
    *
    * "iksaku" => "tpto"  <--- Type of request
    *    ^^^
    * Requester Name
    */

    //TODO

    /**  _    _       _ _           _ _           _   _____ _
     *  | |  | |     | (_)         (_| |         | | |_   _| |
     *  | |  | |_ __ | |_ _ __ ___  _| |_ ___  __| |   | | | |_ ___ _ __ ___  ___
     *  | |  | | '_ \| | | '_ ` _ \| | __/ _ \/ _` |   | | | __/ _ | '_ ` _ \/ __|
     *  | |__| | | | | | | | | | | | | ||  __| (_| |  _| |_| ||  __| | | | | \__ \
     *   \____/|_| |_|_|_|_| |_| |_|_|\__\___|\__,_| |_____|\__\___|_| |_| |_|___/
     */

    private $isUnlimitedEnabled = false;

    /**
     * @return bool
     */
    public function isUnlimitedEnabled(){
        return $this->isUnlimitedEnabled;
    }

    /**
     * @param bool $mode
     * @return bool
     */
    public function setUnlimited($mode){
        if(!is_bool($mode)){
            return false;
        }
        $this->isUnlimitedEnabled = $mode;
        return true;
    }

    /** __      __         _     _
     *  \ \    / /        (_)   | |
     *   \ \  / __ _ _ __  _ ___| |__
     *    \ \/ / _` | '_ \| / __| '_ \
     *     \  | (_| | | | | \__ | | | |
     *      \/ \__,_|_| |_|_|___|_| |_|
     */

    private $isVanished = false;

    /**
     * @return bool
     */
    public function isVanished(){
        return $this->isVanished;
    }

    /**
     * @param bool $mode
     * @return bool
     */
    public function setVanish($mode){
        if(!is_bool($mode)){
            return false;
        }
        $this->isVanished = $mode;
        return true;
    }
}