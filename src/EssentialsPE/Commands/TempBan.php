<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TempBan extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "tempban", "Temporary bans the specified player", "/tempban <player> <time...> [reason ...]");
        $this->setPermission("essentials.tempban");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) < 2){
            $sender->sendMessage($sender instanceof Player ? $this->getUsage() : $this->getConsoleUsage());
            return false;
        }
        $player = $this->getPlugin()->getPlayer($name = array_shift($args));

        $date = new \DateTime();
        foreach(explode(",", array_shift($args)) as $t){
            if(strpos($t, "s")){
                $date->add(new \DateInterval("P" . strtoupper($t)));
            }elseif(strpos($t, "m")){
                $date->add(new \DateInterval("PT" . strtoupper($t)));
            }elseif(strpos($t, "h")){
                $date->add(new \DateInterval("P" . strtoupper($t)));
            }elseif(strpos($t, "d")){
                $date->add(new \DateInterval("P" . strtoupper($t)));
            }elseif(strpos($t, "w")){
                $date->add(new \DateInterval("P" . strtoupper($t)));
            }elseif(strpos($t, "mo")){
                $date->add(new \DateInterval("P" . strtoupper($t)));
            }elseif(strpos($t, "y")){
                $date->add(new \DateInterval("P" . strtoupper($t)));
            }elseif(is_int((int) $t)){
                $date->add(new \DateInterval("P" . $t . "S"));
            }else{
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
                return false;
            }
        }
        $reason = implode(" ", $args);
        if($player !== false){
            if($player->hasPermission("essentials.ban.exempt")){
                $sender->sendMessage(TextFormat::RED . "[Error] ". $name . " can't be banned");
                return false;
            }else{
                $name = $player->getName();
                $player->kick(TextFormat::RED . "Banned until " . TextFormat::AQUA . $date->format("l, F j, Y") . TextFormat::RED . " at " . TextFormat::AQUA . $date->format("h:ia"));
            }
        }
        $sender->getServer()->getNameBans()->addBan($name, ($reason !== "" ? $reason : null), $date, "essentialspe");

        $this->broadcastCommandMessage($sender, "Banned player " . $name . " until " . $date->format("l, F j, Y") . " at " . $date->format("h:ia"));
        return true;
    }
}
