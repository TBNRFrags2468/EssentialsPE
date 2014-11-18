<?php
namespace EssentialsPE\Commands\Override;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Kill extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "kill", "Kill other people", "/kill <player>");
        $this->setPermission("essentials.kill");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) !== 1){
            $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
            return false;
        }
        $player = $this->getPlugin()->getPlayer($args[0]);
        if(!$player){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        $sender->getServer()->getPluginManager()->callEvent($ev = new EntityDamageEvent($sender, EntityDamageEvent::CAUSE_SUICIDE, 1000));
        if($ev->isCancelled()){
            return true;
        }

        $player->setLastDamageCause($ev);
        $player->setHealth(0);
        $player->sendMessage("Ouch. That look like it hurt.");
        return true;
    }
} 