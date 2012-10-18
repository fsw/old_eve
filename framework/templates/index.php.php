<?php echo '<?php'; ?>

/**
 *
 * @author fsw
 *
 */

define('CADO_DEV', <?php echo $this->dev ? 'true' : 'false' ?>);
define('CADO_DB_DSN', '<?php echo $this->db_dsn ?>');
define('CADO_DB_USER', '<?php echo $this->db_user ?>');
define('CADO_DB_PASS', '<?php echo $this->db_pass ?>');

<?php if (!empty($this->db_dsn)): ?>
define('CADO_SLAVE_DSN', '<?php echo $this->slave_dsn ?>');
define('CADO_SLAVE_USER', '<?php echo $this->slave_user ?>');
define('CADO_SLAVE_PASS', '<?php echo $this->slave_pass ?>');
<?php endif; ?>

define('CADO_FILE_CACHE', '<?php echo $this->file_cache ?>');
define('CADO_FILE_UPLOADS', '<?php echo $this->webroot ?>/uploads/');
define('CADO_DOMAIN', '<?php echo $this->domains[0] ?>');
define('CADO_HOST', '<?php echo $this->host ?>');
define('CADO_PUSHID', '<?php echo $this->pushid ?>');

require_once('<?php echo $this->root ?>/cadolibs/Cado.php');
Cado::init();
echo BaseSite::factory('<?php echo $this->code ?>')->route(new Request());
