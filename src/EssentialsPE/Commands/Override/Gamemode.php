<?php
namespace EssentialsPE\Commands\Override;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Gamemode extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "gamemode", "Change player gamemode", "/gamemode <mode> [player]", ["gm", "gma", "gmc", "gms", "gmt", "adventure", "creative", "survival", "spectator", "viewer"]);
        $this->setPermission("essentials.gamemode");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(strtolower($alias) !== "gamemode" && strtolower($alias) !== "gm"){
            if(isset($args[0])){
                $args[1] = $args[0];
            }
            switch(strtolower($alias)){
                case "survival":
                case "gms":
                    $args[0] = "survival";
                    break;
                case "creative":
                case "gmc":
                    $args[0] = "creative";
                    break;
                case "adventure":
                case "gma":
                    $args[0] = "adventure";
                    break;
                case "spectator":
                case "viewer":
                case "gmt":
                    $args[0] = "spectator";
                    break;
                default:
                    return false;
                    break;
            }
        }
        $player = $sender;
        if(!$player instanceof Player && !isset($args[1])){
            $player->sendMessage(TextFormat::RED . "Usage: /gamemode <mode> <player>");
            return false;
        }
        if(isset($args[1])){
            $player = $this->getPlugin()->getPlayer($args[1]);
            if(!$player){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                return false;
            }
        }

        /**
         * The following switch is applied when the user execute:
         * /gamemode <MODE>
         */
        if(!isset($args[0])){
            $sender->sendMessage(TextFormat::RED . "[Error] Please specify a valid gamemode");
            return false;
        }else{
            switch(strtolower($args[0])){
                case 0:
                case "survival":
                case "s":
                    $gm = 0;
                    break;
                case 1:
                case "creative":
                case "c":
                    $gm = 1;
                    break;
                case 2:
                case "adventure":
                case "a":
                    $gm = 2;
                    break;
                case 3:
                case "spectator":
                case "viewer":
                case "view":
                case "v":
                case "t":
                    $gm = 3;
                    break;
                default:
                    $sender->sendMessage(TextFormat::RED . "[Error] Please specify a valid gamemode");
                    return false;
                    break;
            }
        }
        $gmstring = $this->getPlugin()->getServer()->getGamemodeString($gm);
        if($player->getGamemode() === $gm){
            $player->sendMessage(TextFormat::RED . "[Error] " . ($player === $sender ? "You're" : $args[1] . " is") . " already in " . $gmstring . " mode");
            return false;
        }
        if($player !== $sender){
            $sender->sendMessage(TextFormat::GREEN . $args[1] . " is now in " . $gmstring . " mode");
        }
        $player->setGamemode($gm);
        return true;
    }
} 
