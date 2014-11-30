<?php
namespace EssentialsPE\Commands\Override;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
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
        $gm = 0;
        switch(count($args)){
            case 0:
                case "gma":
                case "adventure":
                    $gm = 2;
                    break;
                case "gmc":
                case "creative":
                    $gm = 1;
                    break;
                case "gms":
                case "survival":
                    $gm = 0;
                    break;
                case "gmt":
                case "spectator":
                case "viewer":
                    $gm = 3;
                    break;
            case 1:
            case 2:
                if(!$sender instanceof Player && !isset($arg[1])){
                    $sender->sendMessage(TextFormat::RED . "Usage: /gamemode <mode> <player>");
                    return false;
                }
                switch($alias){
                    case "gamemode":
                    case "gm":
                        switch(strtolower($args[0])){
                            case "adventure":
                            case "a":
                            case 2:
                                $gm = 2;
                                break;
                            case "cretive":
                            case "c":
                            case 1:
                                $gm = 1;
                                break;
                            case "survival":
                            case "s":
                            case 0:
                                $gm = 0;
                                break;
                            case "spectator":
                            case "t":
                            case 3:
                                $gm = 3;
                                break;
                            default:
                                $sender->sendMessage(TextFormat::RED . "[Error] Invalid gamemode");
                                return false;
                                break;
                        }
                        break;
                }
                break;
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? $this->getUsage() : "Usage: /gamemode <mode> <player>"));
                return false;
                break;
        }
        $gmstring = strtolower($sender->getServer()->getGamemodeString($gm));
        if($alias === "gm" || $alias === "gamemode"){
            $arg = 1;
        }else{
            $arg = 0;
        }
        if(isset($args[$arg])){
            $player = $this->getPlugin()->getPlayer($args[$arg]);
            if(!$player){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                return false;
            }
            if($player->getGamemode() !== $gm){
                $sender->getServer()->broadcast(TextFormat::GREEN . "Setting " . $args[$arg] . (substr($args[$arg], -1, 1) === "s" ? "'" : "'s") . " gamemode to " . $gmstring, Server::BROADCAST_CHANNEL_ADMINISTRATIVE);
                $player->sendMessage("Changing your gamemode to " . $gmstring);
                $player->setGamemode($gm);
            }else{
                $sender->sendMessage(TextFormat::RED . "[Error] " . $args[$arg] . " is already on " . $sender->getServer()->getGamemodeString($gm) . " mode");
            }
        }else{
            if($sender->getGamemode() !== $gm){
                $this->broadcastCommandMessage($sender, "Set own gamemode to " . $gmstring . " mode");
                $sender->sendMessage("Changing your gamemode to " . $gmstring . " mode");
                $sender->setGamemode($gm);
            }
        }
        return true;
    }
} 