<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Lightning extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "lightning", "Strike a lightning!", "[player [damage]]", "<player> [damage]", ["strike", "smite", "thor", "shock"]);
        $this->setPermission("essentials.lightning.use");
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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0]) && !($player = $this->getAPI()->getPlayer($args[0]))){
            $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
            return false;
        }
        $pos = isset($args[0]) ? $player : $player->getTargetBlock(100);
        $damage = isset($args[1]) ? $args[1] : 0;
        $this->getAPI()->strikeLightning($pos, $damage);
        $sender->sendMessage(TextFormat::YELLOW . "Lightning summoned!");
        return true;
    }
}