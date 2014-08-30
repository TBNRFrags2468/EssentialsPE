<?php
namespace EssentialsPE\Commands\Warps;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Warp extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "warp", "Teleport to a warp", "/warp <name|list>", ["warps"]);
        $this->setPermission("essentials.warp");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
                break;
            case 1:
                switch(strtolower($args[0])){
                    case "list":
                        if($list = $this->getAPI()->warpList()){
                            $m = TextFormat::YELLOW . "==== List of Warps ====";
                            foreach($list as $l){
                                $m .=TextFormat::AQUA . $l . TextFormat::YELLOW . ",";
                            }
                            $m = substr($m, -1, 2);
                            $m = wordwrap($m, 10, "\n", true);
                            $sender->sendMessage($m);
                        }
                        break;
                    default:
                        if(!$sender instanceof Player){
                            $sender->sendMessage(TextFormat::RED . "Usage: /warp <name> <player>");
                            return false;
                        }elseif($this->getAPI()->warpExist($args[0])){
                            $sender->sendMessage(TextFormat::YELLOW . "Teleporting...");
                            $this->getAPI()->warpPlayer($sender, $args[0]);
                        }else{
                            $sender->sendMessage(TextFormat::RED . "[Error] Warp not found");
                        }
                        break;
                }
                break;
            case 2:
                if(!$sender->hasPermission("essentials.warp.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                $player = $this->getAPI()->getPlayer($args[1]);
                if($player === false){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                if(!$this->getAPI()->warpExist($args[0])){
                    $sender->sendMessage(TextFormat::RED . "[Error] Warp not found");
                    return false;
                }
                $sender->sendMessage(TextFormat::YELLOW . "Teleporting...");
                $player->sendMessage(TextFormat::YELLOW . "Teleporting...");
                $this->getAPI()->warpPlayer($player, $args[0]);
                break;
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
                return false;
                break;
        }
        return true;
    }
} 