<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Near extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "near", "", "/near [radius|player {radius}]", ["nearby"]);
        $this->setPermission("essentials.near");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                if(!($sender instanceof Player)){
                    $sender->sendMessage(TextFormat::RED . "Usage: /near <player> [radius]");
                    return false;
                }
                $this->broadcastPlayers($sender, "you");
                break;
            case 1:
                if(!$sender->hasPermission("essentials.near.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                $player = $this->getPlugin()->getPlayer($args[0]);
                if(!$player){
                    if(!is_numeric($args[0]) && ($sender instanceof Player)){
                        $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                        return false;
                    }elseif(is_numeric($args[0]) && (!$sender instanceof Player)){
                        $sender->sendMessage(TextFormat::RED . "Usage: /near <player> [radius]");
                        return false;
                    }
                    $radius = $args[0];
                    $this->broadcastPlayers($sender, "you", $radius);
                }
                $this->broadcastPlayers($sender, $player->getDisplayName());
                break;
            case 2:
                if(!$sender->hasPermission("essentials.near.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                $player = $this->getPlugin()->getPlayer($args[0]);
                if($player === false){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $radius = $args[1];
                if(!is_numeric($radius)){
                    $sender->sendMessage(TextFormat::RED . "[Error] Invalid radius");
                    return false;
                }
                $this->broadcastPlayers($sender, $player->getDisplayName(), $radius);
                break;
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
                return false;
                break;
        }
        return true;
    }

    /**
     * @param CommandSender $player
     * @param string $who
     * @param int $radius
     */
    private function broadcastPlayers(CommandSender $player, $who, $radius = null){
        if($radius === null){
            $near = $this->getPlugin()->getNearPlayers($player);
        }else{
            $near = $this->getPlugin()->getNearPlayers($player, $radius);
        }
        if(count($near) <= 0){
            $msg = TextFormat::GRAY . "** There are no players near to $who! **";
        }else{
            $msg = TextFormat::YELLOW . "** There " . (count($near) > 1 ? "are " : "is ") . TextFormat::AQUA . count($near) . TextFormat::YELLOW . "player" . (count($near) > 1 ? "s " : " ") . "near to $who:";
            foreach($near as $p){
                $msg .= TextFormat::YELLOW . "\n* " . TextFormat::LIGHT_PURPLE . $p->getDisplayName();
            }
        }
        $player->sendMessage($msg);
    }
} 