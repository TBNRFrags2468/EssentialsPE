<?php
namespace EssentialsPE\BaseFiles;

use EssentialsPE\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand{
    /** @var BaseAPI  */
    private $api;
    /** @var bool|string */
    private $consoleUsageMessage;

    /**
     * @param BaseAPI $api
     * @param string $name
     * @param string $description
     * @param null|string $usageMessage
     * @param bool|null|string $consoleUsageMessage
     * @param array $aliases
     */
    public function __construct(BaseAPI $api, $name, $description = "", $usageMessage = null, $consoleUsageMessage = true, array $aliases = []){
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->api = $api;
        $this->consoleUsageMessage = $consoleUsageMessage;
    }

    /**
     * @return Loader
     */
    public final function getPlugin(){
        return $this->getAPI()->getEssentialsPEPlugin();
    }

    /**
     * @return BaseAPI
     */
    public final function getAPI(){
        return $this->api;
    }

    /**
     * @return string
     */
    public function getUsage(){
        return "/" . parent::getName() . " " . parent::getUsage();
    }

    public function getConsoleUsage(){
        return $this->consoleUsageMessage;
    }

    /**
     * Function to give different type of usages, switching from "Console" and "Player" executors of a command.
     * This function can be overridden to fit any command needs...
     *
     * @param CommandSender $sender
     * @param string $alias
     */
    public function sendUsage(CommandSender $sender, $alias){
        $message = TextFormat::RED . "Usage: " . TextFormat::GRAY . "/$alias ";
        if(!$sender instanceof Player){
            if(is_string($this->consoleUsageMessage)){
                $message .= $this->consoleUsageMessage;
            }elseif(!$this->consoleUsageMessage){
                $message = TextFormat::RED . "[Error] Please run this command in-game";
            }else{
                $message .= str_replace("[player]", "<player>", parent::getUsage());
            }
        }else{
            $message .= parent::getUsage();
        }
        $sender->sendMessage($message);
    }
}
