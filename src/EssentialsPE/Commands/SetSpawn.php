<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SetSpawn extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "setspawn", "Change your server main spawn point", null, false);
        $this->setPermission("essentials.setspawn");
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
        if(!$sender instanceof Player || count($args) != 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $sender->getLevel()->setSpawnLocation($sender);
        $sender->getServer()->setDefaultLevel($sender->getLevel());
        $sender->sendMessage(TextFormat::YELLOW . "Server's spawn point changed!");
        $this->getAPI()->getServer()->getLogger()->info(TextFormat::YELLOW . "Server's spawn point set to" . TextFormat::AQUA . $sender->getLevel()->getName() . TextFormat::YELLOW . " by " . TextFormat::GREEN . $sender->getName());
        return true;
    }
}
