<?php 

class ModelDbTest extends PHPUnit_Framework_TestCase
{
	public function testDbStructure()
	{
		$tables = array();
		foreach (Eve::getDescendants('Model') as $class)
		{
			$tables = array_merge($tables, $class::_getDbStructure());
		}
		
		$tools = new db_Tools(new Db(Config::get('model', 'db', array())));
		$sqls = $tools->diffStructures($tools->getStructure(), $tables);

		$sql = '';
		foreach ($sqls as $key => $q)
		{
			$sql .= '-- ' . $key . NL;
			$sql .= $q . ';' . NL;
			if (Eve::isDev())
			{
				//Model::getDb()->query($sql);
			}
		}
		$this->assertEmpty($sqls, $sql);
	}
}