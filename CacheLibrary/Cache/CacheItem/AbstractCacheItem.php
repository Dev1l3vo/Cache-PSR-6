<?php 

namespace CacheLibrary\Cache\CacheItem;


abstract class AbstractCacheItem
{

    protected $key;
    protected $value;
    protected $expires = null;
    protected $isHitted = false;
    
    protected function validateKey($key){
        $pattern = preg_quote('(){}\@:/', '#');
        if(!preg_match("#[{$pattern}]#", $key)){
            return true;
        }
        return false;
    }

    public function __construct($key,$value){
        if(!$this->validateKey($key)){
            throw new \Exception("Invalid key for cacheItem");
        }
        $this->key = $key;
        $this->value = $value;
        $this->defaultExpires();
    }

    public function defaultExpires()
    {
        $this->expires = new \DateTime('+300 seconds');
    }


    public function __serialize(): array
    {
        return [
          'key' => $this->key,
          'value' => $this->value,
          'expires' => $this->expires,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->key = $data['key'];
        $this->value = $data['value'];
        $this->expires = $data['expires'];
    }
  
  

}