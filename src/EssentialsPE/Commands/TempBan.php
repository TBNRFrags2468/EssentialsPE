<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\permission\BanEntry;
use pocketmine\utils\TextFormat;

class TempBan extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "tempban", "Temporary bans the specified player", "/tempban <player> <time>");
        $this->setPermission("essentials.command.tempban");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) < 2){
            $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            return false;
        }
        $player = $this->getAPI()->getPlayer(array_shift($args));
        if($player === false){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        $seconds = 0; // TODO
        while(preg_match("#\\A([0-9\\.]{1,}(y|mo|w|d|h|m|s)\\Z#i", array_shift($args), $match)){
            $match = $match[0]; // TODO check if the flag is wrong
            if(!is_numeric($match[1])){
                break;
            }
            switch(strtolower($match[2])){
                // TODO
            }
        }
//        $reason = ""; // TODO
        $date = new \DateTime;
        $date->setTimestamp(time() + $seconds);
        $ban = new BanEntry($player->getName());
        $ban->setExpires($date);
//        $ban->setReason($reason);
        $this->getPlugin()->getServer()->getNameBans()->add($ban);
        return true;
    }
}
