<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
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
                $this->getPlugin()->switchAFKMode($sender);
                $sender->sendMessage(TextFormat::YELLOW . "You're " . ($this->getPlugin()->isAFK($sender) ? "now" : "no longer") . " AFK");
                $this->broadcastAFKStatus($sender);
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
                $this->getPlugin()->switchAFKMode($player);
                $player->sendMessage(TextFormat::YELLOW . "You're " . ($this->getPlugin()->isAFK($sender) ? "now" : "no longer") . " AFK");
                $this->broadcastAFKStatus($player);
                break;
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? $this->getUsage() : "Usage: /afk <player>"));
                return false;
                break;
        }
        return true;
    }

    private function broadcastAFKStatus(Player $player){
        $message =TextFormat::YELLOW . $player->getDisplayName() . TextFormat::GREEN . " is " . ($this->getPlugin()->isAFK($player) ? "now" : "no longer") . " AFK";
        $player->getServer()->getLogger()->info($message);
        foreach($player->getServer()->getOnlinePlayers() as $p){
            if($p !== $player){
                $p->sendMessage($message);
            }
        }
    }
} 