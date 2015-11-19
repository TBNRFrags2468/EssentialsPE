<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Antioch extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "antioch", "Holy hand grenade", null, false, ["grenade", "tnt"]);
        $this->setPermission("essentials.antioch");
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
        if(!$sender instanceof Player || count($args) !== 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!$this->getAPI()->antioch($sender)){
            $sender->sendMessage(TextFormat::RED . "[Error] Cannot throw the grenade, there isn't a near valid block");
            return false;
        }
        $sender->sendMessage(TextFormat::GREEN . "Grenade threw!");
        return true;
    }
}