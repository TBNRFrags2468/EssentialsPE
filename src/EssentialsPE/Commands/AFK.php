<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class AFK extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "afk", "Toggle the \"Away From the Keyboard\" status", "/afk [player]", ["away"]);
        $this->setPermission("essentials.afk");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /afk <player>");
                    return false;
                }
                $this->getPlugin()->switchAFKMode($sender, true);
                break;
            case 1:
                if(!$sender->hasPermission("essentials.afk.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                $player = $this->getPlugin()->getPlayer($args[0]);
                if(!$player){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $this->getPlugin()->switchAFKMode($player, true);
                break;
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? $this->getUsage() : "Usage: /afk <player>"));
                return false;
                break;
        }
        return true;
    }
} 