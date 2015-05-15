<?php
namespace EssentialsPE\BaseFiles;

use pocketmine\item\Item;

class BaseKit{
    /** @var string */
    protected $name;
    /** @var Item[] */
    protected $items;

    /**
     * @param string $name
     * @param array $items
     */
    public function __construct($name, array $items){
        $this->name = $name;
        foreach($items as $i){
            if(!$i instanceof Item){
                $i = explode(" ", $i);
                if(count($i) > 0){
                    $amount = $i[1];
                    unset($i[1]);
                }else{
                    $amount = 1;
                }
                $i = explode(":", $i);
                if(count($i) > 0){
                    $id = $i[0];
                    $meta = $i[1];
                }else{
                    $id = $i;
                    $meta = 0;
                }
                $i = new Item($id, $meta, $amount);
            }
            $this->items[$i->getId()] = $i;
        }
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @return Item[]
     */
    public function getItems(){
        return $this->items;
    }

    /**
     * @param int $id
     * @param int|null $meta
     * @return bool|Item
     */
    public function hasItem($id, $meta = null){
        if(!isset($this->items[$id]) || ($meta !== null && $this->items[$id]->getDamage() !== $meta)){
            return false;
        }
        return $this->items[$id];
    }
}