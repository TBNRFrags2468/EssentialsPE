<?php
namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Worth extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "worth", "Get the price of an item", "[item]", "<item>");
        $this->setPermission("essentials.worth");
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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!isset($args[0])){
            $id = $sender->getInventory()->getItemInHand()->getId();
        }else{
            $id = $this->getAPI()->getItem($args[0])->getId();
        }
        if(!($worth = $this->getAPI()->getItemWorth($id))){
            $this->sendMessage($sender, "error.economy.worth.unknown");
            return false;
        }
        $this->sendMessage($sender, "economy.worth.get", $this->getAPI()->getCurrencySymbol() . $worth);
        return true;
    }
}