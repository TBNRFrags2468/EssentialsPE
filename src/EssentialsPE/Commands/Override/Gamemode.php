<?php
namespace EssentialsPE\Commands\Override;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Gamemode extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "gamemode", "Change player gamemode", "/gamemode <mode> [player]", ["gm", "gma", "gmc", "gms", "gmt", "adventure", "creative", "survival", "spectator"]);
        $this->setPermission("essentials.gamemode");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        $gm = strtolower($alias);
        switch(count($args)){
            case 0:
                //TODO Check alias
                break;
            case 1:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /gamemode <mode> <player>");
                    return false;
                }
                if($gm === "gamemode"){
                    switch(strtolower($args[1])){
                        case "adventure":
                        case "a":
                            $gm = 3;
                            break;
                        case "cretive":
                        case "c":
                            $gm = 1;
                            break;
                        case "survival":
                        case "s":
                            $gm = 2;
                            break;
                        case "spectator":
                        case "t":
                            $gm = 4;
                            break;
                        default:
                            $sender->sendMessage(TextFormat::RED . "[Error] Invalid gamemode");
                            return false;
                            break;
                    }
                }
                //TODO Cancel if player is already in that mode & launch event
                $sender->setGamemode($gm);
                break;
            case 2:
                $player = $this->getPlugin()->getPlayer($args[1]);
                if($player === false){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                //TODO Check gamemode and set it, cancel if player is already in that mode & launch event
                break;
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? $this->getUsage() : "Usage: /gamemode <mode> <player>"));
                return false;
                break;
        }
        //TODO Send changing gamemode messages
        return true;
    }
} 