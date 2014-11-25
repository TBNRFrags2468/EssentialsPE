<?php
namespace EssentialsPE\Commands\Warp;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class DelWarp extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "delwarp", "Delete a warp", "/delwarp <name>", ["remwarp", "removewarp", "closewarp"]);
        $this->setPermission("essentials.delwarp");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) !== 1){
            $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
            return false;
        }
        if(!$this->getPlugin()->warpExists($args[0])){
            $sender->sendMessage(TextFormat::RED . "[Error] Warp doesn't exists");
            return false;
        }
        if(!$sender->hasPermission("essentials.warp.override.*") && !$sender->hasPermission("essentials.warp.override.$args[0]")){
            $sender->sendMessage(TextFormat::RED . "[Error] You can't delete this warp");
            return false;
        }
        $this->getPlugin()->removeWarp($args[0]);
        $sender->sendMessage(TextFormat::GREEN . "Warp successfully removed!");
        return true;
    }
} 