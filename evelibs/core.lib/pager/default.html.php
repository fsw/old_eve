<div class="pager">
<?php if ($this->total): ?>
	<?php echo sprintf(__('page %d of %d (%d total results)'), $this->page, $this->last, $this->total); ?>
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