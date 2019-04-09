<?php
class homeController extends controller {

	public function index($token='', $id=0) {
		if(empty($token)) {
			exit;
		}

		$todo = new Todo($token);

		if($todo->isOk() == false) {
			exit;
		}

		$m = $_SERVER['REQUEST_METHOD'];
		$array = array();

		if($id == 0)
		{
			if($m == 'GET') {
				// Pegar dados da tabela
				$array = array('todo'=> $todo->get());
			}
			elseif($m == 'POST') {
				// Inserir dados na tabela
				$data = json_decode(file_get_contents('php://input'));
				if(is_null($data)) {
					$data = $_POST;
					$data = json_decode(json_encode($data), FALSE);
				}
				if(!empty($data->item)) {
					$item = $data->item;
					$id = $todo->add($item);
					$array = array('todo'=> array('last_id' => $id));
				}
			}
		}
		else
		{
			if($m == 'GET') {
				// Pegar um dado específico
				$array = $todo->get($id);
			}
			elseif($m == 'PUT') {
				// Atualizar um dado específico
				$data = json_decode(file_get_contents('php://input'));
				$arg = array(
					'item' => (isset($data->item))? $data->item:'',
					'done' => (isset($data->done))? $data->done:'',
					'id' => $id
				);
				$todo->update($arg);
			}
			elseif($m == 'DELETE') {
				// Deletar um dado específico
				$todo->delete($id);
			}
		}

		header("Content-Type: application/json");
		echo json_encode($array);
	}

	public function create() {
		$todo = new Todo();
		$token = $todo->createToken();

		header("Location: ".BASE_URL.$token."/info");
		exit;
	}

	public function info($token) {
		$url = BASE_URL.$token;

		$this->loadView('create', array('url'=>$url));
	}

	public function notfound() {}

}