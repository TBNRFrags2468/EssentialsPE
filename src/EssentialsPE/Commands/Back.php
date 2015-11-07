<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Back extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "back", "Teleport to your previous location", null, false, ["return"]);
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
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(count($args) !== 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(!($pos = $this->getPlugin()->getLastPlayerPosition($sender))){
            $sender->sendMessage(TextFormat::RED . "[Error] No previous position available");
        }else{
            $sender->sendMessage(TextFormat::GREEN . "Teleporting...");
            $sender->teleport($pos);
        }
        return true;
    }
} 