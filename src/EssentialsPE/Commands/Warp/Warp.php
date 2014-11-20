<?php
namespace EssentialsPE\Commands\Warp;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
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
        if($alias === "warps"){
            $message = TextFormat::AQUA . "Available warps:\n" . $this->getPlugin()->warpList(false);
            return $message;
        }
        $warp = $this->getPlugin()->getWarp($args[0]);
        switch(count($args)){
            case 1:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /warp <name> [player]");
                    return false;
                }
                if(!$warp){
                    $sender->sendMessage(TextFormat::RED . "[Error] Warp doesn't exists");
                    return false;
                }
                if(!$sender->hasPermission("essentials.warps.*") && !$sender->hasPermission("essentials.warps.$args[0]")){
                    $sender->sendMessage(TextFormat::RED . "[Error] You can't teleport to that warp");
                    return false;
                }
                $sender->teleport(new Position($warp[0], $warp[1], $warp[2], $sender->getServer()->getLevelByName($warp[3])), $warp[4], $warp[5]);
                $sender->sendMessage(TextFormat::GREEN . "Warping to $args[0]...");
                break;
            case 2:
                if(!$warp){
                    $sender->sendMessage(TextFormat::RED . "[Error] Warp doesn't exists");
                    return false;
                }
                $player = $this->getPlugin()->getPlayer($args[1]);
                if(!$player){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $player->teleport(new Position($warp[0], $warp[1], $warp[2], $sender->getServer()->getLevelByName($warp[3])), $warp[4], $warp[5]);
                $player->sendMessage(TextFormat::GREEN . "Warping to $args[0]...");
                $sender->sendMessage(TextFormat::GREEN . "Warping $args[1] to $args[0]...");
                break;
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? $this->getUsage() : "Usage: /warp <name> <player>"));
                return false;
                break;
        }
        return true;
    }
} 