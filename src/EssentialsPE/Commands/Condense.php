<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Condense extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "condense", "Compact your inventory!", "[item name|id|hand|inventory|all]", false, ["compact", "toblocks"]);
        $this->setPermission("essentials.condense");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!isset($args[0])){
            $args[0] = "inventory";
        }
        switch($args[0]){
            case "hand":
                $target = $sender->getInventory()->getItemInHand();
                break;
            case "inventory":
            case "all":
                $target = null;
                break;
            default: // Item name|id
                $target = $this->getPlugin()->getItem($args[0]);
                if($target->getId() === 0){
                    $sender->sendMessage(TextFormat::RED . "Unknown item \"" . $args[0] . "\"");
                    return false;
                }
                break;
        }
        $this->getPlugin()->condenseItems($sender->getInventory(), $target);
        $sender->sendMessage(TextFormat::YELLOW . "Condensing items...");
        return true;
    }
}