<?php
namespace EssentialsPE\BaseFiles;

use EssentialsPE\Loader;

class BaseAPI{
    /** @var Loader */
    private $ess;

    /**
     * @param Loader $ess
     */
    public function __construct(Loader $ess){
        $this->ess = $ess;
    }

    /**
     * @return Loader
     */
    public final function getEssentialsPEPlugin(){
        return $this->ess;
    }

    /**
     * @return \pocketmine\Server
     */
    public function getServer(){
        return $this->getEssentialsPEPlugin()->getServer();
    }

    /*
     *  .----------------.  .----------------.  .----------------.
     * | .--------------. || .--------------. || .--------------. |
     * | |      __      | || |   ______     | || |     _____    | |
     * | |     /  \     | || |  |_   __ \   | || |    |_   _|   | |
     * | |    / /\ \    | || |    | |__) |  | || |      | |     | |
     * | |   / ____ \   | || |    |  ___/   | || |      | |     | |
     * | | _/ /    \ \_ | || |   _| |_      | || |     _| |_    | |
     * | ||____|  |____|| || |  |_____|     | || |    |_____|   | |
     * | |              | || |              | || |              | |
     * | '--------------' || '--------------' || '--------------' |
     *  '----------------'  '----------------'  '----------------'
     *
     */

    // TODO
}