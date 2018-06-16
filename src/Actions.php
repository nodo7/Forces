<?php

namespace CST;


class Actions {

  private $db;

  public function __construct() {
    $this->db = new Db('localhost', 'comos', 'root');
  }


  public function getAll($name) {
    $res = $this->db->select($name);

    return $res;
  }

  public function getOne($name, $id) {
    $res = $this->db->select($name, ['id' => $id]);

    $res = reset($res);

    return $res;
  }

  public function create($name, $data) {
    $this->db->insert($name, $data);

    return $this->db->select($name);
  }

}