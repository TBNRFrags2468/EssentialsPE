<?php
namespace EssentialsPE\Tasks;

use EssentialsPE\Loader;
use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Utils;

class GeoLocation extends AsyncTask{
    /** @var Player|Player[] */
    private $player;
    /** @var array */
    private $ip;

    /**
     * @param Player|Player[] $player
     */
    public function __construct($player){
        if(!is_array($player)){
            $player = [$player];
        }
        foreach($player as $p){
            $spl = spl_object_hash($p);
            $this->player[$spl] = $p;
            $this->ip[$spl] = $p->getAddress();
        }
    }

    public function onRun(){
        $list = [];
        foreach($this->ip as $spl => $ip){
            $data = Utils::getURL("http://ip-api.com/json/" . $this->ip);
            $data = json_decode($data, true);
            $list[$spl] = $data["country"];
        }
        $this->setResult($list);
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server){
        /** @var Loader $api */
        $api = $server->getPluginManager()->getPlugin("EssentialsPE");
        foreach($this->getResult() as $spl => $ip){
            $api->updateGeoLocation($this->player[$spl], $ip);
        }
    }
}