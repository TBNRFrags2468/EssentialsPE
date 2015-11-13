<?php
namespace EssentialsPE\BaseFiles;

use EssentialsPE\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand{
    /** @var Loader  */
    private $plugin;
    /** @var null|string */
    private $consoleUsageMessage = null;

    /**
     * @param Loader $plugin
     * @param string $name
     * @param string $description
     * @param null|string $usageMessage
     * @param bool|null|string $consoleUsageMessage
     * @param array $aliases
     */
    public function __construct(Loader $plugin, $name, $description = "", $usageMessage = null, $consoleUsageMessage = null, array $aliases = []){
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->plugin = $plugin;
        $this->consoleUsageMessage = $consoleUsageMessage;
    }

    /**
     * @return Loader
     */
    public final function getPlugin(){
        return $this->plugin;
    }

    /**
     * @return string
     */
    public function getUsage(){
        return "/" . parent::getName() . " " . parent::getUsage();
    }

    /**
     * @param CommandSender $sender
     * @param string $alias
     */
    public function sendUsage(CommandSender $sender, $alias){
        $message = TextFormat::RED . "Usage: " . TextFormat::GRAY . "/$alias ";
        if(!$sender instanceof Player){
            if($this->consoleUsageMessage === null){
                $message .= str_replace("[player]", "<player>", parent::getUsage());
            }elseif(!$this->consoleUsageMessage){
                $message = TextFormat::RED . "[Error] Please run this command in-game";
            }else{
                $message .= $this->consoleUsageMessage;
            }
        }else{
            $message .= parent::getUsage();
        }
        $sender->sendMessage($message);
    }
}
