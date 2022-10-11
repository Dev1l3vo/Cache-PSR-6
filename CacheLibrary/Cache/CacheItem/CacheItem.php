<?php

namespace CacheLibrary\Cache\CacheItem;
use CacheLibrary\Cache\CacheItemInterface;
use DateTime;

class CacheItem extends AbstractCacheItem implements CacheItemInterface
{

    public function __construct($key='',$value='')
    {
        if($key && $value){
            parent::__construct($key,$value);
        }
    }

    public function getKey()
    {
        return $this->key;
    }

    public function get()
    {
        if(!$this->isHit())
        {
            return false;
        }
        return $this->value;
    }

    public function isHit()
    {
        $now = new DateTime();
        
        if($now > $this->expires)
        {
            return false;
        }

        return $this->isHitted;

    }

    public function set($value)
    {
        $this->value = $value;
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function expiresAt($expiration)
    {
        if(!$expiration)
        {
            $this->defaultExpires();
            return $this;
        }
        $this->expires = $expiration;
        return $this;
    }


    public function expiresAfter($time)
    {

        //$time is not datetime callculate datetime s
        if(!$time)
        {
            $this->defaultExpires();
            return $this;
        }else if ($time instanceof \DateInterval)
        {
            $date = new \DateTime();
            $date->add($time);
            $this->expires = $date;
            return $this;
        }

        $this->expires = new \DateTime('+'.$time.' seconds');
        return $this;

    }

    public function setHit($isHitted)
    {
        $this->isHitted = $isHitted;
    }

    public function getHit()
    {
        return $this->isHitted;
    }

}