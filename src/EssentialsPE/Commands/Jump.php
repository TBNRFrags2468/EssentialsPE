<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Jump extends BaseCommand{
    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "jump", "Teleport you to the block you're looking at", null, false, ["j", "jumpto"]);
        $this->setPermission("essentials.jump");
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
        $block = $sender->getTargetBlock(100, Loader::TRANSPARENT_BLOCKS_JUMP);
        if($block === null){
            $sender->sendMessage(TextFormat::RED . "There isn't a reachable block");
            return false;
        }
        if(!$sender->getLevel()->getBlock($block->add(0, 2))->isSolid()){
            $sender->teleport($block->add(0, 1));
            return true;
        }

        switch($side = $sender->getDirection()){
            case 0:
            case 1:
                $side += 3;
                break;
            case 3:
                $side += 2;
                break;
            default:
                break;
        }
        if(!$block->getSide($side)->isSolid()){
            $sender->teleport($block);
        }
        return true;
    }
}
