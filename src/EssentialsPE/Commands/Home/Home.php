<?php
namespace EssentialsPE\Commands\Home;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;

class Home extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "home", "Teleport to your home", "/home <name>");
        $this->setPermission("essentials.home");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        //TODO
        return true;
    }
} 