<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Suicide extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "suicide", "Kill yourself", "/suicide");
        $this->setPermission("essentials.suicide");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
            return false;
        }
        if(count($args) !== 0){
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
            return false;
        }
        $sender->getServer()->getPluginManager()->callEvent($ev = new EntityDamageEvent($sender, EntityDamageEvent::CAUSE_SUICIDE, ($sender->getHealth())));
        if($ev->isCancelled()){
            return true;
        }

        $sender->setLastDamageCause($ev);
        $sender->setHealth(0);
        $sender->sendMessage("Ouch. That look like it hurt.");
        return true;
    }
} 