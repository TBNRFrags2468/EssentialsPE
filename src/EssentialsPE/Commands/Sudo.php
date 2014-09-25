<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Sudo extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "sudo", "Run a command as another player", "/sudo <player>");
        $this->setPermission("essentials.sudo");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) < 1){
            $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
            return false;
        }
        $player = $this->getPlugin()->getPlayer($name = array_shift($args));
        if($player === false){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        $this->getPlugin()->getServer()->dispatchCommand($player, implode(" ", $args));
        $sender->sendMessage(TextFormat::AQUA . "Command ran has $name");
        return true;
    }
} 