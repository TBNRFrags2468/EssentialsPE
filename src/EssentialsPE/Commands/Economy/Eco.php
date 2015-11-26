<?php
namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;

class Eco extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "eco", "Sets the balance of a player", "<add|give|reset|set|take> <player> [amount]", true, ["economy"]);
        $this->setPermission("essentials.eco.use");
    }

    /**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) < 2 || count($args) > 3){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getAPI()->getPlayer($args[1]))){
            $this->sendMessage($sender, "error.playernotfound");
            return false;
        }
        if((!isset($args[2]) && strtolower($args[0]) !== "reset") || (isset($args[2]) && !is_numeric($args[2]))){
            $this->sendMessage($sender, "error.economy.amount");
            return false;
        }
        $balance = (int) $args[2];
        switch(strtolower($args[0])){
            case "give":
            case "add":
                $this->sendMessage($sender, "economy.balance.add");
                $this->getAPI()->addToPlayerBalance($player, $balance);
                break;
            case "reset":
                $this->sendMessage($sender, "economy.balance.reset");
                $this->getAPI()->setPlayerBalance($player, $this->getAPI()->getDefaultBalance());
                break;
            case "set":
                $this->sendMessage($sender, "economy.balance.set");
                $this->getAPI()->setPlayerBalance($player, $balance);
                break;
            case "take":
                $this->sendMessage($sender, "economy.balance.take");
                $this->getAPI()->addToPlayerBalance($player, -$balance);
                break;
        }
        return true;
    }
}