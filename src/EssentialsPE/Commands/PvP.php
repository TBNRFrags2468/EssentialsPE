<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PvP extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "pvp", "Toggle PvP on/off", "<on|off>", false);
        $this->setPermission("essentials.pvp");
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
        }elseif(count($args) != 1){
            $this->sendUsage($sender, $alias);
            return false;
        }

        switch(strtolower($args[0])){
            case "on":
            case "off":
                $this->getAPI()->setPvP($sender, ($state = strtolower($args[0]) === "on" ? true : false));
                $sender->sendMessage(TextFormat::GREEN . "PvP " . ($state ? "enabled!" : "disabled!"));
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
}
