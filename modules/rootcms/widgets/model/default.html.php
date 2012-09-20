<h1>CMS</h1>
<ul>
<?php
	foreach(Project::getModel() as $model)
	{
		echo '<li>' . Routing::link(array('model' => $model, 'id' => null), $model) . '</li>';
	}
?>
</ul>


<?php if (($model = Routing::getModel()) && in_array($model, Project::getModel())) { ?>
	
	<h2><?php echo $model; ?></h2>
	<?php
	if (($id = Routing::getId()) !== null) {
	
		$data = $id ? $model::getById($id) : array();
		$errors = array();
		if (!empty($_POST['data']))
		{
			$data = $_POST['data'];
			try
			{
				$model::save($data);
			}
			catch (ModelException $e)
			{
				$errors = $e->errors;
			}
			if (empty($errors))
			{
				echo 'OK';
			}
		}
		echo new Form('data', $model::fields(), $data, $errors);
		echo Routing::link(array('id' => null), 'Cancel');
		
	} else { 
	?>
		<table width="100%">
		<tr>
		<?php foreach ($model::fields() as $key => $field) { ?>
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
		<?php echo Routing::link(array('id' => 0), 'Add') ?>
	<?php } ?>
<?php } ?>