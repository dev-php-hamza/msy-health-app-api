<?php

namespace App\Traits;
use Excel;
use Exception;

trait Import
{
  public function getCsvData($fileName , $modelName)
  {
    $arr = array();
    $path = public_path().'/assets/import_files/'.$fileName;
    try {
      $csvData = array_map('str_getcsv', file($path));
      $arr['status'] = true;
      $arr['header'] = $csvData[0];
      $arr[$modelName] = array_splice($csvData, 1);
      return $arr;
    } catch (Exception $e) {
      $arr['status']  = false;
      $arr['message'] = $e->getMessage();
      return $arr;
    }
  }
}
