<?php
namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Worth extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "worth", "Get the price of an item", "<hand|item>", "<item>");
        $this->setPermission("essentials.worth");
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
        if(count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        switch(strtolower($args[0])){
            case "hand":
                if(!$sender instanceof Player){
                    $this->sendUsage($sender, $alias);
                    return false;
                }
                $id = $sender->getInventory()->getItemInHand()->getId();
                if(!($worth = $this->getAPI()->getItemWorth($id))){
                    $sender->sendMessage(TextFormat::RED . "[Error] Worth not available for this item");
                    return false;
                }
                $sender->sendMessage(TextFormat::AQUA . "This item worth is " . $this->getAPI()->getCurrencySymbol() . $worth);
                break;
            default:
                $item = $this->getAPI()->getItem($args[0]);
                if(!($worth = $this->getAPI()->getItemWorth($item->getId()))){
                    $sender->sendMessage(TextFormat::RED . "[Error] Worth not available for this item");
                    return false;
                }
                $sender->sendMessage(TextFormat::AQUA . "This item worth is " . $this->getAPI()->getCurrencySymbol() . $worth);
                break;
        }
        return true;
    }
}