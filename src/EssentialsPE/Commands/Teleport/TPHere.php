<?php
namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPHere extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "tphere", "Teleport a player to you", "<player>", false, ["s"]);
        $this->setPermission("essentials.tphere");
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
        if(!$sender instanceof Player || count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $this->getPlugin()->getPlayer($args[0]);
        if(!$player){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        $player->teleport($sender);
        $player->sendMessage(TextFormat::YELLOW . "Teleporting to " . $sender->getDisplayName() . "...");
        $sender->sendMessage(TextFormat::YELLOW . "Teleporting " . $player->getDisplayName() . " to you...");
        return true;
    }
} 