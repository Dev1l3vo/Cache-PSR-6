<?php

namespace CacheLibrary\Storage\Strategy;

use CacheLibrary\Storage\Strategy\FileManager;
use CacheLibrary\Storage\StorageStrategyInterface;

class FileStorage implements StorageStrategyInterface{
    
    private $path; 

    public function __construct($path='cache'){
        if( !file_exists(FileManager::rootDirectory() . "/{$path}" ) ){
            mkdir(FileManager::rootDirectory() ."/{$path}", 0777, true);    
        }    
        $this->path = FileManager::rootDirectory() ."/{$path}";
    }

    private function getEncodeKey($key){
        return base64_encode($key);
    }
    
    
    public function get($key){
        $new_key = $this->getEncodeKey($key);
        
        if(!file_exists($this->path . "/{$new_key}")){
            return '';
        }
        $filesize = filesize( $this->path . "/{$new_key}" );
        $myfile = fopen($this->path . "/{$new_key}", "r");
        if(!$myfile){
            throw new \Exception("Occured error with opening file");
        }
        
        $value = unserialize(fread($myfile,$filesize));
        
        return $value;
    }
   
   
   
    public function set($key,$value){
        $new_key = $this->getEncodeKey($key);
        $myfile = fopen($this->path . "/{$new_key}", "w");
        if(!$myfile){
            fclose($myfile);
            throw new \Exception("Occured error with opening file");
        }

        
        if(fwrite($myfile, $value) == strlen($value) ){
            fclose($myfile);
            return true;
        }
        fclose($myfile);
        return false;
    }

    public function delete($key){
        $new_key = $this->getEncodeKey($key);
        
        if(!file_exists($this->path . "/{$new_key}")){
            return false;
        }

        return unlink($this->path . "/{$new_key}");
    }
}