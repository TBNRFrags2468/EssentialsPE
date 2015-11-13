<?php
namespace EssentialsPE\Tasks\Updater;

use EssentialsPE\Loader;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Utils;

class UpdateFetchTask extends AsyncTask{
    /** @var string */
    private $build;
    /** @var bool */
    private $install;

    /**
     * @param $build
     * @param $install
     */
    public function __construct($build, $install){
        $this->build = $build;
        $this->install = $install;
    }

    public function onRun(){
        switch($this->build){
            case "stable":
            default:
                $url = "http://forums.pocketmine.net/api.php?action=getResource&value=886"; // PocketMine repository for 'Stable' releases
                $this->build = "stable"; // Override property in case of an 'unknown' source
                break;
            case "beta":
                $url = "https://api.github.com/repos/LegendOfMCPE/EssentialsPE/releases"; // Github repository for 'Beta' releases
                break;
            /*case "development": TODO: Release 'dev' builds
                $url = "https://api.github.com/repos/LegendOfMCPE/EssentialsPE/contents/plugin.yml";
                break;*/
        }
        $i = json_decode(Utils::getURL($url), true);

        $r = [];
        switch(strtolower($this->build)){
            case "stable":
                $r["version"] = $i["version_string"];
                $r["downloadURL"] = "http://forums.pocketmine.net/plugins/essentialspe.886/download?version=" . $i["current_version_id"];
                break;
            case "beta":
                $i = $i[0]; // Grab the latest version from Github releases... Doesn't matter if it's Beta or Stable :3
                $r["version"] = substr($i["name"], 13);
                $r["downloadURL"] = $i["assets"][0]["browser_download_url"];
                break;
            case "development":
                $content = yaml_parse(base64_decode($i["content"]));
                $r["version"] = $content["version"];
                $r["downloadURL"] = "https://raw.githubusercontent.com/LegendOfMCPE/EssentialsPE/master/build/EssentialsPE.phar"; // TODO: Update when releasing 'dev' builds, this is written just for reference now...
                break;
        }
        $this->setResult($r);
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server){
        /** @var Loader $esspe */
        $esspe = $server->getPluginManager()->getPlugin("EssentialsPE");

        // Tricky move for better "version" comparison...
        $currentVersion = $this->correctVersion($esspe->getDescription()->getVersion());
        $v = $this->getResult()["version"];

        if($currentVersion < $v){
            $continue = true;
            $message = TextFormat::AQUA . "[EssentialsPE]" . TextFormat::GREEN . " A new " . TextFormat::YELLOW . $this->build . TextFormat::GREEN . " version of EssentialsPE found! Version: " . TextFormat::YELLOW . $v . TextFormat::GREEN . ($this->install !== true ? "" : ", " . TextFormat::LIGHT_PURPLE . "Installing...");
        }else{
            $continue = false;
            $message = TextFormat::AQUA . "[EssentialsPE]" . TextFormat::YELLOW . " No new version found, you're using the latest version of EssentialsPE";
        }
        $esspe->broadcastUpdateAvailability($message);
        if($continue && $this->install){
            $server->getScheduler()->scheduleAsyncTask($task = new UpdateInstallTask($esspe, $this->getResult()["downloadURL"], $server->getPluginPath(), $v));
            $esspe->updaterDownloadTask = $task;
        }
    }

    /**
     * @param string $version
     * @return string
     */
    protected function correctVersion($version){
        if(($beta = stripos($version, "Beta")) !== false){
            str_replace("Beta", ".", $version);
        }
        $version = explode(".", preg_replace("/[^0-9\.]+/", "", $version));
        $beta = 0;
        if(count($version) > 3){
            $beta = array_pop($version);
            $beta = (count($beta) < 2 ? 0 : "") . $beta;
        }
        return implode("", $version) . "." . $beta;
    }
}