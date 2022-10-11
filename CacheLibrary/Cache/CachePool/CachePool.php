<?php 

namespace CacheLibrary\Cache\CachePool;

use CacheLibrary\Cache\CacheItemPoolInterface;
use CacheLibrary\Cache\CacheItemInterface;
use CacheLibrary\Cache\CacheItem\CacheItem;
use CacheLibrary\Storage\Strategy\FileStorage;

class CachePool implements CacheItemPoolInterface
{

    //TODO add valdator for key and finish CacheItem and auto initialization of cachePool

    /**
    * @var FileStorage
    */
    private $storageStrategy;
    
    /**
    * @var CacheItemInterface[]
    */
    private $cachePool;
   
    /**
     * @var CacheItemInterface[]
     */
    private $saveQueue;

    /**
     * Array for saving config
     *
     * @var Array
     */
    private $config;

    public function __construct(FileStorage $storage)
    {
        if(!$storage){
            throw new \Exception("Storage must be initialized");
        }
        $this->storageStrategy = $storage;
        $this->cachePool = [];
        $this->saveQueue = [];
        //READ CONFIG

        //set all keys to cachePool
    }
      
    public function getItem($key)
    {
        if($this->hasItem($key)){
            return $this->cachePool[$key];
        }
        $cacheItem = new CacheItem();
        return $cacheItem;
    }

    public function getItems(array $keys = array())
    {
        $cacheItemList = array();
        foreach ($keys as $key){
            $cacheItem = $this->getItem($key);
            $cacheItemList[] = $cacheItem;
        }
        return $cacheItemList;
    }

    public function hasItem($key)
    {
        return isset($this->cachePool[$key]) && $this->cachePool[$key]->isHit();
    }

    public function clear()
    {
        foreach($this->cachePool as $cacheItem){
            $status = $this->deleteItem($cacheItem->getKey());
            if(!$status){
                return false;
            }
        }
        return true;
    }

    public function deleteItem($key)
    {
        if($this->hasItem($key))
        {
            ($this->cachePool[$key])->setHit(false);
            unset($this->cachePool[$key]);
        }
        return $this->storageStrategy->delete($key);
    }

    public function deleteItems(array $keys)
    {
        foreach ($keys as $key){
            $status = $this->deleteItem($key);
            if(!$status){
                return false;
            }
        }
        return true;
    }

    public function save(CacheItemInterface $item)
    {
        if($item->getKey() && !$this->hasItem($item->getKey()) ){
            $this->cachePool[$item->getKey()] = $item;
        }
        $item->setHit(true);
        return $this->storageStrategy->set($item->getKey(), serialize($item));
    }

    public function saveDeferred(CacheItemInterface $item)
    {
        $this->saveQueue[] = $item;
        $this->cachePool[$item->getKey()] = $item;
        $item->setHit(true);
    }

    public function commit()
    {
        foreach($this->saveQueue as $key => $item){
            $status = $this->storageStrategy->set($item->getKey(), serialize($item));
            if(!$status){
                return false;
            }
            unset($this->saveQueue[$key]);
        }
        return true;
    }
}