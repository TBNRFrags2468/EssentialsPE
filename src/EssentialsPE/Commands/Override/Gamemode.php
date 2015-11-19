<?php
namespace EssentialsPE\Commands\Override;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Gamemode extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "gamemode", "Change player gamemode", "<mode> [player]", true, ["gm", "gma", "gmc", "gms", "gmt", "adventure", "creative", "survival", "spectator", "viewer"]);
        $this->setPermission("essentials.gamemode");
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
        if(strtolower($alias) !== "gamemode" && strtolower($alias) !== "gm"){
            if(isset($args[0])){
                $args[1] = $args[0];
                unset($args[0]);
            }
            switch(strtolower($alias)){
                case "survival":
                case "gms":
                    $args[0] = Player::SURVIVAL;
                    break;
                case "creative":
                case "gmc":
                    $args[0] = Player::CREATIVE;
                    break;
                case "adventure":
                case "gma":
                    $args[0] = Player::ADVENTURE;
                    break;
                case "spectator":
                case "viewer":
                case "gmt":
                    $args[0] = Player::SPECTATOR;
                    break;
                default:
                    return false;
                    break;
            }
        }
        if(count($args) < 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $sender) instanceof Player && !isset($args[1])){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(isset($args[1])){
            $player = $this->getAPI()->getPlayer($args[1]);
            if(!$player){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                return false;
            }
        }

        /**
         * The following switch is applied when the user execute:
         * /gamemode <MODE>
         */
        if(is_numeric($args[0])){
            switch($args[0]){
                case Player::SURVIVAL:
                case Player::CREATIVE:
                case Player::ADVENTURE:
                case Player::SPECTATOR:
                    $gm = $args[0];
                    break;
                default:
                    $sender->sendMessage(TextFormat::RED . "[Error] Please specify a valid gamemode");
                    return false;
                    break;
            }
        }else{
            switch(strtolower($args[0])){
                case "survival":
                case "s":
                    $gm = Player::SURVIVAL;
                    break;
                case "creative":
                case "c":
                    $gm = Player::CREATIVE;
                    break;
                case "adventure":
                case "a":
                    $gm = Player::ADVENTURE;
                    break;
                case "spectator":
                case "viewer":
                case "view":
                case "v":
                case "t":
                    $gm = Player::SPECTATOR;
                    break;
                default:
                    $sender->sendMessage(TextFormat::RED . "[Error] Please specify a valid gamemode");
                    return false;
                    break;
            }
        }
        $gmstring = $this->getAPI()->getServer()->getGamemodeString($gm);
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

    public function sendUsage(CommandSender $sender, $alias){
        $usage = $this->usageMessage;
        if($alias !== "gamemode" && $alias !== "gm"){
            $usage = str_replace("<mode> ", "", $usage);
        }
        if(!$sender instanceof Player){
            $usage = str_replace("[player]", "<player>", $usage);
        }
        $sender->sendMessage(TextFormat::RED . "Usage: " . TextFormat::GRAY . "/$alias $usage");
    }
} 
