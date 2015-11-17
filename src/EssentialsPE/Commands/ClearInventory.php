<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class ClearInventory extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "clearinventory", "Clear your/other's inventory", "[player]", null, ["ci", "clean", "clearinvent"]);
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
                    $sender->sendMessage(TextFormat::RED . "[Error] You're in " . $this->getPlugin()->getServer()->getGamemodeString($gm) . " mode");
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
                if(!($player = $this->getPlugin()->getPlayer($args[0]))){
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
