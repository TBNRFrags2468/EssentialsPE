<?php
namespace EssentialsPE\Commands\Warp;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Warp extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "warp", "Teleport to a warp", "/warp <name> [player]", ["warps"]);
        $this->setPermission("essentials.warp");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if($alias === "warps" || count($args) === 0){
            if(($list = $this->getPlugin()->warpList(false)) === false){
                $sender->sendMessage(TextFormat::AQUA . "There are no Warps currently available");
                return false;
            }
            $sender->sendMessage(TextFormat::AQUA . "Available warps:\n" . $list);
            return true;
        }
        $warp = $this->getPlugin()->getWarp($args[0]);
        if(!$warp){
            $sender->sendMessage(TextFormat::RED . "[Error] Warp doesn't exists");
            return false;
        }
        switch(count($args)){
            case 1:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /warp <name> [player]");
                    return false;
                }
                if(!$sender->hasPermission("essentials.warps.*") && !$sender->hasPermission("essentials.warps." . strtolower($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] You can't teleport to that warp");
                    return false;
                }
                $sender->teleport($warp[0], $warp[1], $warp[2]);
                $sender->sendMessage(TextFormat::GREEN . "Warping to " . $args[0] . "...");
                break;
            case 2:
                $player = $this->getPlugin()->getPlayer($args[1]);
                if(!$player){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                if(!$sender->hasPermission("essentials.warps.*") && !$sender->hasPermission("essentials.warps." . strtolower($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] You can't teleport another one to that warp");
                    return false;
                }
                $player->teleport($warp[0], $warp[1], $warp[2]);
                $player->sendMessage(TextFormat::GREEN . "Warping to " . TextFormat::AQUA . $args[0] . TextFormat::GREEN . "...");
                $sender->sendMessage(TextFormat::GREEN . "Warping " . TextFormat::YELLOW . $args[1] . TextFormat::GREEN . " to " . TextFormat::AQUA . $args[0] . TextFormat::GREEN . "...");
                break;
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? $this->getUsage() : "Usage: /warp <name> <player>"));
                return false;
                break;
        }
        return true;
    }
} 