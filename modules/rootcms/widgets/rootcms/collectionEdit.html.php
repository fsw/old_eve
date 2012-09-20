<?
		$data = $id ? $model::getById($id) : array();
		$errors = array();
		if (!empty($_POST['data']))
		{
			$data = $_POST['data'];
			try
			{
				$model::save($data);
			}
			catch (ModelException $e)
			{
				$errors = $e->errors;
			}
			if (empty($errors))
			{
				echo 'OK';
			}
		}
		echo new Form('data', $model::fields(), $data, $errors);
		echo Routing::link(array('id' => null), 'Cancel');
		?>