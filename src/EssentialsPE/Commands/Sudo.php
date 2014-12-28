<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Sudo extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "sudo", "Run a command as another player", "/sudo <player> <command line>");
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
        if(!$player){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }elseif($player->hasPermission("essentials.sudo.exempt")){
            $sender->sendMessage(TextFormat::RED . "[Error] " . $name . " cannot be sudo'ed");
            return false;
        }

        $v = implode(" ", $args);
        if(substr($v, 0, 2) === "c:"){
            $sender->sendMessage(TextFormat::GREEN . "Sending message as " . $name);
            $this->getPlugin()->getServer()->getPluginManager()->callEvent($ev = new PlayerChatEvent($player, $v));
            if(!$ev->isCancelled()){
                $this->getPlugin()->getServer()->broadcastMessage(\sprintf($ev->getFormat(), $ev->getPlayer()->getDisplayName(), $ev->getMessage()), $ev->getRecipients());
            }
        }else{
            $sender->sendMessage(TextFormat::AQUA . "Command ran has " . $name);
            $this->getPlugin()->getServer()->dispatchCommand($player, $v);
        }
        return true;
    }
} 