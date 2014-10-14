<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\block\Block;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Jump extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "jump", "Teleport you to the block you're looking at", "/jump", ["j", "jumpto"]);
        $this->setPermission("essentials.jump");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage(TextFormat::RED . "Please run this command in-game");
            return false;
        }
        if(count($args) !== 0){
            $sender->sendMessage(TextFormat::RED . $this->getUsage());
        }
        $transparent = [];
        foreach(Block::$list as $block){
            if(!$block->isSolid){
                $transparent[] = $block;
            }
        }
        $block = $sender->getTargetBlock($this->getPlugin()->getConfig()->get("jump-distance"), $transparent);
        //TODO Check for secure teleport
        $sender->teleport(new Vector3($block->getX(), $block->getY() + 1, $block->getZ()));
        return true;
    }
}
