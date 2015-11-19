<?php
namespace EssentialsPE\Commands\Override;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\RemoteConsoleCommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Msg extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "Msg", "Send private messages to other players", "<player> <message ...>", true, ["tell", "m", "t", "whisper"]);
        $this->setPermission("essentials.msg");
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
        if(count($args) < 2){
            $this->sendUsage($sender, $alias);
            return false;
        }
        $t = array_shift($args);
        if(strtolower($t) !== "console" && strtolower($t) !== "rcon"){
            $t = $this->getAPI()->getPlayer($t);
            if(!$t){
                $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                return false;
            }
        }
        $sender->sendMessage(TextFormat::YELLOW . "[me -> " . ($t instanceof Player ? $t->getDisplayName() : $t) . "]" . TextFormat::RESET . " " . implode(" ", $args));
        $m = TextFormat::YELLOW . "[" . ($sender instanceof Player ? $sender->getDisplayName() : $sender->getName()) . " -> me]" . TextFormat::RESET . " " . implode(" ", $args);
        if($t instanceof Player){
            $t->sendMessage($m);
        }else{
            $this->getPlugin()->getLogger()->info($m);
        }
        $this->getAPI()->setQuickReply(($t instanceof Player ? $t : ($t === "console" ? new ConsoleCommandSender() : new RemoteConsoleCommandSender())), $sender);
        return true;
    }
}