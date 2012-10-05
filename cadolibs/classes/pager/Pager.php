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
}
