<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class EssentialsPE extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "essentials", "Get current Essentials version", "[update <check|install>]", true, ["essentials", "ess", "esspe"]);
        $this->setPermission("essentials.essentials");
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
        if(count($args) > 2){
            $this->sendUsage($sender, $alias);
            return false;
        }elseif(!isset($args[0])){
            $args[0] = "v";
        }
        switch(strtolower($args[0])){
            case "update":
            case "u":
                if(!$sender->hasPermission("essentials.update.use")){
                    $sender->sendMessage($this->getPermissionMessage());
                    return false;
                }
                $a = strtolower($args[1]);
                if(!(isset($args[1]) && ($a === "check" || $a === "c" || $a === "install" || $a === "i"))){
                    $this->sendUsage($sender, $alias);
                    return false;
                }
                if(!$this->getAPI()->fetchEssentialsPEUpdate($a[0] === "i")){
                    #$sender->sendMessage(TextFormat::YELLOW . "The updater is already working... Please wait a few moments and try again");
                    $sender->sendMessage($this->getAPI()->getMessage("warning.updater"));
                }
                break;
            case "version":
            case "v":
                #$sender->sendMessage(TextFormat::YELLOW . "You're using " . TextFormat::AQUA . "EssentialsPE " . TextFormat::YELLOW . "v" . TextFormat::GREEN . $sender->getServer()->getPluginManager()->getPlugin("EssentialsPE")->getDescription()->getVersion());
                $sender->sendMessage($this->getAPI()->getMessage("essentials.version", $sender->getServer()->getPluginManager()->getPlugin("EssentialsPE")->getDescription()->getVersion()));
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
}
