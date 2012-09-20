<?php

class actions_Rootcms extends BaseActions
{
	protected $layoutName = 'rootcms';
	
	public function before($method, $args)
	{
		parent::before($method, $args);
		if ($method != 'login' && empty($_SESSION['rootcms']))
		{
			$this->redirectTo($this, 'login');
		}
		$this->layout->logged = !empty($_SESSION['rootcms']);
	}
	
	public function actionLogin()
	{
		$form = new Form();
		$form->title = 'Login to rootcms';
		$form->fields['login'] = new field_Id();
		$form->description = 'Remember, with great power. comes great responsibility.';
		$form->submitText = 'Login';
		$form->handler = function ($post) {
				if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1')
				{
					$_SESSION['rootcms'] = true;
					$this->redirectTo($this::index());
				}
			};
		return $form;
	}
	
	public function actionLogout()
	{
		unset($_SESSION['rootcms']);
		$this->redirectTo($this, 'login');
	}
	
	public function actionIndex()
	{
		return new Widget('rootcms/index');
	}
	
	public function actionModeler($model)
	{
		$this->widget = new Widget('rootcms/collectionList');
		$this->widget->model = $model;
		return $this->widget;
	}
	
	public function actionDbAdminer()
	{
		return 'TODO';
	}
	
	public function actionDbCheck($run = null)
	{
		$model = Cado::getDescendants('Model');
		$tables = array();
		foreach ($model as $className)
		{
			$tables = array_merge($tables, $className::getStructure());
		}
		$current = $this->db->getStructure();
				
		$this->widget->sqls = array();
		foreach ($tables as $name => $fields)
		{
			if (array_key_exists($name, $current))
			{
				$changes = array();
				foreach ($fields as $key => $field)
				{
					if (array_key_exists($key, $current[$name]))
					{
						if ($current[$name][$key] != $field)
						{
							if (strpos($key, 'index_') === 0)
							{
								$changes[] = 'DROP KEY `' . $key . '`';
								$changes[] = 'ADD ' .  $field . ' /*' . $current[$name][$key] . '*/';
							}
							else
							{
								$changes[] = 'MODIFY COLUMN `' . $key . '` ' .  $field . ' /*' . $current[$name][$key] . '*/';
							}
						}
						unset($current[$name][$key]);
					}
					else
					{
						$changes[] = 'ADD ' . (strpos($key, 'index_') === 0 ? '' : 'COLUMN `' . $key . '` ') .  $field;
					}
				}
				foreach ($current[$name] as $key => $field)
				{
					$changes[] = 'DROP ' . (strpos($key, 'index_') === 0 ? 'KEY' : 'COLUMN') . ' `' . $key . '`';
				}
				if (!empty($changes))
				{
					$this->widget->sqls[$name] = 'ALTER TABLE ' . $name . ' ' . PHP_EOL . implode(',' . PHP_EOL, $changes);
				}
				else
				{
					$this->widget->sqls[$name] = '-- OK';
				}
				unset($current[$name]);
			}
			else
			{
				$rows = array();
				foreach ($fields as $key => $field)
				{
					if (strpos($key, 'index_') === 0)
					{
						$rows[] = $field;
					}
					else
					{
						$rows[] = '`' . $key . '` ' .  $field;
					}
				}
				$this->widget->sqls[$name] = 'CREATE TABLE ' . $name . ' (' . PHP_EOL . implode(',' . PHP_EOL, $rows) . PHP_EOL . ') ENGINE=InnoDB DEFAULT CHARSET=utf8';
			}
		}
		foreach ($current as $name => $fields)
		{
			$this->widget->sqls[$name] = 'DROP TABLE ' . $name;
		}
		
		if (!empty($run) && !empty($this->widget->sqls[$run]))
		{
			$this->db->query($this->widget->sqls[$run]);
			$this->widget->sqls[$run] = '-- DONE';
		}
		return $this->widget;
	}
}
