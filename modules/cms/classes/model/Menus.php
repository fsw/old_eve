<?php
class model_Menus extends model_TreeCollection
{
	protected $useArrayCache = true;
	
	protected function initFields()
	{
		return array_merge(
				parent::initFields(),
				array(
						//'slug' => new field_Text(),
						'title' => new field_Text(),
						'type' => new field_Enum(array(
								'group' => 'Group',
								'content' => 'Content',
								'external' => 'External'
								)),
						'content' => new field_relation_One('contents'),
						'order' => new field_Int(),
						'enable' => new field_Bool(),
						'external' => new field_Text(),
				)
		);
	}
	
	public function explode(&$row)
	{
		parent::explode($row);
		
		switch ($row['type'])
		{
			case 'content':
				$content = $this->getSibling('contents')->getById($row['content']);
				$row['href'] = controller_Content::hrefIndex($content['slug']);
				break;
			case 'external':
				$row['href'] = $row['external'];
				break;	
			default:
				$row['href'] = '#';
		}
	}
	
	public function implode(&$row)
	{
		unset($row['href']);
		parent::implode($row);
	}
		
}