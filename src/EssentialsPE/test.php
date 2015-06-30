<?php
// Command Auto-Loader (Thanks @Falkirks)
$commands = [];
//$regex = new \RegexIterator(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($loader->getDataFolder() . "/src/" . __NAMESPACE__ . "/Commands/")), '/\w\.php$/i', \RecursiveRegexIterator::GET_MATCH);
$regex = new \RegexIterator(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(dirname(getcwd()) . "/Commands/")), '/\w\.php$/i', \RecursiveRegexIterator::GET_MATCH);
/*foreach($regex as $file){
    $class = substr($file, stripos($file, "/") + 1);
    //$class = str_replace("/", "\\", substr($file[0], strpos($file[0], __NAMESPACE__ . "/Commands/"), -4));
    $class = new $class($loader);
    if($class instanceof \EssentialsPE\BaseFiles\BaseCommand && !$class instanceof \EssentialsPE\BaseFiles\TestCommand){
        echo "Command found: " . $class->getName();
    }
}*/
var_dump($regex);