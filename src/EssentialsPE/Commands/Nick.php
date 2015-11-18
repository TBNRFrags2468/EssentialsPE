<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Nick extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "nick", "Change your in-game name", "<new nick|off> [player]", true, ["nickname"]);
        $this->setPermission("essentials.nick.use");
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
            case 1:
                if(!$sender instanceof Player){
                    $this->sendUsage($sender, $alias);
                    return false;
                }
                if(($nickname = $args[0]) === "off"){
                    $this->getPlugin()->removeNick($sender);
                }else{
                    if(!$this->getPlugin()->setNick($sender, $nickname)){
                        $sender->sendMessage(TextFormat::RED . "[Error] You don't have permissions to use 'colored' nicknames");
                    }
                }
                $sender->sendMessage(TextFormat::GREEN . "Your nick " . ($nickname === "off" ? "has been removed" : "is now " . $nickname));
                break;
            case 2:
                if(!$sender->hasPermission("essentials.nick.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                if(!($player = $this->getPlugin()->getPlayer($args[1]))){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                if(($nickname = $args[0]) === "off"){
                    $this->getPlugin()->removeNick($player);
                }else{
                    if(!$this->getPlugin()->setNick($player, $nickname)){
                        $sender->sendMessage(TextFormat::RED . "[Error] You don't have permissions to give 'colored' nicknames");
                    }
                }
                $sender->sendMessage(TextFormat::GREEN . $player->getName() . (substr($player->getName(), -1, 1) === "s" ? "'" : "'s") . " nick " . ($nickname === "off" ? "has been removed" : "is now " . $nickname));
                $player->sendMessage(TextFormat::GREEN . "Your nick " . ($nickname === "off" ? "has been removed" : "is now " . $nickname));
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
}
