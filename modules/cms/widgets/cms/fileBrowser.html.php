<script type="text/javascript">
//<!--
function submitUrl(url)
{
	var win = tinyMCEPopup.getWindowArg("window");
	win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = url;

	if (typeof(win.ImageDialog) != "undefined") {
		if (win.ImageDialog.getImageData)
			win.ImageDialog.getImageData();
		if (win.ImageDialog.showPreviewImage)
			win.ImageDialog.showPreviewImage(URL);
	}

	tinyMCEPopup.close();
}
//-->
</script>
<ul>
<?php foreach ($this->files as $file): ?>
<li>
	<a href="javascript:submitUrl('<?php echo $file['file']['url'] ?>')">
		<?php echo $file['name'] ?>
		<?php echo $file['file']['url'] ?>
		<img src="<?php echo $file['file']['thumb'] ?>" />
	</a>
</li>
<?php endforeach; ?>
</ul>
