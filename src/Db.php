<?php

namespace CST;


use PDO;

class Db {

  private $pdo;

  public function __construct($host, $name, $user, $password = '') {
    $this->pdo = new PDO('mysql:host=' . $host . ';' . 'dbname=' . $name . '',
      $user, $password);
  }

  public function select($table, $query = []) {

    $query_values = [];
    if (empty($query)) {
      $squ_query_final = 'SELECT * FROM ' . $table;
    }
    else {
      $quey_key = array_keys($query);
      $query_values = array_values($query);
      $sql_query_condition = '';
      for ($i = 0, $iMax = count($quey_key); $i < $iMax; $i++) {
        if (!$quey_key[$i + 1]) {
          $sql_query_condition .= $quey_key[$i] . '=?';
        }
        else {
          $sql_query_condition .= $quey_key[$i] . '=? and ';
        }
      }
      $squ_query_final = 'SELECT * FROM ' . $table . ' WHERE ' . $sql_query_condition;
    }

    $db_res = $this->execute($squ_query_final, $query_values);

    return $db_res->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insert($table, $data) {
    $question_mark = array_fill(0, count($data), "?");
    $keys = array_keys($data);
    $values = array_values($data);
    $query = 'insert into ' . $table . '(' . implode(',',
        $keys) . ') value (' . implode(',', $question_mark) . ')';

    $this->execute($query, $values);
  }


  public function update($table, $data, $conditions) {
    $data_key = array_keys($data);
    $data_values = array_values($data);
    $conditions_key = array_keys($conditions);
    $conditions_values = array_values($conditions);
    $sql_query_data = '';
    $sql_query_condition = '';
    for ($i = 0, $iMax = count($data_key); $i < $iMax; $i++) {
      if (!$data_key[$i + 1]) {
        $sql_query_data .= $data_key[$i] . '=?';
      }
      else {
        $sql_query_data .= $data_key[$i] . '=?, ';
      }
    }
    for ($i = 0, $iMax = count($conditions_key); $i < $iMax; $i++) {
      if (!$conditions_key[$i + 1]) {
        $sql_query_condition .= $conditions_key[$i] . "=?";
      }
      else {
        $sql_query_condition .= $conditions_key[$i] . "=? and ";
      }
    }
    $sql_query_final = 'UPDATE ' . $table . ' SET ' . $sql_query_data . ' WHERE ' . $sql_query_condition;
    $marged_array = array_merge($data_values, $conditions_values);

    $this->execute($sql_query_final, $marged_array);
  }


  public function delete($table, $conditions) {
    $conditions_key = array_keys($conditions);
    $conditions_value = array_values($conditions);
    $sql_query_condition = '';
    for ($i = 0, $iMax = count($conditions_key); $i < $iMax; $i++) {
      if (!$conditions_key[$i + 1]) {
        $sql_query_condition .= $conditions_key[$i] . '=?';
      }
      else {
        $sql_query_condition .= $conditions_key[$i] . '=? and ';
      }
    }
    $squ_query_final = 'DELETE FROM ' . $table . ' WHERE ' . $sql_query_condition;

    $this->execute($squ_query_final, $conditions_value);
  }

  private function execute($query, $conditions = []) {
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($conditions);


    return $stmt;
  }

}