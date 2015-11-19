<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Near extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "near", "List the players near to you", "[player]", true, ["nearby"]);
        $this->setPermission("essentials.near.use");
    }

    /**
     * @param CommandSender $sender
     * @param string $alias
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $this->sendUsage($sender, $alias);
                    return false;
                }
                $sender->sendMessage($this->broadcastPlayers($sender, "you"));
                break;
            case 1:
                if(!$sender->hasPermission("essentials.near.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                if(!($player = $this->getAPI()->getPlayer($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $sender->sendMessage($this->broadcastPlayers($player, $player->getDisplayName()));
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }

    /**
     * @param Player $player
     * @param string $who
     * @return string
     */
    private function broadcastPlayers(Player $player, $who){
        if(count($near = $this->getAPI()->getNearPlayers($player)) < 1){
            $msg = TextFormat::GRAY . "** There are no players near to " . $who . "! **";
        }else{
            $msg = TextFormat::YELLOW . "** There " . (count($near) > 1 ? "are " : "is ") . TextFormat::AQUA . count($near) . TextFormat::YELLOW . "player" . (count($near) > 1 ? "s " : " ") . "near to " . $who . ":";
            foreach($near as $p){
                $msg .= TextFormat::YELLOW . "\n* " . TextFormat::LIGHT_PURPLE . $p->getDisplayName();
            }
        }
        return $msg;
    }
} 