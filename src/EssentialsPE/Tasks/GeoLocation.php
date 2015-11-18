<?php
namespace EssentialsPE\Tasks;

use EssentialsPE\Loader;
use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Utils;

class GeoLocation extends AsyncTask{
    /** @var Player */
    private $player;
    /** @var string */
    private $ip;

    /**
     * @param Player $player
     */
    public function __construct(Player $player){
        $this->player = $player;
        $this->ip = $player->getAddress();
    }

    public function onRun(){
        $data = Utils::getURL("http://ip-api.com/json/" . $this->ip);
        $data = json_decode($data, true);
        $this->setResult($data["country"]);
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server){
        /** @var Loader $api */
        $api = $server->getPluginManager()->getPlugin("EssentialsPE");
        $api->updateGeoLocation($this->player, $this->getResult());
    }
}