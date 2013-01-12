<?php
/**
 */
class model_Menus extends model_Set
{
	use model_set_Tree;
	protected $useArrayCache = true;
	
	protected static function initFields()
	{
		return array_merge(
				parent::initFields(),
				array(
						'title' => new field_Text(),
						'type' => new field_Enum(array(
								'group' => 'Group',
								'content' => 'Content',
								'external' => 'External'
								)),
						'content' => new field_relation_One('contents'),
						'order' => new field_Number(),
						'enable' => new field_Bool(),
						'external' => new field_Text(),
				)
		);
	}
	
	public static function explode(&$row)
	{
		parent::explode($row);
		
		switch ($row['type'])
		{
			case 'content':
				$content = model_Contents::getById($row['content']);
				$row['href'] = empty($content) ? '#' : Site::lt('content', $content['slug']);
				break;
			case 'external':
				$row['href'] = $row['external'];
				break;	
			default:
				$row['href'] = '#';
		}
	}
	
	public static function implode(&$row)
	{
		unset($row['href']);
		parent::implode($row);
	}
		
}