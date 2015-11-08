<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseFiles\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\block\Block;
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
        if(!$sender instanceof Player){
            $this->sendUsage($sender, $alias);
            return false;
        }
        if(count($args) !== 0){
            $this->sendUsage($sender, $alias);
        }
        $transparent = [Block::SAPLING, Block::WATER, Block::STILL_WATER, Block::LAVA, Block::STILL_LAVA, Block::COBWEB, Block::TALL_GRASS, Block::BUSH, Block::DANDELION,
            Block::POPPY, Block::BROWN_MUSHROOM, Block::RED_MUSHROOM, Block::TORCH, Block::FIRE, Block::WHEAT_BLOCK, Block::SIGN_POST, Block::WALL_SIGN, Block::SUGARCANE_BLOCK,
            Block::PUMPKIN_STEM, Block::MELON_STEM, Block::VINE, Block::CARROT_BLOCK, Block::POTATO_BLOCK, Block::DOUBLE_PLANT];
        $block = $sender->getTargetBlock(100, $transparent);
        if($block === null){
            $sender->sendMessage(TextFormat::RED . "There isn't a reachable block");
            return false;
        }
        if(!$sender->getLevel()->getBlock($block->add(0, 2))->isSolid()){
            $sender->teleport($block->add(0, 1));
            return true;
        }

        $side = $sender->getDirection();
        if($side === 0){
            $side = 3;
        }elseif($side === 1){
            $side = 4;
        }elseif($side === 2){
            $side = 2;
        }elseif($side === 3){
            $side = 5;
        }
        if(!$block->getSide($side)->isSolid()){
            $sender->teleport($block);
        }
        return true;
    }
}
