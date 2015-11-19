<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class TempBan extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tempban", "Temporary bans the specified player", "<player> <time...> [reason ...]");
        $this->setPermission("essentials.tempban");
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
        if(count($args) < 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($info = $this->getAPI()->stringToTimestamp(implode(" ", $args)))){
            $sender->sendMessage(TextFormat::RED . "[Error] Please specify a valid time");
            return false;
        }
        /** @var \DateTime $date */
        $date = $info[0];
        $reason = $info[1];
        if(($player = $this->getAPI()->getPlayer($name = array_shift($args))) !== false){
            if($player->hasPermission("essentials.ban.exempt")){
                $sender->sendMessage(TextFormat::RED . "[Error] " . $name . " can't be banned");
                return false;
            }else{
                $name = $player->getName();
                $player->kick(TextFormat::RED . "Banned until " . TextFormat::AQUA . $date->format("l, F j, Y") . TextFormat::RED . " at " . TextFormat::AQUA . $date->format("h:ia") . (trim($reason) !== "" ? TextFormat::YELLOW . "\nReason: " . TextFormat::RESET . $reason : ""), false);
            }
        }
        $sender->getServer()->getNameBans()->addBan($name, (trim($reason) !== "" ? $reason : null), $date, "essentialspe");
        $this->broadcastCommandMessage($sender, "Banned player " . $name . " until " . $date->format("l, F j, Y") . " at " . $date->format("h:ia") . (trim($reason) !== "" ? TextFormat::YELLOW . " Reason: " . TextFormat::RESET . $reason : ""));
        return true;
    }
}
