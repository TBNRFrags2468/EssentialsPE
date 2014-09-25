<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Nuke extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "nuke", "Thin carpet of bomb", "/nuke [player]");
        $this->setPermission("essentials.nuke");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /nuke <player>");
                    return false;
                }
                $this->getPlugin()->nuke($sender);
                break;
            case 1:
                if(!$sender->hasPermission("essentials.nuke.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                $player = $this->getPlugin()->getPlayer($args[0]);
                if($player === false){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $this->getPlugin()->nuke($player);
                break;
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? $this->getUsage() : "Usage: /nuke <player>"));
                return false;
                break;
        }
        return true;
    }
} 