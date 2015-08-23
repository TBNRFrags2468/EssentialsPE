<?php
namespace EssentialsPE\Tasks;


use pocketmine\command\ConsoleCommandSender;
use pocketmine\scheduler\PluginTask;

class TravisKillTask extends PluginTask{
    /**
     * Actions to execute when run
     *
     * @param $currentTick
     *
     * @return void
     */
    public function onRun($currentTick){
        $this->getOwner()->getServer()->dispatchCommand(new ConsoleCommandSender(), "stop");
    }

}