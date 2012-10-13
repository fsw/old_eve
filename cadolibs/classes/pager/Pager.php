<?php
/**
 * 
 * @author fsw 
 *
 * pager class
 * TODO: make it 'smart'
 */
class Pager extends Widget
{
	
	public function __construct($limit, $page, $total , $href='?page=%PAGE%')
	{
		parent::__construct('widgets/pager');
		$this->page = $page; 
  		$this->href = $href;
  		$this->total = $total;
  		$this->last = $total ? ceil($total / $limit) : 0;
	}
	
	public function hrefNextPage()
	{
		if ($this->page < $this->last)
		{
			return str_replace('%PAGE%', $this->page + 1, $this->href);
		}
		else
		{
			return null;
		}
	}
	
	public function hrefPrevPage()
	{
		if ($this->page > 1)
		{
			return str_replace('%PAGE%', $this->page - 1, $this->href);
		}
		else
		{
			return null;
		}
	}
}
