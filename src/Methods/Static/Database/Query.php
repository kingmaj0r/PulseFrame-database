<?php

namespace PulseFrame\Methods\Static\Database;

use PulseFrame\Facades\Database;
use PDO;

class Query
{
  public static function handle($model, $sql, $params = [], $fetch_all = false)
  {
    switch($sql) {
      case 'BEGIN':
      case 'COMMIT':
        $model = 'default';
    }
  
    $db = Database::getConnection($model, false);
  
    switch($sql) {
      case 'BEGIN':
        return $db->beginTransaction();
        break;

      case 'COMMIT':
        return $db->commit();
        break;
    }
  
    $statement = $db->prepare($sql);
  
    foreach ($params as $key => $value) {
      $statement->bindValue($key, $value);
    }
  
    $statement->execute();
  
    if ($fetch_all) {
      return $statement->fetchAll(PDO::FETCH_ASSOC);
    } else {
      return $statement->fetch(PDO::FETCH_ASSOC);
    }
  }
}