<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Depth extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "depth", "Display your depth related to sea-level", null, false, ["height"]);
        $this->setPermission("essentials.depth");
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
        if(($pos = $sender->getFloorY() - 63) === 0){
            $sender->sendMessage(TextFormat::AQUA . "You're at sea level");
        }else{
            $sender->sendMessage(TextFormat::AQUA . "You're " . (substr($pos, 0, 1) === "-" ? substr($pos, 1) : $pos) . " meters " . ($pos > 0 ? "above" : "below") . " the sea level.");
        }
        return true;
    }
}