<?php
class Todo extends model {

	private $token;
	private $ok;

	public function __construct($t=0) {
		parent::__construct();
		$this->token = $t;
		$this->ok = false;

		$sql = $this->db->prepare("SELECT * FROM todo WHERE token = :token");
		$sql->bindValue(":token", $t);
		$sql->execute();

		if($sql->rowCount() > 0) {
			$this->ok = true;
		}
	}

	public function createToken() {
		while($token = rand(11111, 99999)) {
			$sql = $this->db->prepare("SELECT * FROM todo WHERE token = :token");
			$sql->bindValue(":token", $token);
			$sql->execute();

			if($sql->rowCount() == 0) {
				break;
			}
		}
		$this->token = $token;
		$this->add('Item 1');
		$id = $this->add('Item 2');
		$this->add('Item 3');

		$this->update(array('done'=>'1', 'id'=>$id));

		return $token;
	}

	public function isOk() {
		return $this->ok;
	}

	public function get($id = 0) {
		if($id != 0) {
			$sql = "SELECT id, item, done FROM todo WHERE token = :token AND id = :id";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(":token", $this->token);
			$sql->bindValue(":id", $id);
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		} else {
			$sql = "SELECT id, item, done FROM todo WHERE token = :token";
			$sql = $this->db->prepare($sql);
			$sql->bindValue(":token", $this->token);
			$sql->execute();
			return $sql->fetchAll(PDO::FETCH_ASSOC);
		}
	}

	public function add($item) {
		$sql = "INSERT INTO todo (token, item) VALUES (:token, :item)";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(":token", $this->token);
		$sql->bindValue(":item", $item);
		$sql->execute();
		return $this->db->lastInsertId();
	}

	public function update($arg) {
		$set = array();
		if(!empty($arg['item'])) {
			$set[] = 'item = :item';
		}
		if(!empty($arg['done'])) {
			$set[] = 'done = :done';
		}
		if(count($set) > 0) {
			$sql = "UPDATE todo SET ".implode(', ', $set)." WHERE token = :token AND id = :id";
			$sql = $this->db->prepare($sql);
			if(!empty($arg['item'])) {
				$sql->bindValue(":item", $arg['item']);
			}
			if(!empty($arg['done'])) {
				$done = ($arg['done']=='sim')?'1':'0';
				$sql->bindValue(":done", $done);
			}
			$sql->bindValue(":token", $this->token);
			$sql->bindValue(":id", $arg['id']);
			$sql->execute();
		}
	}

	public function delete($id) {
		$sql = "DELETE FROM todo WHERE token = :token AND id = :id";
		$sql = $this->db->prepare($sql);
		$sql->bindValue(":token", $this->token);
		$sql->bindValue(":id", $id);
		$sql->execute();
	}


}