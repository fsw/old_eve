
<?php if ($this->tree): ?>
<ul class="crumbs">
	<li><a href="<?php echo actions_Cms::hrefList($this->model, array('parent' => 0)); ?>"><?php echo $this->model ?></a></li>
<?php foreach($this->path as $crumb): ?>
	<li><a href="<?php echo actions_Cms::hrefList($this->model, array('parent' => $crumb['id'])); ?>"><?php echo $crumb['title'] ?></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php echo $this->pager ?>
<table class="admin">
<tr>
	<?php foreach ($this->columns as $key): ?>
		<th>
			<?php $search = $this->search; ?>
			<?php if (!empty($search['order']) && ($search['order'] == $key)) : ?>
				<?php $search['order'] = '-' . $key; ?>
				<a href="<?php echo actions_Cms::hrefList($this->model, $search); ?>">&#9660; <?php echo $key; ?></a>
			<?php elseif (!empty($search['order']) && ($search['order'] == '-' . $key)) : ?>
				<?php unset($search['order']); ?>
				<a href="<?php echo actions_Cms::hrefList($this->model, $search); ?>">&#9650; <?php echo $key; ?></a>
			<?php else: ?>
				<?php $search['order'] = $key; ?>
				<a href="<?php echo actions_Cms::hrefList($this->model, $search); ?>"><?php echo $key; ?></a>
			<?php endif; ?>
		</th>
	<?php endforeach; ?>
	<th>
		<?php foreach ($this->actions as $title=>$href): ?>
			<a title="<?php echo $title ?>" class="button" href="<?php echo $href; ?>"><?php echo $title ?></a>
		<?php endforeach; ?>
	</th>
</tr>
<?php foreach ($this->rows as $row): ?>
	<tr>
		<?php foreach ($this->columns as $key): ?>
			<td><?php echo is_array($row[$key]) ? implode(',', $row[$key]) : $row[$key]; ?></td>
		<?php endforeach; ?>
		<td class="actions">
			<?php $first = true;?>
			<?php foreach ($this->rowActions as $title=>$href): ?>
				<?php echo !$first ? '|' : '' ?>
				<a title="<?php echo $title ?>" href="<?php echo str_replace('_ID_', $row['id'], $href); ?>"><?php echo $title ?></a>
				<?php $first = false;?>
			<?php endforeach; ?>
		</td>	
	</tr>
<?php endforeach; ?>
</table>
<?php echo $this->pager ?>

