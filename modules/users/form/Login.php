<?php
/**
 * 
 * @author fsw
 *
 */

class users_form_Login extends Form
{
	protected function getFields()
	{
		return array(
			'email' => new \field_Email(),
			'password' => new \field_Password()
		);
	}
	
	public function handlePost($data)
	{
		$ret = parent::handlePost($data);
		if (!empty($ret))
		{
			return $ret;
		}
		$user = Users::login($data['email'], $data['password']);
		if (!empty($user))
		{
			\Routing::redirectTo(\Routing::linkToAction('index'));
		}
		return array('Wrong email or password');
	}
}
