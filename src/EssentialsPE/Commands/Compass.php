<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Compass extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "compass", "Display your current bearing direction", null, false, ["direction"]);
        $this->setPermission("essentials.compass");
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
        if(!$sender instanceof Player || count($args) !== 0){
            $this->sendUsage($sender, $alias);
            return false;
        }
        switch($sender->getDirection()){
            case 0:
                $direction = "south";
                break;
            case 1:
                $direction = "west";
                break;
            case 2:
                $direction = "north";
                break;
            case 3:
                $direction = "east";
                break;
            default:
                $sender->sendMessage(TextFormat::RED . "Oops, there was an error while getting your face direction");
                return false;
                break;
        }
        $sender->sendMessage(TextFormat::AQUA . "You're facing " . TextFormat::YELLOW . $direction);
        return true;
    }
}
