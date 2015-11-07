<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class PvP extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "pvp", "Toggle PvP on/off", "<on|off>", false);
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
                $this->getPlugin()->setPvP($sender, (strtolower($args[0]) === "on" ? true : false));
                $sender->sendMessage(TextFormat::GREEN . "PvP " . (strtolower($args[0]) === "on" ? "enabled!" : "disabled!"));
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
}
