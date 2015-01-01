<?php
namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Sell extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "sell", "Sell the specified item", "/sell <item|hand> [amount]");
        $this->setPermission("essentials.sell");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
            return false;
        }
        if($sender->getGamemode() === 1 || $sender->getGamemode() === 3){
            $sender->sendMessage(TextFormat::RED . "[Error] You're in " . $this->getPlugin()->getServer()->getGamemodeString($sender->getGamemode()) . " mode");
            return false;
        }
        if(strtolower($args[0]) === "hand"){
            $item = $sender->getInventory()->getItemInHand();
            if($item->getId() === 0){
                $sender->sendMessage(TextFormat::RED . "[Error] You don't have anything in your hand");
                return false;
            }
        }else{
            if(!is_int($args[0])){
                $item = Item::fromString($args[0]);
            }else{
                $item = Item::get($args[0]);
            }
            if($item->getId() === 0){
                $sender->sendMessage(TextFormat::RED . "[Error] Unknown item");
                return false;
            }
        }
        if(!$sender->getInventory()->contains($item)){
            $sender->sendMessage(TextFormat::RED . "[Error] You don't have that item in your inventory");
            return false;
        }
        if(isset($args[1]) && !is_int((int) $args[1])){
            $sender->sendMessage(TextFormat::RED . "[Error] Please specify a valid amount to sell");
            return false;
        }
        /** @var Item[] $contents */
        $contents = [];
        $quantity = 0;
        foreach($sender->getInventory()->getContents() as $s => $i){
            if($i->getId() === $item->getId() && $i->getDamage() === $item->getDamage()){
                $contents[$s] = clone $i;
                $quantity += $i->getCount();
            }
        }
        $worth = $this->getPlugin()->getItemWorth($item->getId());
        if(!isset($args[1])){
            $worth = $worth * $quantity;
            $sender->getInventory()->remove($item);
            $this->getPlugin()->addToPlayerBalance($sender, $worth);
            $sender->sendMessage(TextFormat::GREEN . "Item sold! You got " . $this->getPlugin()->getCurrencySymbol() . $worth);
            return true;
        }
        $amount = (int) $args[1];
        if($amount < 0){
            $amount = $quantity - $amount;
        }
        $count = $amount;
        foreach($contents as $s => $i){
            if(($count - $i->getCount()) >= 0){
                $count = $count - $i->getCount();
                $i->setCount(0);
            }else{
                $c = $i->getCount() - $count;
                $i->setCount($c);
                $count = 0;
            }
            if($count <= 0){
                break;
            }
        }
        $sender->sendMessage(TextFormat::RED . "Sold " . $amount . " items! You got" . $this->getPlugin()->getCurrencySymbol() . ($worth * $amount));
        return true;
    }
}