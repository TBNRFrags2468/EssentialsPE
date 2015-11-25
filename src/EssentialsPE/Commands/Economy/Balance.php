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
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                #$sender->sendMessage($this->getAPI()->getMessage("error.player.notfound"));
                return false;
            }
        }
        $sender->sendMessage(TextFormat::AQUA . ($player === $sender ? "Your current balance is " : $player->getDisplayName() . TextFormat::AQUA . " has ") . TextFormat::YELLOW . $this->getAPI()->getCurrencySymbol() . $this->getAPI()->getPlayerBalance($player));
        #$sender->sendMessage(TextFormat::AQUA . $this->getAPI()->getMessage("balance.message." . ($player === $sender ? "self" : "other"), $player->getName(), TextFormat::YELLOW . $this->getAPI()->getMessage("balance.sign") . $this->getAPI()->getPlayerBalance($player)));
        return true;
    }
}