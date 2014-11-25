<?php
namespace EssentialsPE\Commands\Home;

use EssentialsPE\BaseCommand;
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

        return true;
    }
} 