<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Kit extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "kit", "Get a pre-defined kit!", "/kit [name] [player]", ["kits"]);
        $this->setPermission("essentials.kit");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if($alias === "kits" || count($args) === 0){
            if(($list = $this->getPlugin()->kitList(false)) === false){
                $sender->sendMessage(TextFormat::AQUA . "There are no Kits currently available");
                return false;
            }
            $sender->sendMessage(TextFormat::AQUA . "Available kits:\n" . $list);
            return true;
        }
        $kit = $this->getPlugin()->getKit($args[0]);
        if(!$kit){
            $sender->sendMessage(TextFormat::RED . "[Error] Kit doesn't exist");
            return false;
        }
        switch(count($args)){
            case 1:
                if(!$sender->hasPermission("essentials.kits." . strtolower($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] You can't get this kit");
                    return false;
                }
                $this->giveItems($sender, $kit);
                break;
            case 2:
                if(!$sender->hasPermission("essentials.kit.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                $player = $this->getPlugin()->getPlayer($args[1]);
                if(!$player){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                if(!$sender->hasPermission("essentials.kits." . strtolower($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] You can't get this kit");
                    return false;
                }
                $this->giveItems($player, $kit);
                break;
            default:
                $sender->sendMessage(TextFormat::RED . $this->getUsage());
                return false;
                break;
        }
        return true;
    }

    private function giveItems(Player $player, $kit){
        foreach($kit as $k){
            $k = explode(" ", $k);
            if(count($k) > 1){
                $amount = $k[1];
            }else{
                $amount = 1;
            }
            $item_name = $k[0];
            $item = $this->getPlugin()->getItem($item_name);
            if($item->getID() === 0) {
                return false;
            }
            $item->setCount($amount);
            $player->getInventory()->setItem($player->getInventory()->firstEmpty(), $item);
        }
        return true;
    }
}