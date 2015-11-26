<?php
namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;

class SetWorth extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "setworth", "Sets the worth of the item you're holding", "<worth>", false);
        $this->setPermission("essentials.setworth");
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
        if(!$sender instanceof Player || count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }elseif(!is_numeric($args[0]) || (int) $args[0] < 0){
            $this->sendMessage($sender, "error.economy.worth.invalid");
            return false;
        }elseif(($id = $sender->getInventory()->getItemInHand()->getId()) === Item::AIR){
            $this->sendMessage($sender, "error.item.invalid");
            return false;
        }
        $this->sendMessage($sender, "economy.worth.set");
        $this->getAPI()->setItemWorth($id, (int) $args[0]);
        return true;
    }
}