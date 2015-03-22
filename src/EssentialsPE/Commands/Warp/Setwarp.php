<?php
namespace EssentialsPE\Commands\Warp;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Setwarp extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "setwarp", "Create a warp (or update it)", "/setwarp <name>", ["openwarp", "createwarp"]);
        $this->setPermission("essentials.setwarp");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
            return false;
        }
        if(count($args) !== 1){
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
            return false;
        }
        if($args[0] === null || $args[0] === "" || $args[0] === " "){
            $sender->sendMessage(TextFormat::RED . "[Error] Please provide a Warp name");
            return false;
        }
        $existed = $this->getPlugin()->warpExists($args[0]);
        if($existed && !$sender->hasPermission("essentials.warp.override.*") && !$sender->hasPermission("essentials.warp.override.$args[0]")){
            $sender->sendMessage(TextFormat::RED . "[Error] You can't modify this warp position");
            return false;
        }
        $this->getPlugin()->setWarp($args[0], $sender->getPosition(), $sender->getYaw(), $sender->getPitch());
        $sender->sendMessage(TextFormat::GREEN . "Warp successfully " . ($existed ? "updated!" : "created!"));
        return true;
    }
} 