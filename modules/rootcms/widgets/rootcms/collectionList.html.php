
<h2><?php echo $this->model; ?></h2>
<table width="100%">
<tr>
<?php /*foreach (($this->model)::fields() as $key => $field) { ?>
	<th><?php echo $key ?></th>
<?php } ?>
<th>actions</th>
</tr>
<?php $list = $model::search(array()); ?>
<?php foreach($list as $row){ ?>
	<tr>
	<?php foreach ($model::fields() as $key => $field) { ?>
		<td><?php var_dump($row[$key]) ?></td>
	<?php } ?>
	<td>
		<?php echo Routing::link(array('id' => $row['id']), 'Edit') ?>
	</td>
	</tr>
<?php } ?>
</table>
<?php echo Routing::link(array('id' => 0), 'Add') */?>