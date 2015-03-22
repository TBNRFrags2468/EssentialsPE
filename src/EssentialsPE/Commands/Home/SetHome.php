<?php
namespace EssentialsPE\Commands\Home;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SetHome extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "sethome", "Create or update a home position", "/sethome <name>", ["createhome"]);
        $this->setPermission("essentials.sethome");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please use this command in-game");
            return false;
        }
        if(count($args) !== 1){
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
            return false;
        }
        if(strtolower($args[0]) === "bed"){
            $sender->sendMessage(TextFormat::RED . "[Error] You can only set a \"bed\" home by sleeping in a bed");
            return false;
        }elseif(trim($args[0] === "")){
            $sender->sendMessage(TextFormat::RED . "[Error] Please provide a Home name");
            return false;
        }
        $existed = $this->getPlugin()->homeExists($sender, $args[0]);
        $this->getPlugin()->setHome($sender, strtolower($args[0]), $sender->getPosition(), $sender->getYaw(), $sender->getPitch());
        $sender->sendMessage(TextFormat::GREEN . "Home successfuly " . ($existed ? "updated" : "created"));
        return true;
    }
} 
