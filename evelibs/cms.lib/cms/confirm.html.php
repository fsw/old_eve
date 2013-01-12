<?php if ($this->errors === false): ?>
<b>Your email is confirmed</b>
<?php else: ?>
<b>Errors</b>
<?php var_dump($this->errors) ?>
<?php endif; ?>