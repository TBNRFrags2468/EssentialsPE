<?php
namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPAHere extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "tpahere", "Request a player to teleport to your position", "/tpahere <pllayer>");
        $this->setPermission("essentials.tpahere");
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
        $this->getPlugin()->removeTPRequest($player);

        $player = $this->getPlugin()->getPlayer($args[0]);
        if(!$player){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        $this->getPlugin()->requestTPHere($sender, $player);
        $player->sendMessage(TextFormat::AQUA . $sender->getName() . TextFormat::GREEN . " wants you to teleport to him, please use:\n/tpaccept to accepts the request\n/tpdeny to decline the invitation");
        $sender->sendMessage(TextFormat::GREEN . "Teleport request sent to " . $args[0] . "!");
        return true;
    }
} 