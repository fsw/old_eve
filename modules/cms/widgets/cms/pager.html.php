<div class="pager">
<?php if ($this->total): ?>
	displaying page <?php echo $this->page; ?> out of <?php echo $this->last; ?>. (<?php echo $this->total; ?> total results)
	
	<?php if ($this->last > 1): ?>
	<ul>
	<?php for ($i=1; $i<=$this->last; $i++): ?>
	<li>
		<?php if ($i == $this->page): ?>
			<strong><?php echo $i; ?></strong>
		<?php else: ?>
			<a href="<?php echo str_replace('_PAGE_', $i, $this->href); ?>"><?php echo $i; ?></a>
		<?php endif; ?>
	</li>
	<?php endfor; ?>
	</ul>
	<?php endif; ?>
<?php else: ?>
nothing found
<?php endif; ?>
</div>