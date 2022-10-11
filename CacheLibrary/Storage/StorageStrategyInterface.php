<?php 

namespace CacheLibrary\Storage;

interface StorageStrategyInterface{
    public function get($key);
    public function set($key,$value);
    public function delete($key);
}
 