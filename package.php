<?php
define("DIRECTORY", getenv("WORKSPACE") ? getenv("WORKSPACE") : getcwd());

class PluginDescription{
    private $name;
    private $main;
    private $api;
    private $depend = array();
    private $softDepend = array();
    private $loadBefore = array();
    private $version;
    private $commands = array();
    private $description = null;
    private $authors = array();
    private $website = null;
    private $prefix = null;
    private $order = 1;
    /**
     * @param string $yamlString
     */
    public function __construct($yamlString){
        $this->loadMap(yaml_parse($yamlString)); //TODO compile a binary with YAML
    }
    /**
     * @param array $plugin
     *
     * @throws \Exception
     */
    private function loadMap(array $plugin){
        $this->name = preg_replace("[^A-Za-z0-9 _.-]", "", $plugin["name"]);
        if($this->name === ""){
            throw new \Exception("Invalid PluginDescription name");
        }
        $this->name = str_replace(" ", "_", $this->name);
        $this->version = $plugin["version"];
        $this->main = $plugin["main"];
        $this->api = !is_array($plugin["api"]) ? array($plugin["api"]) : $plugin["api"];
        if(stripos($this->main, "pocketmine\\") === 0){
            trigger_error("Invalid PluginDescription main, cannot start within the PocketMine namespace", E_USER_ERROR);
            return;
        }
        if(isset($plugin["commands"]) and is_array($plugin["commands"])){
            $this->commands = $plugin["commands"];
        }
        if(isset($plugin["depend"])){
            $this->depend = (array) $plugin["depend"];
        }
        if(isset($plugin["softdepend"])){
            $this->softDepend = (array) $plugin["softdepend"];
        }
        if(isset($plugin["loadbefore"])){
            $this->loadBefore = (array) $plugin["loadbefore"];
        }
        if(isset($plugin["website"])){
            $this->website = $plugin["website"];
        }
        if(isset($plugin["description"])){
            $this->description = $plugin["description"];
        }
        if(isset($plugin["prefix"])){
            $this->prefix = $plugin["prefix"];
        }
        if(isset($plugin["load"])){
            $order = strtoupper($plugin["load"]);
            if($order == "STARTUP") $this->order = 0;
            else $this->order = 1;
        }
        $this->authors = array();
        if(isset($plugin["author"])){
            $this->authors[] = $plugin["author"];
        }
        if(isset($plugin["authors"])){
            foreach($plugin["authors"] as $author){
                $this->authors[] = $author;
            }
        }
    }
    /**
     * @return string
     */
    public function getFullName(){
        return $this->name . " v" . $this->version;
    }
    /**
     * @return array
     */
    public function getCompatibleApis(){
        return $this->api;
    }
    /**
     * @return array
     */
    public function getAuthors(){
        return $this->authors;
    }
    /**
     * @return string
     */
    public function getPrefix(){
        return $this->prefix;
    }
    /**
     * @return array
     */
    public function getCommands(){
        return $this->commands;
    }
    /**
     * @return array
     */
    public function getDepend(){
        return $this->depend;
    }
    /**
     * @return string
     */
    public function getDescription(){
        return $this->description;
    }
    /**
     * @return array
     */
    public function getLoadBefore(){
        return $this->loadBefore;
    }
    /**
     * @return string
     */
    public function getMain(){
        return $this->main;
    }
    /**
     * @return string
     */
    public function getName(){
        return $this->name;
    }
    /**
     * @return int
     */
    public function getOrder(){
        return $this->order;
    }
    /**
     * @return array
     */
    public function getSoftDepend(){
        return $this->softDepend;
    }
    /**
     * @return string
     */
    public function getVersion(){
        return $this->version;
    }
    /**
     * @return string
     */
    public function getWebsite(){
        return $this->website;
    }
}
date_default_timezone_set("UTC");
print "Packaging plugin...\n";
$description = new PluginDescription(file_get_contents(DIRECTORY . "/plugin.yml"));
$pharPath = DIRECTORY . "/" . $description->getName() ."_v" . $description->getVersion() . ".phar";
$phar = new Phar($pharPath);
$phar->setMetadata(array(
    "name" => $description->getName(),
    "version" => $description->getVersion(),
    "main" => $description->getMain(),
    "api" => $description->getCompatibleApis(),
    "depend" => $description->getDepend(),
    "description" => $description->getDescription(),
    "authors" => $description->getAuthors(),
    "website" => $description->getWebsite(),
    "creationDate" => strtotime("now")
));
$phar->setStub('<?php echo "PocketMine-MP plugin ' . $description->getName() . ' v' . $description->getVersion() . '\n----------------\n";if(extension_loaded("phar")){$phar = new \Phar(__FILE__);foreach($phar->getMetadata() as $key => $value){echo ucfirst($key).": ".(is_array($value) ? implode(", ", $value):$value)."\n";}}__HALT_COMPILER();');
$phar->setSignatureAlgorithm(\Phar::SHA1);
$phar->startBuffering();
$filePath = DIRECTORY;
foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($filePath)) as $file) {
    $path = ltrim(str_replace(array("\\", $filePath), array("/", ""), $file), "/");
    if ($path{0} === "." || strpos($path, "/.") !== false || $path === $pharPath) {
        continue;
    }
    print "Adding $path\n";
    $phar->addFile($file, $path);
}
$phar->compressFiles(\Phar::GZ);
$phar->stopBuffering();
print "Plugin packaged.\n";
