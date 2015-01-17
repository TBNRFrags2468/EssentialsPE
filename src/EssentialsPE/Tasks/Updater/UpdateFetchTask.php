<?php
namespace EssentialsPE\Tasks\Updater;

use EssentialsPE\Loader;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class UpdateFetchTask extends AsyncTask{
    /** @var string */
    private $build;
    /** @var bool */
    private $install;

    public function __construct($build, $install = false){
        $this->build = $build;
        $this->install = $install;
    }

    public function onRun(){
        if($this->build === "beta"){
            $ch = curl_init("https://api.github.com/repos/LegendOfMCPE/EssentialsPE/releases"); // Github repository for 'Beta' releases
        }else{
            $ch = curl_init("http://forums.pocketmine.net/api.php?action=getResource&value=886"); // PocketMine repository for 'Stable' releases
        }
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $i = json_decode(curl_exec($ch), true);
        curl_close($ch);

        $r = [];
        switch(strtolower($this->build)){
            case "stable":
            default:
                $r["version"] = $i["version_string"];
                $r["downloadURL"] = "http://forums.pocketmine.net/plugins/essentialspe.886/download?version=" . $i["current_version_id"];
                break;
            case "beta":
                foreach($i as $release){
                    if($release["prerelease"] === true){
                        $i = $release;
                        break;
                    }
                }
                $r["version"] = substr($i["name"], 13);
                $r["downloadURL"] = $i["assets"][0]["browser_download_url"];
                break;
            /*case "development":
                // IDK How to do this :(
                break;*/
        }
        $this->setResult($r);
    }

    public function onCompletion(Server $server){
        /** @var Loader $esspe */
        $esspe = $server->getPluginManager()->getPlugin("EssentialsPE");
        $esspe->getServer()->getLogger()->debug(TextFormat::YELLOW . "Running EssentialsPE's UpdateFetchTask");
        if($esspe->getDescription()->getVersion() < ($v = $this->getResult()["version"])){
            $continue = true;
            $message = TextFormat::AQUA . "[EssentialsPE]" . TextFormat::GREEN . " A new " . TextFormat::YELLOW . $this->build . TextFormat::GREEN . " version of EssentialsPE found! Version: " . TextFormat::YELLOW . $v . TextFormat::GREEN . ($this->install !== true ? "" : ", " . TextFormat::LIGHT_PURPLE . "Installing...");
        }else{
            $continue = false;
            $message = TextFormat::AQUA . "[EssentialsPE]" . TextFormat::YELLOW . " No new version found, you're using the latest version of EssentialsPE";
        }
        $esspe->broadcastUpdateAvailability($message);
        if($continue && $this->install){
            $this->install($server);
            $esspe->broadcastUpdateAvailability($server->getLogger()->info(TextFormat::AQUA . "[EssentialsPE]" . TextFormat::YELLOW . " Successfully updated to version " . TextFormat::GREEN . $v . TextFormat::YELLOW . ". To start using the new features, please fully restart your server."));
        }
        $esspe->scheduleUpdaterTask();
    }

    public function install(Server $server){
        $url = $this->getResult()["downloadURL"];
        if(file_exists($server->getPluginPath() . "EssentialsPE.phar")){
            unlink($server->getPluginPath() . "EssentialsPE.phar");
        }
        $file = fopen($server->getPluginPath() . "EssentialsPE.phar", 'w+');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_FILE, $file);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        curl_close($ch);
        fclose($file);
    }
}