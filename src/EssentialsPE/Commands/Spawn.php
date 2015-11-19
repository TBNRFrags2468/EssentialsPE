<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Spawn extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "spawn", "Teleport to server's main spawn", "[player]");
        $this->setPermission("essentials.spawn.use");
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
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $this->sendUsage($sender, $alias);
                    return false;
                }
                $sender->teleport($sender->getServer()->getDefaultLevel()->getSpawnLocation());
                $sender->sendMessage(TextFormat::GREEN . "Teleporting...");
                break;
            case 1:
                if(!$sender->hasPermission("essentials.spawn.other")){
                    $sender->sendMessage(TextFormat::RED . "[Error] You can't teleport another one to spawn");
                    return false;
                }
                if(!($player = $this->getAPI()->getPlayer($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $player->teleport($sender->getServer()->getDefaultLevel()->getSpawnLocation());
                $player->sendMessage(TextFormat::GREEN . "Teleporting...");
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
} 
