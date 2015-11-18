<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class God extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "god", "Prevent you to take any damage", "[player]", true, ["godmode", "tgm"]);
        $this->setPermission("essentials.god.use");
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
                $this->getPlugin()->switchGodMode($sender);
                $sender->sendMessage(TextFormat::AQUA . "God mode " . ($this->getPlugin()->isGod($sender) ? "enabled!" : "disabled"));
                break;
            case 1:
                if(!$sender->hasPermission("essentials.god.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                if(!($player = $this->getPlugin()->getPlayer($args[0]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $this->getPlugin()->switchGodMode($player);
                $sender->sendMessage(TextFormat::AQUA . "God mode " . ($this->getPlugin()->isGod($player) ? "enabled" : "disabled") . " for " . $player->getDisplayName());
                $player->sendMessage(TextFormat::AQUA . "God mode " . ($this->getPlugin()->isGod($player) ? "enabled!" : "disabled"));
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
}
