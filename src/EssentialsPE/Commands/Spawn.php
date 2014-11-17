<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Spawn extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "spawn", "Teleport to server's main spawn", "/spawn [player]");
        $this->setPermission("essentials.spawn");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /spawn <player>");
                    return false;
                }
                $sender->setPosition($sender->getServer()->getDefaultLevel()->getSpawnLocation());
                $sender->sendMessage(TextFormat::GREEN . "Teleporting...");
                break;
            case 1:
                $player = $this->getPlugin()->getPlayer($args[0]);
                if($player === false){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $player->setPosition($sender->getServer()->getDefaultLevel()->getSpawnLocation());
                $player->sendMessage(TextFormat::GREEN . "Teleporting...");
                break;
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? $this->getUsage() : "Usage: /spawn <player>"));
                return false;
                break;
        }
        return true;
    }
} 