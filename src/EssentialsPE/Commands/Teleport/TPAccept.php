<?php
namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPAccept extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "tpaccept", "Accept a teleport request", "/tpaccept", ["tpyes"]);
        $this->setPermission("essentials.tpaccept");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!($sender instanceof Player)){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
            return false;
        }
        if(count($args) !== 0){
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
            return false;
        }
        if(!($request = $this->getPlugin()->hasARequest($sender))){
            $sender->sendMessage(TextFormat::RED . "[Error] You don't have any request yet");
            return false;
        }
        $player = $this->getPlugin()->getPlayer($request[0]);
        if(!$player){
            $sender->sendMessage(TextFormat::RED . "[Error] Request unavailable");
            return false;
        }
        $player->sendMessage(TextFormat::AQUA . $sender->getDisplayName() . TextFormat::GREEN . " accepted your teleport request! Teleporting...");
        $sender->sendMessage(TextFormat::GREEN . "Teleporting...");
        if($request[1] === "tpto"){
            $sender->teleport($player->getPosition(), $player->getYaw(), $player->getPitch());
        }else{
            $player->teleport($sender->getPosition(), $sender->getYaw(), $sender->getPitch());
        }
        return true;
    }
} 