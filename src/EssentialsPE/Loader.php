<?php
namespace EssentialsPE;

use EssentialsPE\BaseFiles\BaseKit;
use EssentialsPE\BaseFiles\BaseLocation;
use EssentialsPE\Commands\AFK;
use EssentialsPE\Commands\Antioch;
use EssentialsPE\Commands\Back;
use EssentialsPE\Commands\BreakCommand;
use EssentialsPE\Commands\Broadcast;
use EssentialsPE\Commands\Burn;
use EssentialsPE\Commands\ClearInventory;
use EssentialsPE\Commands\Compass;
use EssentialsPE\Commands\Condense;
use EssentialsPE\Commands\Depth;
#use EssentialsPE\Commands\Economy\Balance;
#use EssentialsPE\Commands\Economy\Eco;
#use EssentialsPE\Commands\Economy\Pay;
#use EssentialsPE\Commands\Economy\Sell;
#use EssentialsPE\Commands\Economy\SetWorth;
#use EssentialsPE\Commands\Economy\Worth;
use EssentialsPE\Commands\EssentialsPE;
use EssentialsPE\Commands\Extinguish;
use EssentialsPE\Commands\Fly;
use EssentialsPE\Commands\GetPos;
use EssentialsPE\Commands\God;
use EssentialsPE\Commands\Heal;
use EssentialsPE\Commands\Home\DelHome;
use EssentialsPE\Commands\Home\Home;
use EssentialsPE\Commands\Home\SetHome;
use EssentialsPE\Commands\ItemCommand;
use EssentialsPE\Commands\ItemDB;
use EssentialsPE\Commands\Jump;
use EssentialsPE\Commands\KickAll;
use EssentialsPE\Commands\Kit;
use EssentialsPE\Commands\Lightning;
use EssentialsPE\Commands\More;
use EssentialsPE\Commands\Mute;
use EssentialsPE\Commands\Near;
use EssentialsPE\Commands\Nick;
use EssentialsPE\Commands\Nuke;
use EssentialsPE\Commands\Override\Gamemode;
use EssentialsPE\Commands\Override\Kill;
use EssentialsPE\Commands\Override\Msg;
use EssentialsPE\Commands\Ping;
use EssentialsPE\Commands\PowerTool\PowerTool;
use EssentialsPE\Commands\PowerTool\PowerToolToggle;
use EssentialsPE\Commands\PTime;
use EssentialsPE\Commands\PvP;
use EssentialsPE\Commands\RealName;
use EssentialsPE\Commands\Repair;
use EssentialsPE\Commands\Reply;
use EssentialsPE\Commands\Seen;
use EssentialsPE\Commands\SetSpawn;
use EssentialsPE\Commands\Spawn;
use EssentialsPE\Commands\Sudo;
use EssentialsPE\Commands\Suicide;
use EssentialsPE\Commands\Teleport\TPA;
use EssentialsPE\Commands\Teleport\TPAccept;
use EssentialsPE\Commands\Teleport\TPAHere;
use EssentialsPE\Commands\Teleport\TPAll;
use EssentialsPE\Commands\Teleport\TPDeny;
use EssentialsPE\Commands\Teleport\TPHere;
use EssentialsPE\Commands\TempBan;
use EssentialsPE\Commands\Top;
use EssentialsPE\Commands\Unlimited;
use EssentialsPE\Commands\Vanish;
use EssentialsPE\Commands\Warp\DelWarp;
use EssentialsPE\Commands\Warp\Setwarp;
use EssentialsPE\Commands\Warp\Warp;
use EssentialsPE\Commands\World;
use EssentialsPE\EventHandlers\OtherEvents;
use EssentialsPE\EventHandlers\PlayerEvents;
use EssentialsPE\EventHandlers\SignEvents;
use pocketmine\entity\Entity;
use pocketmine\level\Level;
use pocketmine\nbt\tag\Enum;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\network\protocol\MobEffectPacket;
use pocketmine\permission\Permission;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Loader extends PluginBase{
    /** @var Config */
    private $economy;

    /** @var array */
    private $kits = [];

    /** @var array */
    private $warps = [];

    public function onEnable(){
        if(!is_dir($this->getDataFolder())){
            mkdir($this->getDataFolder());
        }
        $this->checkConfig();
        $this->saveConfigs();
	    $this->getLogger()->info(TextFormat::YELLOW . "Loading...");
        $this->registerEvents();
        $this->registerCommands();
        if(count($l = $this->getServer()->getOnlinePlayers()) > 0){
            $this->createSession($l);
        }
        if($this->isUpdaterEnabled()){
            $this->fetchEssentialsPEUpdate(false);
        }
        $this->scheduleAutoAFKSetter();
    }

    public function onDisable(){
        if(count($l = $this->getServer()->getOnlinePlayers()) > 0){
            $this->removeSession($l);
        }
        $this->encodeWarps(true);
    }

    /**
     * Function to register all the Event Handlers that EssentialsPE provide
     */
    public function registerEvents(){
        $this->getServer()->getPluginManager()->registerEvents(new OtherEvents($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerEvents($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new SignEvents($this), $this);
    }

    /**
     * Function to easily disable commands
     *
     * @param array $commands
     */
    private function unregisterCommands(array $commands){
        $commandmap = $this->getServer()->getCommandMap();

        foreach($commands as $commandlabel){
            $command = $commandmap->getCommand($commandlabel);
            $command->setLabel($commandlabel . "_disabled");
            $command->unregister($commandmap);
        }
    }

    /**
     * Function to register all EssentialsPE's commands...
     * And to override some default ones
     */
    private function registerCommands(){
        //Unregister commands to override
        $this->unregisterCommands([
           "gamemode",
            "kill",
            "tell"
        ]);

        //Register the new commands
        $this->getServer()->getCommandMap()->registerAll("EssentialsPE", [
            new AFK($this),
            new Antioch($this),
            new Back($this),
            //new BigTreeCommand($this), TODO
            new BreakCommand($this),
            new Broadcast($this),
            new Burn($this),
            new ClearInventory($this),
            new Compass($this),
            new Condense($this),
            new Depth($this),
            new EssentialsPE($this),
            new Extinguish($this),
            new Fly($this),
            new GetPos($this),
            new God($this),
            //new Hat($this), TODO: Implement when MCPE implements "Hat rendering"
            new Heal($this),
            new ItemCommand($this),
            new ItemDB($this),
            new Jump($this),
            new KickAll($this),
            new Kit($this),
            new Lightning($this),
            new More($this),
            new Mute($this),
            new Near($this),
            new Nick($this),
            new Nuke($this),
            new Ping($this),
            new PTime($this),
            new PvP($this),
            new RealName($this),
            new Repair($this),
            new Seen($this),
            new SetSpawn($this),
            new Spawn($this),
            //new Speed($this), TODO
            new Sudo($this),
            new Suicide($this),
            new TempBan($this),
            new Top($this),
            //new TreeCommand($this), TODO
            new Unlimited($this),
            new Vanish($this),
            //new Whois($this), TODO
            new World($this),

            //Economy
            //new Balance($this),
            //new Eco($this),
            //new Pay($this),
            //new Sell($this),
            //new SetWorth($this),
            //new Worth($this),

            //Home
            new DelHome($this),
            new Home($this),
            new SetHome($this),

            // Messages
            new Msg($this),
            new Reply($this),

            //PowerTool
            new PowerTool($this),
            new PowerToolToggle($this),

            //Teleport
            new TPA($this),
            new TPAccept($this),
            new TPAHere($this),
            new TPAll($this),
            new TPDeny($this),
            new TPHere($this),

            //Warp
            new DelWarp($this),
            new Setwarp($this),
            new Warp($this),

            //Override
            new Gamemode($this),
            new Kill($this)
        ]);
    }

    public function checkConfig(){
        if(!file_exists($this->getDataFolder() . "config.yml")){
            $this->saveDefaultConfig();
        }
        //$this->saveResource("Economy.yml");
        $this->saveResource("Kits.yml");
        $cfg = $this->getConfig();

        if(!$cfg->exists("version") || $cfg->get("version") !== "0.0.2"){
            $this->getLogger()->debug(TextFormat::RED . "An invalid config file was found, generating a new one...");
            unlink($this->getDataFolder() . "config.yml");
            $this->saveDefaultConfig();
            $cfg = $this->getConfig();
        }

        $booleans = ["enable-custom-colors"];
        foreach($booleans as $key){
            $value = null;
            if(!$cfg->exists($key) || !is_bool($cfg->get($key))){
                switch($key){
                    // Properties to auto set true
                    case "safe-afk":
                        $value = true;
                        break;
                    // Properties to auto set false
                    case "enable-custom-colors":
                        $value = false;
                        break;
                }
            }
            if($value !== null){
                $cfg->set($key, $value);
            }
        }

        $integers = ["oversized-stacks", "near-radius-limit", "near-default-radius"];
        foreach($integers as $key){
            $value = null;
            if(!is_numeric($cfg->get($key))){
                switch($key){
                    case "auto-afk-kick":
                        $value = 300;
                        break;
                    case "oversized-stacks":
                        $value = 64;
                        break;
                    case "near-radius-limit":
                        $value = 200;
                        break;
                    case "near-default-radius":
                        $value = 100;
                        break;
                }
            }
            if($value !== null){
                $cfg->set($key, $value);
            }
        }

        $afk = ["safe", "auto-set", "auto-broadcast", "auto-kick", "broadcast"];
        foreach($afk as $key){
            $value = null;
            $k = $this->getConfig()->getNested("afk." . $key);
            switch($key){
                case "safe":
                case "auto-broadcast":
                case "broadcast":
                    if(!is_bool($k)){
                        $value = true;
                    }
                    break;
                case "auto-set":
                case "auto-kick":
                    if(!is_int($k)){
                        $value = 300;
                    }
                    break;
            }
            if($value !== null){
                $this->getConfig()->setNested("afk." . $key, $value);
            }
        }

        $updater = ["enabled", "time-interval", "warn-console", "warn-players", "stable"];
        foreach($updater as $key){
            $value = null;
            $k = $this->getConfig()->getNested("updater." . $key);
            switch($key){
                case "time-interval":
                    if(!is_int($k)){
                        $value = 1800;
                    }
                    break;
                case "enabled":
                case "warn-console":
                case "warn-players":
                case "stable":
                    if(!is_bool($k)){
                        $value = true;
                    }
                    break;
            }
            if($value !== null){
                $this->getConfig()->setNested("updater." . $key, $value);
            }
        }
    }

    private function saveConfigs(){
        /**$this->economy = new Config($this->getDataFolder() . "Economy.yml", Config::YAML);
        $keys = ["default-balance", "max-money", "min-money"];
        foreach($keys as $k){
            if(!is_int($k)){
                $value = 0;
                switch($k){
                    case "default-balance":
                        $value = 0;
                        break;
                    case "max-money":
                        $value = 10000000000000;
                        break;
                    case "min-money":
                        $value = -10000;
                        break;
                }
                $this->economy->set($k, $value);
            }
        }*/

        $this->loadKits();
        $this->loadWarps();
        $this->updateHomesAndNicks();
    }

    private function updateHomesAndNicks(){
        if(file_exists($f = $this->getDataFolder() . "Homes.yml")){
            $cfg = new Config($f, Config::YAML);
            foreach($cfg->getAll() as $player => $home){
                if(is_array($home)){
                    continue;
                }
                $pCfg = $this->getSessionFile($player);
                foreach($home as $name => $values){
                    if(!$this->validateName($name, false) || !is_array($values)){
                        continue;
                    }
                    $pCfg->setNested("homes." . $name, $values);
                }
                $pCfg->save();
            }
            unlink($f);
        }
        if(file_exists($f = $this->getDataFolder() . "Nicks.yml")){
            $cfg = new Config($f, Config::YAML);
            foreach($cfg->getAll() as $player => $nick){
                $pCfg = $this->getSessionFile($player);
                $pCfg->set("nick", $nick);
                $pCfg->save();
            }
            unlink($f);
        }
    }

    private function loadKits(){
        $cfg = new Config($this->getDataFolder() . "Kits.yml", Config::YAML);
        $children = [];
        foreach($cfg->getAll() as $n => $i){
            $this->kits[$n] = new BaseKit($n, $i);
            $children[] = new Permission("essentials.kits." . $n);
        }
        $this->getServer()->getPluginManager()->addPermission(new Permission("essentials.kits", null, null, $children));
    }

    private function loadWarps(){
        $cfg = new Config($this->getDataFolder() . "Warps.yml", Config::YAML);
        $cfg->reload();
        $children = [];
        foreach($cfg->getAll() as $n => $v){
            if($this->getServer()->isLevelGenerated($v[3])){
                if(!$this->getServer()->isLevelLoaded($v[3])){
                    $this->getServer()->loadLevel($v[3]);
                }
                $this->warps[$n] = new BaseLocation($n, $v[0], $v[1], $v[2], $this->getServer()->getLevelByName($v[3]), $v[4], $v[5]);
                $children[] = new Permission("essentials.warps." . $n);
            }
        }
        $this->getServer()->getPluginManager()->addPermission(new Permission("essentials.warps", null, null, $children));
    }

    /**
     * @param bool $save
     */
    private function encodeWarps($save = false){
        $warps = [];
        foreach($this->warps as $name => $object){
            if($object instanceof BaseLocation){
                $warps[$name] = [$object->getX(), $object->getY(), $object->getZ(), $object->getLevel()->getName(), $object->getYaw(), $object->getPitch()];
            }
        }
        if($save){
            $cfg = new Config($this->getDataFolder() . "Warps.yml", Config::YAML);
            $cfg->setAll($warps);
            $cfg->save();
        }
        $this->warps = $warps;
    }

    public function reloadFiles(){
        $this->getConfig()->reload();
        //$this->economy->reload();
        $this->loadKits();
        $this->loadWarps();
        $this->updateHomesAndNicks();
    }

    public function getAPI(){
        // TODO
    }
}
