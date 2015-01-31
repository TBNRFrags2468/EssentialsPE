<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\RemoteConsoleCommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Reply extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "reply", "Quickly reply to the last person that messaged you", "/reply <message ...>", ["r"]);
        $this->setPermission("essentials.reply");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) < 1){
            $sender->sendMessage(TextFormat::RED . ($sender instanceof Player ? "" : "Usage: ") . $this->getUsage());
            return false;
        }
        $t = $this->getPlugin()->getQuickReply($sender);
        if(!$t){
            $sender->sendMessage(TextFormat::RED . "[Error] No target available for QuickReply");
            return false;
        }
        if(strtolower($t) !== "console" || strtolower($t) !== "rcon"){
            $t = $this->getPlugin()->getPlayer($t);
            if(!$t){
                $sender->sendMessage(TextFormat::RED . "[Error] No target available for QuickReply");
                $this->getPlugin()->removeQuickReply($sender);
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
        $this->getPlugin()->setQuickReply(($t instanceof Player ? $t : ($t === "console" ? new ConsoleCommandSender() : new RemoteConsoleCommandSender())), $sender);
        return true;
    }
}