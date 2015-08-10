<?php
namespace EssentialsPE\BaseFiles;

use EssentialsPE\Loader;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class MessagesAPI{
    const VERSION = "2.0.0";

    /** @var Config */
    private $config;

    /** @var Config */
    private $original;

    /**
     * @param Loader $plugin
     * @param string $originalFile
     */
    public function __construct(Loader $plugin, $originalFile){
            $oF = fopen($originalFile, "rb");
            $originalInfo = fread($oF, filesize($originalFile));
            fclose($oF);

            $oFS = fopen($originalFileSave = $plugin->getDataFolder() . "MessagesOriginal.yml", "w+");
            fwrite($oFS, $originalInfo);
            fclose($oFS);
            $this->original = new Config($originalFileSave, Config::YAML);
            unlink($originalFileSave);

        $plugin->saveResource("Messages.yml");
        $this->config = new Config($file = $plugin->getDataFolder() . "Messages.yml", Config::YAML);
        if(!$this->config->exists("version") || $this->config->get("version") !== self::VERSION){
            $plugin->getLogger()->debug(TextFormat::RED . "An invalid language file was found, generating a new one...");
            unlink($file);
            $plugin->saveResource("Messages.yml", true);
            $this->config = new Config($file, Config::YAML);
        }
    }

    /**
     * @param $identifier
     * @return bool|string
     */
    public function getMessage($identifier){
        if(trim($identifier) === "" || (!$this->config->exists($identifier) && !$this->original->exists($identifier))){
            return false;
        }
        if(($c = $this->config->get($identifier)) === null){
            return $this->original->get($identifier);
        }
        return $c;
    }
}