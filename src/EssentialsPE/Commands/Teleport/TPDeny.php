<?php
namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPDeny extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "tpdeny", "Decline a Teleport Request", "/tpdeny [player]", ["tpno"]);
        $this->setPermission("essentials.tpdeny");
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
        $request = $this->getPlugin()->hasARequest($sender);
        if(!$request){
            $sender->sendMessage(TextFormat::RED . "[Error] You don't have any request yet");
            return false;
        }
        $player = $this->getPlugin()->getPlayer($request[0]);
        if(!$player){
            $sender->sendMessage(TextFormat::RED . "[Error] Request unavailable");
            return false;
        }
        $player->sendMessage(TextFormat::AQUA . $sender->getDisplayName() . TextFormat::RED . " denied your teleport request");
        $sender->sendMessage(TextFormat::GREEN . "Denied " . TextFormat::AQUA . $player->getName() . (substr($player->getDisplayName(), -1, 1) === "s" ? "'" : "'s") . TextFormat::RED . " teleport request");
        $this->getPlugin()->removeTPRequest($player, $sender);
        return true;
    }
} 