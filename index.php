<?php
require 'vendor/autoload.php';
use CacheLibrary\Storage\Strategy\FileStorage;
use CacheLibrary\Cache\CachePool\CachePool;
use CacheLibrary\Cache\CacheItem\CacheItem;

$fileStr = new FileStorage();
$pool = new CachePool($fileStr);
$item = new CacheItem('vova','vova the best');
$item2 = new CacheItem('dead inside','hate this life');

$interval = new DateInterval('P32D');
$item->expiresAfter($interval);

$pool->saveDeferred($item);
$pool->saveDeferred($item2);

var_dump($item->isHit());
var_dump($pool->hasItem('vova'));

$pool->commit();
$pool->clear();

var_dump($item2->isHit());
var_dump($pool->hasItem('dead inside'));