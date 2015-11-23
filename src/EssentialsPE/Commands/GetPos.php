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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("essentials.getpos.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!($player = $this->getAPI()->getPlayer($args[0]))){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                return false;
            }
        }
        $sender->sendMessage(TextFormat::GREEN . ($player === $sender ? "You're" : $player->getDisplayName() . TextFormat::GRAY . "is") . "in world: " . TextFormat::AQUA . $player->getLevel()->getName() . "\n" . TextFormat::GREEN . "Coordinates: " . TextFormat::YELLOW . "X: " . TextFormat::AQUA . $player->getFloorX() . TextFormat::GREEN . ", " . TextFormat::YELLOW . "Y: " . TextFormat::AQUA . $player->getFloorY() . TextFormat::GREEN . ", " . TextFormat::YELLOW . "Z: " . TextFormat::AQUA . $player->getFloorZ());
        return true;
    }
}
