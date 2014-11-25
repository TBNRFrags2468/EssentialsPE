<?php
namespace EssentialsPE\Commands\Home;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;

class SetHome extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "sethome", "Create or update a home position", "/sethome <name>", ["createhome"]);
        $this->setPermission("essentials.sethome");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        //TODO
        return true;
    }
} 