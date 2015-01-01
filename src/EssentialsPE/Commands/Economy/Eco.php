<?php
namespace EssentialsPE\Commands\Economy;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Eco extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "eco", "Sets the balance of a player", "/eco <give|take|set|reset> <player> [amount]", ["economy"]);
        $this->setPermission("essentials.eco");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        switch(count($args)){
            case 2:
            case 3:
                $player = $this->getPlugin()->getPlayer($args[1]);
                if(!$player){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player nto found");
                    return false;
                }
                if(!isset($args[2]) && strtolower($args[0]) !== "reset"){
                    $sender->sendMessage(TextFormat::RED . "[Error] Please specify an amount");
                    return false;
                }
                if(!is_int((int) $args[2])){
                    $sender->sendMessage(TextFormat::RED . "[Error] Please specify a valid amount");
                    return false;
                }
                switch(strtolower($args[0])){
                    case "give":
                        $balance = (int) $args[2];
                        $sender->sendMessage(TextFormat::YELLOW . "Adding the balance...");
                        $this->getPlugin()->addToPlayerBalance($player, $balance);
                        break;
                    case "take":
                        $balance = (int) "-" . $args[2];
                        $sender->sendMessage(TextFormat::YELLOW . "Taking the balance...");
                        $this->getPlugin()->addToPlayerBalance($player, $balance);
                        break;
                    case "set":
                        $balance = (int) $args[2];
                        $sender->sendMessage(TextFormat::YELLOW . "Setting the balance...");
                        $this->getPlugin()->setPlayerBalance($player, $balance);
                        break;
                    case "reset":
                        $sender->sendMessage(TextFormat::YELLOW . "Resetting balance...");
                        $this->getPlugin()->setPlayerBalance($player, $this->getPlugin()->getDefaultBalance());
                        break;
                }
                break;
        }
        return true;
    }
}