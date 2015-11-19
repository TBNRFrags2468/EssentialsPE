<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class GetPos extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "getpos", "Get your/other's position", "[player]", true, ["coords", "position", "whereami", "getlocation", "getloc"]);
        $this->setPermission("essentials.getpos.use");
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
                $sender->sendMessage(TextFormat::GREEN . "You're in world: " . TextFormat::AQUA . $sender->getLevel()->getName() . "\n" . TextFormat::GREEN . "Your Coordinates are:" . TextFormat::YELLOW . " X: " . TextFormat::AQUA . $sender->getFloorX() . TextFormat::GREEN . "," . TextFormat::YELLOW . " Y: " . TextFormat::AQUA . $sender->getFloorY() . TextFormat::GREEN . "," . TextFormat::YELLOW . " Z: " . TextFormat::AQUA . $sender->getFloorZ());
                break;
            case 1:
                if(!$sender->hasPermission("essentials.getpos.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                if(!($player = $this->getAPI()->getPlayer($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    return false;
                }
                $sender->sendMessage(TextFormat::YELLOW . $player->getDisplayName() . TextFormat::GREEN . " is in world: " . TextFormat::AQUA . $player->getLevel()->getName() . "\n" . TextFormat::GREEN . "Coordinates:" . TextFormat::YELLOW . " X: " . TextFormat::AQUA . $player->getFloorX() . TextFormat::GREEN . "," . TextFormat::YELLOW . " Y: " . TextFormat::AQUA . $player->getFloorY() . TextFormat::GREEN . "," . TextFormat::YELLOW . " Z: " . TextFormat::AQUA . $player->getFloorZ());
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
}
