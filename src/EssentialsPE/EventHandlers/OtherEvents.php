<?php
namespace EssentialsPE\EventHandlers;

use EssentialsPE\Loader;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\math\Vector3;
use pocketmine\network\protocol\UseItemPacket;

class OtherEvents implements Listener{
    /** @var Loader */
    public $plugin;

    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @param ServerCommandEvent $event
     */
    public function onServerCommand(ServerCommandEvent $event){
        $command = $this->plugin->colorMessage($event->getCommand());
        if($command === false){
            $event->setCancelled(true);
        }
        $event->setCommand($command);
    }

    /**
     * @param EntityExplodeEvent $event
     */
    public function onTNTExplode(EntityExplodeEvent $event){
        if($event->getEntity()->namedtag->getName() === "EssNuke"){
            $event->setBlockList([]);
        }
    }

    /**
     * @param PlayerInteractEvent $event
     *
     * @priority HIGH
     */
    public function onBlockTap(PlayerInteractEvent $event){// PowerTool
        if($this->plugin->executePowerTool($event->getPlayer(), $event->getItem())){
            $event->setCancelled(true);
        }
    }

    /**
     * @param BlockPlaceEvent $event
     *
     * @priority HIGH
     */
    public function onBlockPlace(BlockPlaceEvent $event){
        // PowerTool
        if($this->plugin->executePowerTool($event->getPlayer(), $event->getItem())){
            $event->setCancelled(true);
        }

        // Unlimited block placing
        elseif($this->plugin->isUnlimitedEnabled($event->getPlayer())){
            $event->setCancelled(true);
            $pos = new Vector3($event->getBlockReplaced()->getX(), $event->getBlockReplaced()->getY(), $event->getBlockReplaced()->getZ());
            $event->getPlayer()->getLevel()->setBlock($pos, $event->getBlock(), true);
        }
    }

    /**
     * @param DataPacketReceiveEvent $event
     *
     * Special thanks to @PEMapModder for the information!
     */
    public function onPacketReceive(DataPacketReceiveEvent $event){
        $packet = $event->getPacket();
        if(($packet instanceof UseItemPacket && $packet->face ===  0xff) && $this->plugin->executePowerTool($event->getPlayer(), $this->plugin->getItem($packet->item))){
            $event->setCancelled(true);
        }
    }
}