<?php

class actions_Cms extends actions_Layout
{
	protected $layoutName = 'cms';
	
	public function before($method, $args)
	{
		parent::before($method, $args);
		if (empty($_SESSION['user']))
		{
			$this->redirectTo(
					actions_Users::hrefLogin(
						Site::unroute(get_class($this), 'action' . ucfirst($method), $args)
					)
			);
		}
		else
		{
			$this->layout->logged = true;
			$this->layout->modules = $this->site->getModules();
			$this->layout->addJs('/static/tinymce/jscripts/tiny_mce/tiny_mce.js');
			$this->cmsData = $this->site->getConfigField('cmsData');
			if (empty($this->cmsData))
			{
				$this->cmsData = array(
						'menus' => 'Menus',
						'files' => 'Files',
						'contents' => 'Contents'
				);
			}
			$this->layout->dataMenu = $this->cmsData;
			$this->layout->user = $_SESSION['user'];
		}
	}
	
	public function actionIndex()
	{
		return new Widget('widgets/cms/index');
	}
	
	
	public function actionSave($model, $id, array $data)
	{
		$form = new Form();
		$form->id = $model . 'Form';
		$form->title = 'save ' . $model;
		$fields = $this->site->model($model)->fields();
		foreach ($fields as $key => $field)
		{
			if ($field instanceof field_relation_One)
			{
				$values = $this->site->model($field->model)->getAll();
				$options = array(0 => 'none');
				foreach ($values as $val)
				{
					$options[$val['id']] = $this->site->model($field->model)->getAdminString($val);
				}
				$fields[$key] = new field_Enum($options);
			}
			if ($field instanceof field_relation_Many)
			{
				$values = $this->site->model($field->model)->getAll();
				foreach ($values as $val)
				{
					$options[$val['id']] = $this->site->model($field->model)->getAdminString($val);
				}
				$fields[$key] = new field_Enum($options, true);
			}
				
		}
		$form->fields = $fields; 
		if (!empty($id))
		{
			$current = $this->site->model($model)->getById($id);
			//var_dump($current);
			$form->setData($current);
		}
		elseif (!empty($data))
		{
			$form->setData($data);
		}
		
		if (!$form->submitted())
		{
			$_SESSION['saveReferer'] = $this->request->getReferer();
		}
		if ($form->validate())
		{
			$ret = $this->site->model($model)->save($form->getData());
			if ($ret == true)
			{
				$this->redirectTo($_SESSION['saveReferer']);
			}
			else
			{
				$form->setErrors($ret);
			}
		}
		return $form;
	}
	
	public function actionList($model, Array $search)
	{
		$widget = new Widget('widgets/cms/list');
		$actions = array('Add' => self::hrefSave($model, 0));
		$rowActions = array(
				'Edit' => self::hrefSave($model, '_ID_'),
				'Trash' => self::hrefSave($model, 0)
		);
		
		$modelClass = $this->site->model($model);
		$where = '1';
		$bind = array();
		
		$widget->model = $model;
		$widget->search = $search;
		
		if ($modelClass instanceof model_TreeCollection)
		{
			$widget->tree = true;
			
			$parent = empty($search['parent']) ? 0 : $search['parent'];
			
			$widget->path = $modelClass->getPath($parent);
			
			$rowActions['Children'] = self::hrefList($model, array('parent' => '_ID_'));
			$actions['Add'] = self::hrefSave($model, 0, array('parent' => $parent));
			
			$where .= ' AND `parent`=?';
			$bind[] = $parent;
		}
		
		$widget->rowActions = $rowActions;
		$widget->actions = $actions; 
		
		switch($model)
		{
			case 'menus':
				$columns = array('id', 'title', 'slug');
				break;
			case 'users':
				$columns = array('id', 'name', 'email');
				break;
			case 'contents':
				$columns = array('id', 'title', 'slug');
				break;
			case 'files':
				$columns = array('id', 'name', 'file');
				break;
			case 'groups':
				$columns = array('id', 'name', 'description', 'privilages');
				break;
			case 'privilages':
				$columns = array('id', 'code', 'description');
				break;
			default:
				$columns = 	$modelClass->getAdminCols();
		}
		$widget->columns = $columns;
		
		if (!empty($search['order']))
		{
			$direction = 'ASC';
			$order = $search['order'];
			if (strpos($order, '-') === 0)
			{
				$direction = 'DESC';
				$order = substr($order, 1);
			}
			if (in_array($order, $columns))
			{
				$where .= ' ORDER BY `' . $order . '` ' . $direction;
			}
		}
		$perPage = 30;
		$page = empty($search['page']) ? 1 : $search['page'];
		
		$widget->rows = $modelClass->search($where, $bind , $perPage, $page, $foundRows);
		
		$search['page'] = '_PAGE_';
		$widget->pager = new Pager($perPage, $page, $foundRows, $this::hrefList($model, $search), 'widgets/cms/pager');
		
		//var_dump($widget->rows);
		return $widget;
	}
	
	public function actionCache($clear = null)
	{
		$widget = new Widget('widgets/cms/cache');
		
		if ($clear === 'output')
		{
			//Fs::remove($path, true);
		}
		$output = Fs::listFiles(Cado::$outputCache, true, true);
		$widget->outputCacheCount = count($output);
		$size = 0;
		foreach ($output as $file)
		{
			$size += filesize($file);
		}
		$widget->outputCacheSize = $size;
		if ($clear === 'apc')
		{
			apc_clear_cache();
		}
		$apc_info = apc_cache_info();
		$widget->apcCacheCount = $apc_info['num_entries'];
		$widget->apcCacheSize = $apc_info['mem_size'];
		return $widget;
	}
	
	public function actionExport()
	{
		$widget = new Widget('widgets/cms/export');
		$models = $this->site->getModels();
		$tables = array();
		foreach ($models as $model)
		{
			$tables = array_merge($tables, $this->site->model($model)->getStructure());
		}
		//var_dump($tables);
		return $widget;
	}
}
