<?php

abstract class cms_Data extends Cms
{	
	protected $modelClass = null;
	
	protected function getColumns()
	{
		return array('id');
	}
	
	protected function getFormFields()
	{
		$class = $this->modelClass;
		$fields = $class::getFields();
		if (array_key_exists('password', $fields))
		{
			unset($fields['password']);
		}
		return $fields;	
	}
	
	protected function saveFormData($data)
	{
		if (array_key_exists('password', $data) && empty($data['password']))
		{
			unset($data['password']);
		}	
		$class = $this->modelClass;
		return $class::save($data);
	}
	
	public function actionIndex($getSearch = array())
	{
		$actions = array();
		$rowActions = array();
		$this->readOnly = true;
		$this->toggable = false;
		$model = $this->modelClass;
		
		if ($model::getField('enable') !== null)
		{
			$this->toggable = true;
		}
		
		if ($model::canAdmin())
		{
			$actions = array('Add' => Site::lt($this->path . '/save', 0));
			$rowActions = array(
					'Edit' => Site::lt($this->path . '/save', '_ID_'),
			);
			
			if ($model::getField('enable') !== null)
			{
				$rowActions['On/Off'] = Site::lt($this->path . '/save', '_ID_');
			}
			
			$this->readOnly = false;
		}
		if (isset($model::$versioned))
		{
			$rowActions['Revisions'] = Site::lt($this->path . '/revisions', '_ID_');
		}
		
		$where = '1';
		$bind = array();
		
		$this->search = $getSearch;
		$this->tree = false;
		$this->indexPath = $this->path;
		
		if ($model instanceof model_set_Tree)
		{
			$this->tree = true;
			
			$parent = empty($getSearch['parent']) ? 0 : $getSearch['parent'];
			
			$path = array();
			$path['root'] = Site::lt($this->path, array('parent' => 0));
			
			foreach ($model::getPath($parent) as $elem)
			{
				$path[$elem['title']] = Site::lt($this->path, array('parent' => $elem['id']));
			}
			
			$rowActions['Children'] = Site::lt($this->path, array('parent' => '_ID_'));

			if ($this->readOnly == false)
			{
				$actions['Add'] = Site::lt($this->path . '/save', 0, array('parent' => $parent));
			}
			
			$where .= ' AND `parent`=?';
			$bind[] = $parent;
			$this->treePath = $path;
		}
		
		$this->rowActions = $rowActions;
		$this->actions = $actions; 
		
		$this->columns = $this->getColumns();
		
		if (!empty($getSearch['order']))
		{
			$direction = 'ASC';
			$order = $getSearch['order'];
			if (strpos($order, '-') === 0)
			{
				$direction = 'DESC';
				$order = substr($order, 1);
			}
			if (in_array($order, $this->columns))
			{
				$where .= ' ORDER BY `' . $order . '` ' . $direction;
			}
		}
		$perPage = 30;
		$page = empty($getSearch['page']) ? 1 : $getSearch['page'];
		
		$this->rows = $model::search($where, $bind , $perPage, $page, $foundRows);
		
		$getSearch['page'] = '_PAGE_';
		$this->pager = new Pager($perPage, $page, $foundRows, Site::lt($this->path, $getSearch));
		
	}
	
	public function actionToggle($id)
	{
		$class = $this->modelClass;
		$current = $class::getById($id);
		//var_dump($current);
		$class::update($id, array('enable' => !$current['enable']));
		//die();
		
		$this->redirectTo($this->request->getReferer());
	}
	
	public function actionSave($id, $getData = array())
	{
		$form = new Form();
		$form->id = 'form';
		$form->title = ($id == 0 ? 'Add ' : 'Edit ');
		$modelClass = $this->modelClass;
		$form->addElements($this->getFormFields()); 
		
		if (!empty($id))
		{
			$current = $modelClass::getById($id);
			$form->setValues($current);
		}
		elseif (!empty($getData))
		{
			$form->setValues($getData);
		}
		
		if (!$form->isSubmitted())
		{
			$_SESSION['saveReferer'] = $this->request->getReferer();
		}
		if ($form->validate())
		{
			try
			{
				$ret = $this->saveFormData($form->getValues());
			}
			catch (model_Exception $e)
			{
				$form->addErrors($e->getErrors());
			}
			
			if ($form->isValid())
			{
				$this->redirectTo($_SESSION['saveReferer']);
			}
		}
		return $form;
	}
	
}
