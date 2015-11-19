<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ClearInventory extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "clearinventory", "Clear your/other's inventory", "[player]", true, ["ci", "clean", "clearinvent"]);
        $this->setPermission("essentials.clearinventory.use");
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
                if(($gm = $sender->getGamemode()) === 1 || $gm === 3){
                    $sender->sendMessage(TextFormat::RED . "[Error] You're in " . $this->getAPI()->getServer()->getGamemodeString($gm) . " mode");
                    return false;
                }
                $sender->getInventory()->clearAll();
                $sender->sendMessage(TextFormat::AQUA . "Your inventory was cleared");
                break;
            case 1:
                if(!$sender->hasPermission("essentials.clearinventory.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                if(!($player = $this->getAPI()->getPlayer($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    return false;
                }
                if(($gm = $player->getGamemode()) === 1 || $gm === 3){
                    $sender->sendMessage(TextFormat::RED . "[Error] " . $player->getDisplayName() . " is on " . ($gm === 1 ? "creative" : "adventure") . " mode");
                    return false;
                }
                $player->getInventory()->clearAll();
                $sender->sendMessage(TextFormat::AQUA . $player->getDisplayName() . (substr($player->getDisplayName(), -1, 1) === "s" ? "'" : "'s") . " inventory was cleared");
                $player->sendMessage(TextFormat::AQUA . "Your inventory was cleared");
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
}
