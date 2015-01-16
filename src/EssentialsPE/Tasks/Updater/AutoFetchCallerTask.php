<?php
namespace EssentialsPE\Tasks\Updater;

use EssentialsPE\BaseTask;
use EssentialsPE\Loader;

class AutoFetchCallerTask extends BaseTask{

    public function __construct(Loader $plugin){
        parent::__construct($plugin);
    }

    public function onRun($currentTick){
        $this->getPlugin()->fetchEssentialsPEUpdate(false);
    }
}