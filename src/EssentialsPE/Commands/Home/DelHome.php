<?php
namespace EssentialsPE\Commands\Home;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;

class DelHome extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "delhome", "Remove a home", "/delhome <name>", ["remhome", "removehome"]);
        $this->setPermission("essentials.delhome");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        //TODO
        return true;
    }
} 