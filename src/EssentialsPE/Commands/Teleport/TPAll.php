<?php
namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPAll extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "tpall", "Teleport all player to you or another player", "/tpall [player]");
        $this->setPermission("essentials.tpall");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /tpall <player>");
                    return false;
                }
                foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $p){
                    $p->teleport($sender->getPosition());
                    $p->sendMessage(TextFormat::YELLOW . "Teleporting to " . $sender->getDisplayName() . "...");
                }
                $sender->sendMessage(TextFormat::YELLOW . "Teleporting players to you...");
                break;
            case 1:
                $player = $this->getPlugin()->getPlayer($args[0]);
                if(!$player){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $p){
                    $p->teleport($player->getPosition());
                    $p->sendMessage(TextFormat::YELLOW . "Teleporting to " . $player->getDisplayName() . "...");
                }
                $player->sendMessage(TextFormat::YELLOW . "Teleporting players to you...");
                break;
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? $this->getUsage() : "Usage: /tpall <player>"));
                return false;
                break;
        }
        return true;
    }
} 