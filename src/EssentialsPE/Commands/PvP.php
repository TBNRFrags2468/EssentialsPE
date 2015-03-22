<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PvP extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "pvp", "Toggle PvP on/off", "/pvp <on|off>");
        $this->setPermission("essentials.pvp");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
            return false;
        }elseif(count($args) != 1){
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
            return false;
        }

        switch(strtolower($args[0])){
            case "on":
            case "off":
                $this->getPlugin()->setPvP($sender, (strtolower($args[0]) === "on" ? true : false));
                $sender->sendMessage(TextFormat::GREEN . "PvP " . (strtolower($args[0]) === "on" ? "enabled!" : "disabled!"));
                break;
            default:
                $sender->sendMessage(TextFormat::RED . $this->getUsage());
                return false;
                break;
        }
        return true;
    }
}
