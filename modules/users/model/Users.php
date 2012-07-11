<?php
namespace Users;

class Users extends \Collection
{
	public static function getFields()
	{
		return array_merge(
		parent::getFields(),
		array(
 			'email' => new \field_Email(),
 			'password' => new \field_Password(),
 			'name' => new \field_Text(),
 			'avatar' => new \field_Image(),
 			'bio' => new \field_Longtext(),
 			'groups' => new \relation_ManyToMany('Groups'),
		)
		);
	}

	public static function getIndexes()
	{
		return array_merge(
		parent::getIndexes(),
		array(
 			'email' => array(true, 'email'),
		)
		);
	}
	
	protected static function explode(&$row)
	{
		$row['title'] = $row['name'] . '(' . $row['email'] . ')';
		return $row;
	}

}
