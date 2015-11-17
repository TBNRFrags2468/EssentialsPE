<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Repair extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "repair", "Repair the item you're holding", "[all|hand]", false, ["fix"]);
        $this->setPermission("essentials.repair.use");
    }

    /**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $alias,  array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        switch(count($args)){
            case 0:
                if(!$this->getPlugin()->isRepairable($item = $sender->getInventory()->getItemInHand())){
                    $sender->sendMessage(TextFormat::RED . "[Error] This item can't be repaired!");
                    return false;
                }
                $item->setDamage(0);
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage(TextFormat::GREEN . "Item successfully repaired!");
                break;
            case 1:
                switch(strtolower($args[0])){
                    case "all":
                        if(!$sender->hasPermission("essentials.repair.all")){
                            $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                            return false;
                        }
                        foreach($sender->getInventory()->getContents() as $item){
                            if($this->getPlugin()->isRepairable($item)){
                                $item->setDamage(0);
                            }
                        }
                        $r = TextFormat::GREEN . "All the tools on your inventory were repaired!";
                        if($sender->hasPermission("essentials.repair.armor")){
                            foreach($sender->getInventory()->getArmorContents() as $item){
                                $item->setDamage(0);
                            }
                            $r .= TextFormat::AQUA . "\n(including the equipped Armor)";
                        }
                        $sender->sendMessage($r);
                        break;
                    case "hand":
                        if(!$this->getPlugin()->isRepairable($item = $sender->getInventory()->getItemInHand())){
                            $sender->sendMessage(TextFormat::RED . "[Error] This item can't be repaired!");
                            return false;
                        }
                        $item->setDamage(0);
                        $sender->sendMessage(TextFormat::GREEN . "Item successfully repaired!");
                        break;
                    default:
                        $this->sendUsage($sender, $alias);
                        return false;
                        break;
                }
        }
        return true;
    }
}
