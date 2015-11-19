<?php
namespace EssentialsPE\Commands\Warp;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Warp extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "warp", "Teleport to a warp", "[name] [player]", "[<name> <player>]", ["warps"]);
        $this->setPermission("essentials.warp.use");
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
        if(count($args) === 0){
            if(($list = $this->getAPI()->warpList(false)) === false){
                $sender->sendMessage(TextFormat::AQUA . "There are no Warps currently available");
                return false;
            }
            $sender->sendMessage(TextFormat::AQUA . "Available warps:\n" . $list);
            return true;
        }
        if(!($warp = $this->getAPI()->getWarp($args[0]))){
            $sender->sendMessage(TextFormat::RED . "[Error] Warp doesn't exist");
            return false;
        }
        switch(count($args)){
            case 1:
                if(!$sender instanceof Player){
                    $this->sendUsage($sender, $alias);
                    return false;
                }
                if(!$sender->hasPermission("essentials.warps.*") && !$sender->hasPermission("essentials.warps." . strtolower($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] You can't teleport to that warp");
                    return false;
                }
                $sender->teleport($warp);
                $sender->sendMessage(TextFormat::GREEN . "Warping to " . TextFormat::AQUA . $warp->getName() . "...");
                break;
            case 2:
                if(!$sender->hasPermission("essentials.warp.other")){
                    $sender->sendMessage(TextFormat::RED . "[Error] You can't warp other players");
                    return false;
                }
                if(!$sender->hasPermission("essentials.warps.*") && !$sender->hasPermission("essentials.warps." . strtolower($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] You can't teleport another one to that warp");
                    return false;
                }
                $player = $this->getAPI()->getPlayer($args[1]);
                if(!$player){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $player->teleport($warp);
                $player->sendMessage(TextFormat::GREEN . "Warping to " . TextFormat::AQUA . $warp->getName() . TextFormat::GREEN . "...");
                $sender->sendMessage(TextFormat::GREEN . "Warping " . TextFormat::YELLOW . $player->getDisplayName() . TextFormat::GREEN . " to " . TextFormat::AQUA . $warp->getName() . TextFormat::GREEN . "...");
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
} 
