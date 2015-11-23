<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ItemDB extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "itemdb", "Display the information attached to the item you hold", "[name|id|meta]", false, ["itemno", "durability", "dura"]);
        $this->setPermission("essentials.itemdb");
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
        if(!$sender instanceof Player || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $item = $sender->getInventory()->getItemInHand();
        $m = TextFormat::AQUA . "This item " . ($this->getAPI()->isRepairable($item) ? "has " . TextFormat::RED . $item->getDamage() . TextFormat::AQUA . " points of damage" : "metadata is " . TextFormat::RED . $item->getDamage());
        if(isset($args[0])){
            switch(strtolower($args[0])){
                case "name":
                    $m = TextFormat::AQUA . "This item is named: " . TextFormat::RED . $item->getName();
                    break;
                case "id":
                    $m = TextFormat::AQUA . "This item ID is: " . TextFormat::RED . $item->getID();
                    break;
                case "durability":
                case "dura":
                case "metadata":
                case "meta":
                    $m = TextFormat::AQUA . "This item " . ($this->getAPI()->isRepairable($item) ? "has " . TextFormat::RED . $item->getDamage() . TextFormat::AQUA . " points of damage" : "metadata is " . TextFormat::RED . $item->getDamage());
                    break;
            }
        }
        $sender->sendMessage($m);
        return true;
    }
} 