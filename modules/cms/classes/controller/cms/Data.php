<?php

abstract class controller_cms_Data extends controller_Cms
{
	protected $model = null;
	protected $columns = array('id');
	
	protected function getFormFields()
	{
		$fields = Site::model($this->model)->getFields();
		foreach ($fields as $key => $field)
		{
			if ($field instanceof field_relation_Many)
			{
				$values = Site::model($field->model)->getAll();
				foreach ($values as $val)
				{
					$options[$val['id']] = Site::model($field->model)->getAdminString($val);
				}
				$fields[$key] = new field_Enum($options, true);
			}
		
		}
		return $fields;	
	}
	
	protected function saveFormData($data)
	{
		if (array_key_exists('password', $data) && empty($data['password']))
		{
			unset($data['password']);
		}	
		return Site::model($this->model)->save($data);
	}
	
	public function actionIndex($getSearch = array())
	{
		$widget = new Widget('widgets/cms/list');
		
		$actions = array();
		$rowActions = array();
		$widget->readOnly = true;
		if (Site::model($this->model)->getField('enable') !== null)
		{
			$widget->toggable = true;
		}
		
		if (Site::model('users')->hasPriv('admin_' . $this->model))
		{
			$actions = array('Add' => self::hrefSave(0));
			$rowActions = array(
					'Edit' => self::hrefSave('_ID_'),
			);
			
			if (Site::model($this->model)->getField('enable') !== null)
			{
				$rowActions['On/Off'] = self::hrefToggle('_ID_');
			}
			
			$widget->readOnly = false;
		}
		
		$modelClass = Site::model($this->model);
		$where = '1';
		$bind = array();
		
		$widget->model = $this->model;
		$widget->search = $getSearch;
		
		if ($modelClass instanceof model_TreeCollection)
		{
			$widget->tree = true;
			
			$parent = empty($getSearch['parent']) ? 0 : $getSearch['parent'];
			
			$path = array();
			$path[$this->model] = self::hrefIndex(array('parent' => 0));
			
			foreach ($modelClass->getPath($parent) as $elem)
			{
				$path[$elem['title']] = self::hrefIndex(array('parent' => $elem['id']));
			}
			$widget->path = $path;
			
			$rowActions['Children'] = self::hrefIndex(array('parent' => '_ID_'));
			
			if ($widget->readOnly == false)
			{
				$actions['Add'] = self::hrefSave(0, array('parent' => $parent));
			}
			
			$where .= ' AND `parent`=?';
			$bind[] = $parent;
		}
		
		$widget->rowActions = $rowActions;
		$widget->actions = $actions; 
		
		$widget->columns = $this->columns;
		
		if (!empty($getSearch['order']))
		{
			$direction = 'ASC';
			$order = $getSearch['order'];
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
		$page = empty($getSearch['page']) ? 1 : $getSearch['page'];
		
		$widget->rows = $modelClass->search($where, $bind , $perPage, $page, $foundRows);
		
		$getSearch['page'] = '_PAGE_';
		$widget->pager = new Pager($perPage, $page, $foundRows, self::hrefIndex($getSearch), 'widgets/cms/pager');
		
		//var_dump($widget->rows);
		return $widget;
	}
	
	public function actionToggle($id)
	{
		$current = Site::model($this->model)->getById($id);
		//var_dump($current);
		Site::model($this->model)->update($id, array('enable' => !$current['enable']));
		//die();
		
		$this->redirectTo($this->request->getReferer());
	}
	
	public function actionSave($id, $getData = array())
	{
		$form = new Form();
		$form->id = $this->model . 'Form';
		$form->title = ($id == 0 ? 'Add ' : 'Edit ') . ucfirst(substr($this->model, 0, strlen($this->model) - 1));
		
		$form->addElements($this->getFormFields()); 
		
		if (!empty($id))
		{
			$current = Site::model($this->model)->getById($id);
			//var_dump($current);
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
			$ret = $this->saveFormData($form->getValues());
			if ($ret == true)
			{
				$this->redirectTo($_SESSION['saveReferer']);
			}
			else
			{
				$form->addErrors($ret);
			}
		}
		return $form;
	}
	
}
