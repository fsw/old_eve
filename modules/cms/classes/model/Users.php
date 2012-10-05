<?php

class model_Users extends model_Collection
{
	public function getFields()
	{
		return array_merge(
			parent::getFields(),
			array(
	 			'email' => new field_Email(),
	 			'password' => new field_Password(),
	 			'name' => new field_Text(),
	 			'avatar' => new field_Image(),
	 			'bio' => new field_Longtext(),
	 			'groups' => new field_relation_Many('model_Groups'),
			)
		);
	}

	public function getIndexes()
	{
		return array_merge(
		parent::getIndexes(),
		array(
 			'email' => array(true, 'email'),
		)
		);
	}
	
	public function add($row)
	{
		$ret = parent::add($row);
		if ($ret === true)
		{
			$group = array(
				'name' => $row['email'],
				'description' => $row['email'] . ' group',
			);
			$mg = new model_Groups($this->db, $this->prefix);
			$gid = $mg->add($row);
			
			$row['groups'] = array($gid);	
		}
		return $ret;
	}
	
	public function getByEmail($email)
	{
		return $this->searchOne('email = ?', array($email));
	}
	
	protected function explode(&$row)
	{
		parent::explode($row);
		$row['title'] = $row['name'] . '(' . $row['email'] . ')';
	}
	
	protected function implode(&$row)
	{
		unset($row['title']);
		parent::implode($row);
	}

	public function register($email)
	{
	
	}
	
	public function login($email, $password)
	{
		$user = $this->getByEmail($email);
		if (!empty($user) && ($user['password'] == $password))
		{
			$_SESSION['user'] = $user;
		}
		return $user;
	}
	
	public function logout()
	{
		unset($_SESSION['user']);
	}
	
	public function isLoggedIn()
	{
		return !empty($_SESSION['user']);
	}
	
	public function getLoggedIn()
	{
		return empty($_SESSION['user']) ? null : $_SESSION['user'];
	}
	
}