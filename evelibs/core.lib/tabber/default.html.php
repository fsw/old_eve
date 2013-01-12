<ul class="tabber">
	<?php $i = 0; ?>
	<?php foreach ($this->tabs as $tab => $url): ?>
	<?php $i++; ?>
	<li class="t<? echo $i; ?>">
		<?php if ($i == $this->current): ?>
			<strong title="<?php echo $tab; ?>"><?php echo $tab; ?></strong>
		<?php else: ?>
			<a title="<?php echo $tab; ?>" href="<?php echo $url; ?>"><?php echo $tab; ?></a>
		<?php endif; ?>
	</li>
	<?php endforeach; ?>
</ul>