<?php
namespace EssentialsPE\Commands\Teleport;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class TPAccept extends BaseCommand{
    /**
     * @param BaseAPI $api
     */
    public function __construct(BaseAPI $api){
        parent::__construct($api, "tpaccept", "Accept a teleport request", "[player]", false, ["tpyes"]);
        $this->setPermission("essentials.tpaccept");
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
        }
        $request = $this->getAPI()->hasARequest($sender);
        if(!$request){
            $sender->sendMessage(TextFormat::RED . "[Error] You don't have any request yet");
            return false;
        }
        switch(count($args)){
            case 0:
                $player = $this->getAPI()->getPlayer(($name = $this->getAPI()->getLatestRequest($sender)));
                if(!$player){
                    $sender->sendMessage(TextFormat::RED . "[Error] Request unavailable");
                    return false;
                }
                $player->sendMessage(TextFormat::AQUA . $sender->getDisplayName() . TextFormat::GREEN . " accepted your teleport request! Teleporting...");
                $sender->sendMessage(TextFormat::GREEN . "Teleporting...");
                if($request[$name] === "tpto"){
                    $player->teleport($sender);
                }else{
                    $sender->teleport($player);
                }
                $this->getAPI()->removeTPRequest($player, $sender);
                break;
            case 1:
                $player = $this->getAPI()->getPlayer($args[0]);
                if(!$player) {
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                    return false;
                }
                if(!($request = $this->getAPI()->hasARequestFrom($sender, $player))){
                    $sender->sendMessage(TextFormat::RED . "[Error] You don't have any requests from " . TextFormat::AQUA . $player->getDisplayName());
                    return false;
                }
                $player->sendMessage(TextFormat::AQUA . $sender->getDisplayName() . TextFormat::GREEN . " accepted your teleport request! Teleporting...");
                $sender->sendMessage(TextFormat::GREEN . "Teleporting...");
                if($request === "tpto"){
                    $player->teleport($sender);
                }else{
                    $sender->teleport($player);
                }
                $this->getAPI()->removeTPRequest($player, $sender);
                break;
            default:
                $this->sendUsage($sender, $alias);
                return false;
                break;
        }
        return true;
    }
} 