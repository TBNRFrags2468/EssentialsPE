<?php
namespace EssentialsPE\Commands\Override;

use EssentialsPE\BaseFiles\BaseAPI;
use EssentialsPE\BaseFiles\BaseCommand;

abstract class BaseOverrideCommand extends BaseCommand{
    /**
     * @param BaseAPI $api
     * @param string $name
     * @param string $description
     * @param null|string $usageMessage
     * @param bool|null|string $consoleUsageMessage
     * @param array $aliases
     */
    public function __construct(BaseAPI $api, $name, $description = "", $usageMessage = null, $consoleUsageMessage = true, array $aliases = []){
        parent::__construct($api, $name, $description, $usageMessage, $consoleUsageMessage, $aliases);
        // Special part :D
        $commandMap = $api->getServer()->getCommandMap();
        $aliases[] = $name;
        foreach($aliases as $label){
            $command = $commandMap->getCommand($label);
            if($command !== null){
                $command->setLabel($label . "_disabled");
                $command->unregister($commandMap);
            }
        }
    }
}