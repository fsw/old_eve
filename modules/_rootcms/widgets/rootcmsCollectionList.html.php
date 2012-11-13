
<h1>Collection: <?php echo $this->model; ?></h1>


<table class="admin">
<tr>
	<?php foreach ($this->columns as $key): ?>
		<th><?php echo $key; ?></th>
	<?php endforeach; ?>
	<th>
		<a title="Add" class="button" href="<?php echo controller_Rootcms::hrefModelerSave($this->model, 0); ?>">Add</a>
	</th>
</tr>
<?php foreach ($this->data as $row): ?>
	<tr>
		<?php foreach ($this->columns as $key): ?>
			<td><?php var_dump($row[$key]); ?></td>
		<?php endforeach; ?>
		<td class="actions">
			<a title="Edit" href="<?php echo controller_Rootcms::hrefModelerSave($this->model, $row['id']); ?>">Edit</a>
			|
			<a title="Trash" onClick="$(this).parents('tr').fadeOut(); return false;" class="apiCall" href="<?php echo controller_Api::hrefJson($this->model, 'deleteById', $row['id']); ?>">Trash</a>
		</td>	
	</tr>
<?php endforeach; ?>
</table>
<?php echo $this->pager ?>

