<?php

namespace CacheLibrary\Storage\Strategy;

class FileManager
{
  public static function rootDirectory()
  {
    // Change the second parameter to suit your needs
    return dirname(__FILE__, 4);
  }
}