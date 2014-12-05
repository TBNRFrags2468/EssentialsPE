<?php
namespace EssentialsPE\Commands\Home;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Home extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "home", "Teleport to your home", "/home <name>", ["homes"]);
        $this->setPermission("essentials.home");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please use this command in-game");
            return false;
        }
        if(count($args) > 1){
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
            return false;
        }
        if($alias === "homes" || count($args) === 0){
            if(($list = $this->getPlugin()->homesList($sender, false)) === false){
                $sender->sendMessage(TextFormat::AQUA . "You don't have any home yet");
                return false;
            }
            $sender->sendMessage(TextFormat::AQUA . "Available homes:\n" . $list);
            return true;
        }
        $home = $this->getPlugin()->getHome($sender, $args[0]);
        if(!$home){
            $sender->sendMessage(TextFormat::RED . "[Error] Home doesn't exists");
            return false;
        }
        $sender->teleport($home[0], $home[1], $home[2]);
        $sender->sendMessage(TextFormat::GREEN . "Teleporting to home " . TextFormat::AQUA . $args[0] . TextFormat::GREEN . "...");
        return true;
    }
} 