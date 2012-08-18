<ul class='menu'>
	<li><a href="<?php echo $this->href('frontend', array('action' => 'news')); ?>">Aktualności</a></li>
	<li><a href="<?php echo $this->href('frontend', array('action' => 'rules')); ?>">Zasady programu</a></li>
	<li><a href="<?php echo $this->href('frontend', array('action' => 'account')); ?>">Konto uczestnika</a></li>
	<li><a href="<?php echo $this->href('frontend', array('action' => 'wiki')); ?>">Baza wiedzy</a></li>
	<li><a href="<?php echo $this->href('frontend', array('action' => 'contact')); ?>">Help-desk</a></li>
	<li><a href="<?php echo $this->href('users\\logout'); ?>">Wyloguj</a></li>
</ul>
<div id='body'>
<?php switch ($action) { 
	case 'news':
		echo new Widget('news\\news');
		break;
	case 'rules':
		echo new Widget('pages\\page', array('slug' => 'rules'));
		break;
	case 'help':
		
		break;
	case 'contact':
		
		break;
	default :
		echo new widget_404($this);
} ?>
</div>