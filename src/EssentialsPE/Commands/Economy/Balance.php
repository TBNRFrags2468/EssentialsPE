<?php
namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Balance extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "balance", "See how many money do you have", "[player]", true, ["bal", "money"]);
        $this->setPermission("essentials.balance.use");
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
        if((!isset($args[0]) && !$sender instanceof Player) || count($args) > 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $player = $sender;
        if(isset($args[0])){
            if(!$sender->hasPermission("essentials.balance.other")){
                $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                return false;
            }elseif(!$player = $this->getAPI()->getPlayer($args[0])){
                $this->sendMessage($sender, "error.playernotfound");
                return false;
            }
        }
        $this->sendMessage($sender, "economy.balance." . ($player === $sender ? "self" : "other"), $player->getName(), $this->getAPI()->getMessage("economy.sign") . $this->getAPI()->getPlayerBalance($player));
        return true;
    }
}