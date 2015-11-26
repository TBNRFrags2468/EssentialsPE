<?php
namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Sell extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "sell", "Sell the specified item", "<item|hand> [amount]", false);
        $this->setPermission("essentials.sell");
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
        if($sender->getGamemode() === Player::CREATIVE || $sender->getGamemode() === Player::SPECTATOR){
            $this->sendMessage($sender, "error.gamemode.self", $this->getAPI()->getServer()->getGamemodeString($sender->getGamemode()));
            return false;
        }
        if(strtolower($args[0]) === "hand"){
            $item = $sender->getInventory()->getItemInHand();
            if($item->getId() === 0){
                $this->sendMessage($sender, "error.item.in.hand");
                return false;
            }
        }else{
            if(!is_int($args[0])){
                $item = Item::fromString($args[0]);
            }else{
                $item = Item::get($args[0]);
            }
            if($item->getId() === Item::AIR){
                $this->sendMessage($sender, "error.item.unknown");
                return false;
            }
        }
        if(!$sender->getInventory()->contains($item)){
            $this->sendMessage($sender, "error.item.in.inventory");
            return false;
        }
        if(isset($args[1]) && !is_numeric($args[1])){
            $this->sendMessage($sender, "error.economy.amount");
            return false;
        }

        $amount = $this->getAPI()->sellPlayerItem($sender, $item, (isset($args[1]) ? $args[1] : null));
        if(!$amount){
            $this->sendMessage($sender, "error.economy.worth.unknown");
            return false;
        }elseif($amount === -1){
            $this->sendMessage($sender, "error.item.quantity");
            return false;
        }
        $profit = $this->getAPI()->getCurrencySymbol();
        $m = "single";
        if(is_array($amount)){
            $profit .= $amount[0] * $amount[1];
            $amount = $amount[0];
            $m = "much";
        }else{
            $profit .= $amount;
        }
        $this->sendMessage($sender, "economy.sold.$m", $amount, $profit);
        return true;
    }
}