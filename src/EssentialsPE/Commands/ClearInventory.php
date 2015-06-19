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
        parent::__construct($plugin, "clearinventory", "Clear your/other's inventory", "/clearinventory [player]", null, ["ci", "clean", "clearinvent"]);
        $this->setPermission("essentials.clearinventory");
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
                    $sender->sendMessage($this->getConsoleUsage());
                    return false;
                }
                $gm = $sender->getGamemode();
                if($gm === 1 || $gm === 3){
                    $sender->sendMessage(TextFormat::RED . "[Error] You're in " . ($gm === 1 ? "creative" : "adventure") . " mode");
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
                $player = $this->getPlugin()->getPlayer($args[0]);
                if(!$player){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found.");
                    return false;
                }
                $gm = $player->getGamemode();
                if($gm === 1 || $gm === 3){
                    $sender->sendMessage(TextFormat::RED . "[Error] " . $player->getDisplayName() . " is on " . ($gm === 1 ? "creative" : "adventure") . " mode");
                    return false;
                }
                $player->getInventory()->clearAll();
                $sender->sendMessage(TextFormat::AQUA . $player->getDisplayName() . (substr($player->getDisplayName(), -1, 1) === "s" ? "'" : "'s") . " inventory was cleared");
                $player->sendMessage(TextFormat::AQUA . "Your inventory was cleared");
                break;
            default:
                $sender->sendMessage($sender instanceof Player ? $this->getUsage() : $this->getConsoleUsage());
                return false;
                break;
        }
        return true;
    }
}
