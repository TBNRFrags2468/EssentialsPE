<?php
namespace EssentialsPE;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBedEnterEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\ServerCommandEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat;

class EventHandler implements Listener{
    /** @var Loader */
    public $plugin;

    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerPreLoginEvent $event
     *
     * @priority MONITOR
     * @ignoreCancelled true
     */
    public function onPlayerPreLogin(PlayerPreLoginEvent $event){
        // Ban remove:
        if($event->getPlayer()->isBanned() && $event->getPlayer()->hasPermission("essentials.ban.exempt")){
            $event->getPlayer()->setBanned(false);
        }
        // Session configure:
        $this->plugin->muteSessionCreate($event->getPlayer());
        $this->plugin->createSession($event->getPlayer());
        // Nick and NameTag set:
        $this->plugin->setNick($event->getPlayer(), $this->plugin->getNick($event->getPlayer()), false);
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
        // Nick and NameTag set:
        $event->setJoinMessage(TextFormat::GREEN . $event->getPlayer()->getDisplayName() . " joined the game");
        // Hide vanished players
        foreach($event->getPlayer()->getServer()->getOnlinePlayers() as $p){
            if($this->plugin->isVanished($p)){
                $event->getPlayer()->hidePlayer($p);
            }
        }
        //$this->plugin->setPlayerBalance($event->getPlayer(), $this->plugin->getDefaultBalance()); TODO
    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function onPlayerQuit(PlayerQuitEvent $event){
        // Quit message (nick):
        $event->setQuitMessage(TextFormat::YELLOW . $event->getPlayer()->getDisplayName() . " left the game");
        // Nick and NameTag restore:
        $this->plugin->setNick($event->getPlayer(), $event->getPlayer()->getName(), false);

        // Sessions
        if($this->plugin->sessionExists($event->getPlayer())){
            // Remove teleport requests
            $this->plugin->removeTPRequest($event->getPlayer());
            // Session destroy:
            $this->plugin->removeSession($event->getPlayer());
        }
    }

    /**
     * @param PlayerChatEvent $event
     */
    public function onPlayerChat(PlayerChatEvent $event){
        if($this->plugin->isMuted($event->getPlayer())){
            $event->setCancelled(true);
        }
    }

    /**
     * @param PlayerCommandPreprocessEvent $event
     */
    public function onPlayerCommand(PlayerCommandPreprocessEvent $event){
        $command = $this->plugin->colorMessage($event->getMessage(), $event->getPlayer());
        if($command === false){
            $event->setCancelled(true);
        }
        $event->setMessage($command);
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
     * @param PlayerMoveEvent $event
     */
    public function onPlayerMove(PlayerMoveEvent $event){
        $entity = $event->getPlayer();
        if($this->plugin->isAFK($entity)){
            $this->plugin->setAFKMode($entity, false);
            $entity->sendMessage(TextFormat::GREEN . "You're no longer AFK");
            foreach($entity->getServer()->getOnlinePlayers() as $p){
                if($p !== $entity){
                    $p->sendMessage(TextFormat::GREEN . $entity->getDisplayName() . " is no longer AFK");
                }
            }
            $entity->getServer()->getLogger()->info(TextFormat::GREEN . $entity->getDisplayName() . " is no longer AFK");
        }
    }

    /**
     * @param EntityTeleportEvent $event
     */
    public function onEntityTeleport(EntityTeleportEvent $event){
        $entity = $event->getEntity();
        if($entity instanceof Player){
            $this->plugin->setPlayerLastPosition($entity, $entity->getPosition(), $entity->getYaw(), $entity->getPitch());
        }
    }

    /**
     * @param EntityLevelChangeEvent $event
     *
     * @priority HIGHEST
     */
    public function onEntityLevelChange(EntityLevelChangeEvent $event){
        $entity = $event->getEntity();
        if($entity instanceof Player){
            $this->plugin->switchLevelVanish($entity, $event->getOrigin(), $event->getTarget());
        }
    }

    /**
     * @param PlayerBedEnterEvent $event
     */
    public function onPlayerSleep(PlayerBedEnterEvent $event){
        if($event->getPlayer()->hasPermission("essentials.home.bed")){
            $this->plugin->setHome($event->getPlayer(), "bed", $event->getPlayer()->getPosition());
        }
    }

    /**
     * @param EntityDamageEvent $event
     *
     * @priority HIGH
     */
    public function onEntityDamageByEntity(EntityDamageEvent $event){
        $victim = $event->getEntity();
        if($event instanceof EntityDamageByEntityEvent){
            $issuer = $event->getDamager();
            if($victim instanceof Player && $issuer instanceof Player){
                if($this->plugin->isGod($victim)){
                    $event->setCancelled(true);
                }elseif($this->plugin->isGod($issuer) && !$issuer->hasPermission("essentials.god.pvp")){
                    $event->setCancelled(true);
                }

                if($this->plugin->isVanished($issuer) && !$issuer->hasPermission("essentials.vanish.pvp")){
                    $event->setCancelled(true);
                }

                if(!$this->plugin->isPvPEnabled($issuer)){
                    $issuer->sendMessage(TextFormat::RED . "You have PvP disabled!");
                    $event->setCancelled(true);
                }elseif(!$this->plugin->isPvPEnabled($victim)){
                    $issuer->sendMessage(TextFormat::RED . $victim->getDisplayName() . " has PvP disabled!");
                    $event->setCancelled(true);
                }
            }
        }
    }

    /**
     * @param PlayerDeathEvent $event
     */
    public function onPlayerDeath(PlayerDeathEvent $event){
        if($event->getEntity()->hasPermission("essentials.back.ondeath")){
            $this->plugin->setPlayerLastPosition($event->getEntity(), $event->getEntity()->getPosition(), $event->getEntity()->getYaw(), $event->getEntity()->getPitch());
        }else{
            $this->plugin->removePlayerLastPosition($event->getEntity());
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @return bool
     */
    public function onBlockTap(PlayerInteractEvent $event){// PowerTool
        if($event->getItem()->isPlaceable()){
            if($this->plugin->executePowerTool($event->getPlayer(), $event->getItem())){
                $event->setCancelled(true);
            }
        }


        // Special Signs
        $tile = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->getFloorX(), $event->getBlock()->getFloorY(), $event->getBlock()->getFloorZ()));
        if($tile instanceof Sign){
            // Free sign
            // TODO Implement costs
            if($tile->getText()[0] === "[Free]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.free")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }else{
                    if($event->getPlayer()->getGamemode() === 1 || $event->getPlayer()->getGamemode() === 3){
                        $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] You're in " . $event->getPlayer()->getServer()->getGamemodeString($event->getPlayer()->getGamemode()) . " mode");
                        return false;
                    }

                    $item_name = $tile->getText()[1];
                    $damage = $tile->getText()[2];

                    if(!is_numeric($item_name)){
                        $item = Item::fromString($item_name);
                    }else{
                        $item = Item::get($item_name);
                    }
                    $item->setDamage($damage);

                    $event->getPlayer()->getInventory()->addItem($item);
                    $event->getPlayer()->sendMessage(TextFormat::YELLOW . "Giving " . TextFormat::RED . $item->getCount() . TextFormat::YELLOW . " of " . TextFormat::RED .( $item->getName() === "Unknown" ? $item_name : $item->getName()));
                }
            }

            // Gamemode sign
            // TODO Implement costs
            elseif($tile->getText()[0] === "[Gamemode]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.gamemode")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }else{
                    $v = strtolower($tile->getText()[1]);
                    if($v === "survival"){
                        $event->getPlayer()->setGamemode(0);
                    }elseif($v === "creative"){
                        $event->getPlayer()->setGamemode(1);
                    }elseif($v === "adventure"){
                        $event->getPlayer()->setGamemode(2);
                    }elseif($v === "spectator"){
                        $event->getPlayer()->setGamemode(3);
                    }
                }
            }

            // Heal sign
            // TODO Implement costs
            elseif($tile->getText()[0] === "[Heal]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.heal")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }elseif($event->getPlayer()->getGamemode() === 1 || $event->getPlayer()->getGamemode() === 3){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] You're in " . $event->getPlayer()->getServer()->getGamemodeString($event->getPlayer()->getGamemode()) . " mode");
                    return false;
                }else{

                    $event->getPlayer()->heal($event->getPlayer()->getMaxHealth());
                    $event->getPlayer()->sendMessage(TextFormat::GREEN . "You have been healed!");
                }
            }

            // Repair sign
            // TODO Implement costs
            elseif($tile->getText()[0] === "[Repair]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.repair")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }elseif($event->getPlayer()->getGamemode() === 1 || $event->getPlayer()->getGamemode() === 3){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] You're in " . $event->getPlayer()->getServer()->getGamemodeString($event->getPlayer()->getGamemode()) . " mode");
                    return false;
                }else{
                    if(($v = $tile->getText()[1]) === "Hand"){
                        if($this->plugin->isReparable($item = $event->getPlayer()->getInventory()->getItemInHand())){
                            $item->setDamage(0);
                            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Item successfully repaired!");
                        }
                    }elseif($v === "All"){
                        foreach($event->getPlayer()->getInventory()->getContents() as $item){
                            if($this->plugin->isReparable($item)){
                                $item->setDamage(0);
                            }
                        }
                        foreach($event->getPlayer()->getInventory()->getArmorContents() as $item){
                            if($this->plugin->isReparable($item)){
                                $item->setDamage(0);
                            }
                        }
                        $event->getPlayer()->sendMessage(TextFormat::GREEN . "All the tools on your inventory were repaired!" . TextFormat::AQUA . "\n(including the equipped Armor)");
                    }
                }
            }

            // Time sign
            // TODO Implement costs
            elseif($tile->getText()[0] === "[Time]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.time")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }else{
                    if(($v = $tile->getText()[1]) === "Day"){
                        $event->getPlayer()->getLevel()->setTime(0);
                        $event->getPlayer()->sendMessage(TextFormat::GREEN . "Time set to \"Day\"");
                    }elseif($v === "Night"){
                        $event->getPlayer()->getLevel()->setTime(12500);
                        $event->getPlayer()->sendMessage(TextFormat::GREEN . "Time set to \"Night\"");
                    }
                }
            }

            // Teleport sign
            // TODO Implement costs
            elseif($tile->getText()[0] === "[Teleport]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.teleport")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }else{
                    $event->getPlayer()->teleport(new Vector3($x = $tile->getText()[1], $y = $tile->getText()[2], $z = $tile->getText()[3]));
                    $event->getPlayer()->sendMessage(TextFormat::GREEN . "Teleporting to " . TextFormat::AQUA . $x . TextFormat::GREEN . ", " . TextFormat::AQUA . $y . TextFormat::GREEN . ", " . TextFormat::AQUA . $z);
                }
            }

            // Warp sign
            // TODO Implement costs
            elseif($tile->getText()[0] === "[Warp]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.warp")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }else{
                    $warp = $this->plugin->getWarp($tile->getText()[1]);
                    if(!$warp){
                        $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Warp doesn't exists");
                        return false;
                    }
                    if(!$event->getPlayer()->hasPermission("essentials.warps.*") && !$event->getPlayer()->hasPermission("essentials.warps." . $tile->getText()[1])){
                        $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] You can't teleport to that warp");
                        return false;
                    }
                    $event->getPlayer()->teleport($warp[0], $warp[1], $warp[2]);
                    $event->getPlayer()->sendMessage(TextFormat::GREEN . "Warping to " . $tile->getText()[1] . "...");
                }
            }

            /**
             * Economy signs
             */

            // Balance sign
            /**elseif($tile->getText()[0] === "[Balance]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("essentials.sign.use.balance")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                }else{
                    $event->getPlayer()->sendMessage(TextFormat::AQUA . "Your current balance is " . TextFormat::YELLOW . $this->plugin->getCurrencySymbol() . $this->plugin->getPlayerBalance($event->getPlayer()));
                }
            }*/

            /**
             * TODO Implement:
             * - Buy sign
             * - Sell sign
             */
        }
        return true;
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
     * @param BlockBreakEvent $event
     *
     * @priority HIGH
     */
    public function onBlockBreak(BlockBreakEvent $event){
        // Special Signs
        $tile = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->getFloorX(), $event->getBlock()->getFloorY(), $event->getBlock()->getFloorZ()));
        if($tile instanceof Sign){

            // Free sign
            if($tile->getText()[0] === "[Free]" && !$event->getPlayer()->hasPermission("essentials.sign.break.free")){
                $event->setCancelled(true);
                $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to break this sign");
            }

            // Gamemode sign
            elseif($tile->getText()[0] === "[Gamemode]" && !$event->getPlayer()->hasPermission("essentials.sign.break.gamemode")){
                $event->setCancelled(true);
                $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to break this sign");
            }

            // Heal sign
            elseif($tile->getText()[0] === "[Heal]" && !$event->getPlayer()->hasPermission("essentials.sign.break.heal")){
                $event->setCancelled(true);
                $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to break this sign");
            }

            // Repair sign
            elseif($tile->getText()[0] === "[Repair]" && !$event->getPlayer()->hasPermission("essentials.sign.break.repair")){
                $event->setCancelled(true);
                $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to break this sign");
            }

            // Time sign
            elseif($tile->getText()[0] === "[Time]" && !$event->getPlayer()->hasPermission("essentials.sign.break.time")){
                $event->setCancelled(true);
                $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to break this sign");
            }

            // Teleport sign
            elseif($tile->getText()[0] === "[Teleport]" && !$event->getPlayer()->hasPermission("essentials.sign.break.teleport")){
                $event->setCancelled(true);
                $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to break this sign");
            }

            // Warp sign
            elseif($tile->getText()[0] === "[Warp]" && !$event->getPlayer()->hasPermission("essentials.sign.break.warp")){
                $event->setCancelled(true);
                $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to break this sign");
            }
        }
    }

    /**
     * @param SignChangeEvent $event
     * @return bool
     */
    public function onSignChange(SignChangeEvent $event){
        // Colored Sign
        if($event->getPlayer()->hasPermission("essentials.sign.color")){
            $event->setLine(0, $this->plugin->colorMessage($event->getLine(0)));
            $event->setLine(1, $this->plugin->colorMessage($event->getLine(1)));
            $event->setLine(2, $this->plugin->colorMessage($event->getLine(2)));
            $event->setLine(3, $this->plugin->colorMessage($event->getLine(3)));
        }

        // Special Signs

        // Free sign
        if(strtolower($event->getLine(0)) === "[free]" && $event->getPlayer()->hasPermission("essentials.sign.create.free")){
            if(trim($event->getLine(1)) !== "" || $event->getLine(1) !== null){
                $item_name = $event->getLine(1);

                if(trim($event->getLine(2)) !== "" || $event->getLine(2) !== null){
                    $damage = $event->getLine(2);
                }else{
                    $damage = 0;
                }

                if(!is_numeric($item_name)){
                    $item = Item::fromString($item_name);
                }else{
                    $item = Item::get($item_name);
                }

                if($item->getID() === 0 || $item->getName() === "Air"){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Invalid item name/ID");
                    $event->setCancelled(true);
                }else{
                    $event->getPlayer()->sendMessage(TextFormat::GREEN . "Free sign successfully created!");
                    $event->setLine(0, "[Free]");
                    $event->setLine(1, ($item->getName() === "Unknown" ? $item->getID() : $item->getName()));
                    $event->setLine(2, $damage);
                }
            }else{
                $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] You should provide an item name/ID");
                $event->setCancelled(true);
            }
        }

        // Gamemode sign
        elseif(strtolower($event->getLine(0)) === "[gamemode]" && $event->getPlayer()->hasPermission("essentials.sign.create.gamemode")){
            switch(strtolower($event->getLine(1))){
                case "survival":
                case "0":
                    $event->setLine(1, "Survival");
                    break;
                case "creative":
                case "1":
                    $event->setLine(1, "Creative");
                    break;
                case "adventure":
                case "2":
                    $event->setLine(1, "Adventure");
                    break;
                case "spectator":
                case "view":
                case "3":
                    $event->setLine(1, "Spectator");
                    break;
                default:
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Unknown Gamemode, you should use \"Survival\", \"Creative\", \"Adventure\" or \"Spectator\"");
                    $event->setCancelled(true);
                    return false;
                    break;
            }
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Gamemode sign successfully created!");
            $event->setLine(0, "[Gamemode]");
        }

        // Heal sign
        elseif(strtolower($event->getLine(0)) === "[heal]" && $event->getPlayer()->hasPermission("essentials.sign.create.heal")){
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Heal sign successfully created!");
            $event->setLine(0, "[Heal]");
        }

        // Repair sign
        elseif(strtolower($event->getLine(0)) === "[repair]" && $event->getPlayer()->hasPermission("essentials.sign.create.repair")){
            switch(strtolower($event->getLine(1))){
                case "hand":
                    $event->setLine(1, "Hand");
                    break;
                case "all":
                    $event->setLine(1, "All");
                    break;
                default:
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Invalid argument, you should use \"Hand\" or \"All\"");
                    $event->setCancelled(true);
                    return false;
                    break;
            }
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Repair sign successfully created!");
            $event->setLine(0, "[Repair]");
        }

        // Time sign
        elseif(strtolower($event->getLine(0)) === "[time]" && $event->getPlayer()->hasPermission("essentials.sign.create.time")){
            switch(strtolower($event->getLine(1))){
                case "day":
                    $event->setLine(1, "Day");
                    break;
                case "night";
                    $event->setLine(1, "Night");
                    break;
                default:
                    $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Invalid time, you should use \"Day\" or \"Night\"");
                    $event->setCancelled(true);
                    return false;
                    break;
            }
            $event->getPlayer()->sendMessage(TextFormat::GREEN . "Time sign successfully created!");
            $event->setLine(0, "[Time]");
        }

        // Teleport sign
        elseif(strtolower($event->getLine(0)) === "[teleport]" && $event->getPlayer()->hasPermission("essentials.sign.create.teleport")){
            if(!is_numeric($event->getLine(1))){
                $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Invalid X position, Teleport sign will not work");
                $event->setCancelled(true);
            }elseif(!is_numeric($event->getLine(2))){
                $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Invalid Y position, Teleport sign will not work");
                $event->setCancelled(true);
            }elseif(!is_numeric($event->getLine(3))){
                $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Invalid Z position, Teleport sign will not work");
                $event->setCancelled(true);
            }else{
                $event->getPlayer()->sendMessage(TextFormat::GREEN . "Teleport sign successfully created!");
                $event->setLine(0, "[Teleport]");
                $event->setLine(1, $event->getLine(1));
                $event->setLine(2, $event->getLine(2));
                $event->setLine(3, $event->getLine(3));
            }
        }

        // Warp sign
        elseif(strtolower($event->getLine(0)) === "[warp]" && $event->getPlayer()->hasPermission("essentials.sign.create.warp")){
            $warp = $event->getLine(1);
            if(!$this->plugin->warpExists($warp)){
                $event->getPlayer()->sendMessage(TextFormat::RED . "[Error] Warp doesn't exists");
                $event->setCancelled(true);
            }else{
                $event->getPlayer()->sendMessage(TextFormat::GREEN . "Warp sign successfully created!");
                $event->setLine(0, "[Warp]");
            }
        }
        return true;
    }

    /**
     * @param EntityExplodeEvent $event
     */
    public function onTNTExplode(EntityExplodeEvent $event){
        if($event->getEntity()->namedtag->getName() === "EssNuke"){
            $event->setBlockList([]);
        }
    }
}