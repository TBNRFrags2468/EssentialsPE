<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class EssentialsPE extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "essentials", "Get current Essentials version", "/essentialspe [reload]", ["esspe"]);
        $this->setPermission("essential.essentials");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                $sender->sendMessage(TextFormat::YELLOW . "You're using " . TextFormat::AQUA . "EssentialsPE " . TextFormat::GREEN . "v" . $sender->getServer()->getPluginManager()->getPlugin("EssentialsPE")->getDescription()->getVersion());
                break;
            case 1:
                switch(strtolower($args[0])){
                    case "reload":
                        if(!$sender->hasPermission("essentials.essentials.reload")){
                            $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                            return false;
                        }
                        $this->getPlugin()->checkConfig();
                        $sender->sendMessage(TextFormat::AQUA . "Config successfully reloaded!");
                        break;
                    /*case "update":
                        $sender->sendMessage(TextFormat::YELLOW . ($sender instanceof Player ? "" : "Usage: ") . "/essentialspe update <check|update>");
                        break;*/
                    default:
                        $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
                        return false;
                        break;
                }
                break;
            /*case 2:
                switch(strtolower($args[1])){
                    case "check":
                        //TODO
                        break;
                    case "install":
                        //TODO
                        break;
                    default:
                        $sender->sendMessage(TextFormat::YELLOW . ($sender instanceof Player ? "" : "Usage: ") . "/essentialspe update <check|update>");
                        break;
                }
                break;*/
            default:
                $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
                return false;
                break;
        }
        return true;
    }
}
