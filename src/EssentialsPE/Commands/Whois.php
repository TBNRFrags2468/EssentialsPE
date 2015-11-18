<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Whois extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "whois", "Display player information", "<player>");
        $this->setPermission("essentials.whois");
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
        if(count($args) < 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($player = $this->getPlugin()->getPlayer($alias[0]))){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        $data = $this->getPlugin()->getPlayerInformation($player);
        if(!$sender->hasPermission("essentials.geoip.show") || $player->hasPermission("essentials.geoip.hide")){
            unset($data["location"]);
        }
        $message =TextFormat::AQUA . "Information:\n";
        foreach($data as $k => $v){
            $message .= TextFormat::GRAY . " * " . ucfirst($k) . ": $v";
        }
        $sender->sendMessage($message);
        return true;
    }
}