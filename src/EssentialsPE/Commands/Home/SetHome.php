<?php
namespace EssentialsPE\Commands\Home;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class SetHome extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "sethome", "Create or update a home position", "<name>", false, ["createhome"]);
        $this->setPermission("essentials.sethome");
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
        if(!$sender instanceof Player || count($args) !== 1){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(strtolower($args[0]) === "bed"){
            $sender->sendMessage(TextFormat::RED . "[Error] You can only set a \"bed\" home by sleeping in a bed");
            return false;
        }elseif(trim($args[0] === "")){
            $sender->sendMessage(TextFormat::RED . "[Error] Please provide a Home name");
            return false;
        }
        if(!$this->getAPI()->setHome($sender, strtolower($args[0]), $sender->getLocation(), $sender->getYaw(), $sender->getPitch())){
            $sender->sendMessage(TextFormat::RED . "Invalid home name given! Please be sure to only use alphanumerical characters and underscores");
            return false;
        }
        $sender->sendMessage(TextFormat::GREEN . "Home successfully " . ($this->getAPI()->homeExists($sender, $args[0]) ? "updated" : "created"));
        return true;
    }
} 
