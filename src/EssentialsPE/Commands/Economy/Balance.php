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
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $this->sendUsage($sender, $alias);
                    return false;
                }
                $sender->sendMessage(TextFormat::AQUA . "Your current balance is " . TextFormat::YELLOW . $this->getAPI()->getCurrencySymbol() . $this->getAPI()->getPlayerBalance($sender));
                break;
            case 1:
                if(!$sender->hasPermission("essentials.balance.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                if(!$player = $this->getAPI()->getPlayer($args[0])){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                $sender->sendMessage(TextFormat::AQUA . $player->getDisplayName() . " has " . TextFormat::YELLOW . $this->getAPI()->getCurrencySymbol() . $this->getAPI()->getPlayerBalance($player));
                break;
        }
        return true;
    }
}