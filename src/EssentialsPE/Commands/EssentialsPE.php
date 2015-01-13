<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use EssentialsPE\Tasks\Updater\UpdateFetchTask;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class EssentialsPE extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "essentials", "Get current Essentials version", "/essentialspe [reload|update]", ["essentials", "ess", "esspe"]);
        $this->setPermission("essential.essentials");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 0:
                $sender->sendMessage(TextFormat::YELLOW . "You're using " . TextFormat::AQUA . "EssentialsPE " . TextFormat::YELLOW . "v" . TextFormat::GREEN . $sender->getServer()->getPluginManager()->getPlugin("EssentialsPE")->getDescription()->getVersion());
                break;
            case 1:
                switch(strtolower($args[0])){
                    case "reload":
                    case "r":
                        if(!$sender->hasPermission("essentials.essentials.reload")){
                            $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                            return false;
                        }
                        $this->getPlugin()->checkConfig();
                        $this->getPlugin()->reloadFiles();
                        $sender->sendMessage(TextFormat::AQUA . "Config successfully reloaded!");
                        break;
                    /*case "update":
                    case "u":
                        if(!$sender->hasPermission("essentials.essentials.update")){
                            $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                            return false;
                        }
                        $sender->sendMessage(TextFormat::YELLOW . ($sender instanceof Player ? "" : "Usage: ") . "/essentialspe update <check|install>");
                        break;*/
                    default:
                        $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
                        return false;
                        break;
                }
                break;
            /*case 2:
                if(strtolower($args[0]) !== "update" && strtolower($args[0]) !== "u"){
                    $sender->sendMessage(TextFormat::RED . $this->getUsage());
                    return false;
                }
                if($sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "[Error] You can only perform this action on the console");
                    return false;
                }
                switch(strtolower($args[1])){
                    case "check":
                    case "c":
                        $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new UpdateFetchTask("stable")); //Just for tests... When the config works, use the getUpdateBuild() function
                        //$this->getPlugin()->getLogger()->info($this->getPlugin()->getUpdateBuild());
                        break;
                    case "install":
                    case "i":
                    $this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new UpdateFetchTask("stable", true)); //Just for tests... When the config works, use the getUpdateBuild() function
                        #$this->getPlugin()->getServer()->getScheduler()->scheduleAsyncTask(new UpdateFetchTask($this->getPlugin()->getUpdateBuild(), true));
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
