<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Hat extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "hat", "Get some new cool headgear", "[remove]", false, ["head"]);
        $this->setPermission("essentials.hat");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $remove = false;
        if(isset($args[0])){
            if($args[0] === "remove"){
                $remove = true;
            }else{
                $this->sendUsage($sender, $alias);
                return false;
            }
        }
        if($remove){
            $hat = $sender->getInventory()->getHelmet();
            if($sender->getInventory()->canAddItem($hat)){
                $sender->getInventory()->setItem($sender->getInventory()->firstEmpty(), $hat);
            }
            $sender->getInventory()->setHelmet(Item::get(Item::AIR));
        }else{
            $hat = $sender->getInventory()->getItemInHand();
            if($hat->getId() === 0){
                $sender->sendMessage(TextFormat::RED . "[Error] Please specify an item to wear");
                return false;
            }
            $sender->getInventory()->setItemInHand(Item::get(Item::AIR));
            $sender->getInventory()->setHelmet($hat);
            $sender->sendMessage(TextFormat::AQUA . "You got a new hat!");
        }
        return true;
    }
}