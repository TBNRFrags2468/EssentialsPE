<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\level\Location;
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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("essentials.spawn.other")){
                $sender->sendMessage(TextFormat::RED . "[Error] You can't teleport other players to spawn");
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                return false;
            }
        }
        $player->teleport(Location::fromObject($this->getAPI()->getServer()->getDefaultLevel()->getSpawnLocation(), $this->getAPI()->getServer()->getDefaultLevel()));
        $player->sendMessage(TextFormat::GREEN . "Teleporting...");
        return true;
    }
} 
