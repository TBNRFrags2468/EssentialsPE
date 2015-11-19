<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Back extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "back", "Teleport to your previous location", null, false, ["return"]);
        $this->setPermission("essentials.back.use");
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
        if(!($pos = $this->getAPI()->getLastPlayerPosition($sender))){
            $sender->sendMessage(TextFormat::RED . "[Error] No previous position available");
        }else{
            $sender->sendMessage(TextFormat::GREEN . "Teleporting...");
            $sender->teleport($pos);
        }
        return true;
    }
} 