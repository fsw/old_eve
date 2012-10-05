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
define('CADO_FILE_CACHE', '<?php echo $this->file_cache ?>');
define('CADO_FILE_UPLOADS', 'webroots/<?php echo $this->code ?>/uploads/');
define('CADO_DOMAIN', '<?php echo $this->domains[0] ?>');

require_once('../../cadolibs/Cado.php');
Cado::init();
echo BaseSite::factory('<?php echo $this->code ?>')->route(new Request());
