<?php

class actions_Rootcms extends actions_Layout
{
	protected $layoutName = 'rootcms';
	
	public function before($method, $args)
	{
		parent::before($method, $args);
		if ($method != 'actionLogin' && empty($_SESSION['rootcms']))
		{
			$this->redirectTo($this::hrefLogin());
		}
		$this->layout->logged = false;
		if(!empty($_SESSION['rootcms']))
		{
			$this->layout->logged = true;
			$mainMenu = array(
				array( 'title' => 'Tools',
						'href' => '#',
						'children' => array(
								array('title' => 'checkDb', 'href' => actions_Rootcms::hrefDbCheck()),
								),
						)
			);
			$modelerMenu = array();
			foreach ($this->site->getModels() as $model)
			{
				$modelerMenu[] = array('title' => $model, 'href' => actions_Rootcms::hrefModeler($model));
			}
			$mainMenu[] = array('title' => 'Modeler', 'href' => '#', 'children' => $modelerMenu);
			$mainMenu[] = array('title' => '', 'href' => actions_Rootcms::hrefLogout(), 'class' => 'logout');
			$this->layout->mainMenu = $mainMenu;
		}
	}
	
	public function actionLogin()
	{
		$form = new Form();
		$form->title = 'Login to rootcms';
		$form->description = 'Remember, with great power. comes great responsibility.';
		$form->submitText = 'Login';
		if ($form->validate())
		{
			if (CADO_DEV || ($_SERVER['REMOTE_ADDR'] === '127.0.0.1'))
			{
				$_SESSION['rootcms'] = true;
				$this->redirectTo($this::hrefIndex());
			}
		}
		return $form;
	}
	
	public function actionLogout()
	{
		unset($_SESSION['rootcms']);
		$this->redirectTo($this::hrefLogin());
	}
	
	public function actionIndex()
	{
	}
	
	public function actionConfig()
	{
	
	}
	
	public function actionModules()
	{
		
	}
	
	public function actionModule($module)
	{
	
	}
	
	public function actionModeler($model, $parent = 0, $page = 1, $orderBy = null)
	{
		$limit = 30; 
		$this->widget = new Widget('widgets/rootcmsCollectionList');
		$this->widget->model = $model;
		$this->widget->columns = array_keys($this->site->model($model)->fields());
		
		if ($this->site->model($model) instanceof model_TreeCollection)
		{
			$this->widget->data = $this->site->model($model)->getChildren($parent, $limit, $page, $foundRows);
		}
		else
		{
			$this->widget->data = $this->site->model($model)->getAll($limit, $page, $foundRows);
		}
		
		$this->widget->pager = new Pager($limit, $page, $foundRows, $this::hrefModeler($model, $parent, '%PAGE%'));
		return $this->widget;
	}
	
	public function actionModelerSave($model, $id = 0)
	{
		$this->modelName = $model;
		$form = new Form();
		$form->title = 'Edit ' . $model . ' ' . $id;
		$form->description = 'Remember, with great power. comes great responsibility.';
		$form->fields = $this->site->model($model)->fields();
		$form->data = $id ? $this->site->model($model)->getById($id) : $this->site->model($model)->getDefaults();
		$form->submitText = $id ? 'Save' : 'Add';
		
		if ($form->validate())
		{
			try
			{
				$this->site->model($model)->save($form->getData());
				$this->redirectTo($this::hrefModeler($this->modelName));
			}
			catch(model_Exception $e)
			{
				$form->setErrors($e->getErrors());
			}
		}
		return $form;
	}
	
	public function actionDbCheck($run = null)
	{
		$this->widget = new Widget('widgets/rootcmsDbCheck');
		$models = $this->site->getModels();
		$tables = array();
		foreach ($models as $model)
		{
			$tables = array_merge($tables, $this->site->model($model)->getStructure());
		}
		$current = $this->site->readDbStructure();
		//var_dump($current);
		//die();
		$sqls = array();
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
					$sqls[$name] = 'ALTER TABLE ' . $name . ' ' . PHP_EOL . implode(',' . PHP_EOL, $changes);
				}
				else
				{
					$sqls[$name] = '-- OK';
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
				$sqls[$name] = 'CREATE TABLE ' . $name . ' (' . PHP_EOL . implode(',' . PHP_EOL, $rows) . PHP_EOL . ') ENGINE=InnoDB DEFAULT CHARSET=utf8';
			}
		}
		foreach ($current as $name => $fields)
		{
			$sqls[$name] = 'DROP TABLE ' . $name;
		}
		
		if (!empty($run) && !empty($sqls[$run]))
		{
			$this->site->getDb()->query($sqls[$run]);
			$sqls[$run] = '-- DONE';
		}
		$this->widget->sqls = $sqls;
		return $this->widget;
	}
}
