<?php
define('MYSQL_CODEPAGE', 'utf8mb4'); // Atualizado para suporte completo a emoticons e caracteres modernos
define('MYSQL_COLLATE',  'utf8mb4_unicode_ci');
define('BASEPATH', '');
$error = false;
$step = isset($_POST['step']) ? (int)$_POST['step'] : 1;
$step_count = 4;
$MSG_PROGRESS = "";

$_TABLES['calendar'] = "`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(255) NOT NULL,`starts` datetime NOT NULL,`ends` datetime NOT NULL,`duration` bigint(20) NOT NULL,`background_color` varchar(7) NOT NULL,`color` varchar(7) NOT NULL,`repeat_type` int(5) NOT NULL,`repeat_days` varchar(50) DEFAULT NULL,`no_end` tinyint(1) DEFAULT NULL,`end_date` date DEFAULT NULL,PRIMARY KEY (`id`)";

$_TABLES['calendar_instance'] = "`id` int(11) NOT NULL AUTO_INCREMENT,`id_calendar` int(11) NOT NULL,`starts` datetime NOT NULL,`ends` datetime NOT NULL,`one-time` tinyint(1) NOT NULL,`repeat_type` int(1) NOT NULL,PRIMARY KEY (`id`)";

$_TABLES['users_groups'] = "  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,`user_id` int(11) unsigned NOT NULL,`group_id` mediumint(8) unsigned NOT NULL,PRIMARY KEY (`id`),UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),KEY `fk_users_groups_users1_idx` (`user_id`),KEY `fk_users_groups_groups1_idx` (`group_id`)";

$_TABLES['groups'] = "`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,`name` varchar(20) NOT NULL,`description` varchar(100) NOT NULL,PRIMARY KEY (`id`)";

$_TABLES['login_attempts'] = "`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`ip_address` varchar(15) NOT NULL,`login` varchar(100) NOT NULL,`time` int(11) unsigned DEFAULT NULL,PRIMARY KEY (`id`)";

$_TABLES['playlist'] = "`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(255) NOT NULL,`description` text NOT NULL,`audiostart` double(11,2) NOT NULL DEFAULT '0.00',`audioend` double(11,2) NOT NULL DEFAULT '0.00',`audiolength` double(11,2) NOT NULL DEFAULT '0.00',PRIMARY KEY (`id`)";

$_TABLES['playlist_calendar'] = "`id` int(11) NOT NULL AUTO_INCREMENT,`playlist_id` int(11) NOT NULL,`calendar_id` int(11) NOT NULL,`time_start` decimal(11,2) NOT NULL,`time_end` decimal(11,2) NOT NULL,PRIMARY KEY (`id`)";

$_TABLES['settings'] = "`time_zone` varchar(255) NOT NULL,`language` varchar(255) NOT NULL,`weekstart` int(11) NOT NULL";

$_TABLES['track'] = "`id` int(11) NOT NULL AUTO_INCREMENT,`title` varchar(255) NOT NULL,`url` varchar(255) NOT NULL,`artist` varchar(255) DEFAULT NULL,`album` varchar(255) DEFAULT NULL,`length` double(11,2) DEFAULT NULL,`mime` varchar(50) DEFAULT NULL,`genre` varchar(255) DEFAULT NULL,`bitrate` int(4) DEFAULT NULL,`samplerate` int(10) DEFAULT NULL,`channels` int(4) DEFAULT NULL,`audiostart` double(11,2) DEFAULT NULL,`audioend` double(11,2) DEFAULT NULL,`audiolength` double(11,2) DEFAULT NULL,`filename` varchar(255) NOT NULL,`filesize` double(11,2) NOT NULL,`upload_date` datetime NOT NULL,PRIMARY KEY (`id`)";

$_TABLES['track_playlist'] = "`id` int(11) NOT NULL AUTO_INCREMENT,`track_id` int(11) NOT NULL,`playlist_id` int(11) NOT NULL,`time_start` decimal(11,2) NOT NULL,`time_end` decimal(11,2) NOT NULL,PRIMARY KEY (`id`)";

$_TABLES['users'] = "`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`ip_address` varchar(45) NOT NULL,`username` varchar(100) DEFAULT NULL,`password` varchar(255) NOT NULL,`salt` varchar(255) DEFAULT NULL,`email` varchar(100) NOT NULL,`activation_code` varchar(40) DEFAULT NULL,`forgotten_password_code` varchar(40) DEFAULT NULL,`forgotten_password_time` int(11) unsigned DEFAULT NULL,`remember_code` varchar(40) DEFAULT NULL,`created_on` int(11) unsigned NOT NULL,`last_login` int(11) unsigned DEFAULT NULL,`active` tinyint(1) unsigned DEFAULT NULL,`first_name` varchar(50) DEFAULT NULL,`last_name` varchar(50) DEFAULT NULL,`company` varchar(100) DEFAULT NULL,`phone` varchar(20) DEFAULT NULL,PRIMARY KEY (`id`)";

$_ADDITIONAL_SQL[] = "INSERT INTO `settings` (`time_zone`, `language`, `weekstart`) VALUES ('Africa/Algiers', 'english', 6);";
$_ADDITIONAL_SQL[] = "INSERT INTO `groups` (`id`, `name`, `description`) VALUES (1, 'admin', 'Administrator'),(2, 'members', 'General User');";

$_ADDITIONAL_SQL[] = "ALTER TABLE `users_groups` ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION, ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;";

function checkPostData($name, $min_size, $max_size)
{
  $data = isset($_POST[$name]) ? trim($_POST[$name]) : '';
  $s = mb_strlen($data);
  if($s < $min_size || $s > $max_size) return NULL;
  return $data;
}

function htmlEntitiesEx($string)
{
  if ($string === null) return '';
  return htmlspecialchars(preg_replace('|[\x00-\x09\x0B\x0C\x0E-\x1F\x7F-\x9F]|u', ' ', $string), ENT_QUOTES, 'UTF-8');
}

function CreateTable($name)
{
	global $_TABLES;
	global $mysql_connection;
	ShowProgress("Creating table <b>'{$name}'</b>.");
	if(!@mysqli_query($mysql_connection, "DROP TABLE IF EXISTS `{$name}`") || !@mysqli_query($mysql_connection, "CREATE TABLE `{$name}` ({$_TABLES[$name]}) ENGINE=InnoDB CHARACTER SET=".MYSQL_CODEPAGE." COLLATE=".MYSQL_COLLATE))
	{
		ShowError("Failed: ".htmlEntitiesEx(mysqli_error($mysql_connection)));
		return false;
	}
	return true;
}

function exec_sql($sql)
{
	global $mysql_connection;
	if( !@mysqli_query($mysql_connection, $sql) )
	{
		ShowError("Failed: ".htmlEntitiesEx(mysqli_error($mysql_connection)));
		return false;
	}
	return true;
}

// ATUALIZADO: Removida a dependência do Bcrypt.php antigo. Agora usa o gerador nativo e seguro do PHP 8+
function hash_password($password)
{
	if (empty($password))
	{
		return FALSE;
	}
	return password_hash($password, PASSWORD_BCRYPT, ['cost' => 8]);
}

function updateConfigHelper($updateList, $name, $default)
{
  return isset($updateList[$name]) ? $updateList[$name] : $default;
}

function updateConfig($updateList)
{
	$file    = defined('FILE_CONFIG') ? FILE_CONFIG : 'config.php';
	$oldfile = $file.'.old.php';
	@chmod(@dirname($file), 0777);
	@chmod($file,           0777);
	@chmod($oldfile,        0777);
	@unlink($oldfile);
	if(is_file($file) && !@rename($file, $oldfile)){
		return false;
	}
	else
	{
		$cfgData = "<?php\n".
		"define('CONFIG_MYSQL_HOST', '".addslashes(updateConfigHelper($updateList, 'mysql_host', 'localhost'))."');\n".
		"define('CONFIG_MYSQL_USER', '".addslashes(updateConfigHelper($updateList, 'mysql_user', 'root'))."');\n".
		"define('CONFIG_MYSQL_PASS', '".addslashes(updateConfigHelper($updateList, 'mysql_pass', ''))."');\n".
		"define('CONFIG_MYSQL_DB', '".addslashes(updateConfigHelper($updateList, 'mysql_db', 'radiostation'))."');\n".
		"define('CONFIG_BASE_URL', '".addslashes(updateConfigHelper($updateList, 'base_url', ''))."');\n".
		"?>";
		if(@file_put_contents($file, $cfgData) !== strlen($cfgData))
			return false;
	}
	return true;
}

function ShowError($text)
{
  global $MSG_PROGRESS;
  $MSG_PROGRESS .= "<p class='text-error'>&#8226; ERROR: ".$text."</p>";
}

function ShowProgress($text)
{
  global $MSG_PROGRESS;
  $MSG_PROGRESS .= "<p class='text-success'>&#8226; ".$text."</p>";
}

if( isset($_POST['step']) && $_POST['step'] == "1" )
{
    $pd_mysql_host      = checkPostData('mysql_host',   1, 256);
    $pd_mysql_user      = checkPostData('mysql_user',   1, 256);
    $pd_mysql_pass      = checkPostData('mysql_pass',   0, 256);
    $pd_mysql_db        = checkPostData('mysql_db',     1, 256);
	
	if(!$error){
		if($pd_mysql_host === NULL || $pd_mysql_user === NULL || $pd_mysql_db === NULL)
		{
			ShowError('Bad format of MySQL server data.');
			$error = true;
		}
	}
	if(!$error){
		ShowProgress("Connecting to MySQL as <b>'{$pd_mysql_user}'</b>.");
		$mysql_connection = @mysqli_connect($pd_mysql_host, $pd_mysql_user, $pd_mysql_pass);

		if (mysqli_connect_errno()) {
		    ShowError("Connect failed: ". mysqli_connect_error());
			$error = true;
		}
	}

	if(!$error){
		if(!$mysql_connection || !@mysqli_query($mysql_connection, 'SET NAMES \''.MYSQL_CODEPAGE.'\' COLLATE \''.MYSQL_COLLATE.'\''))
		{
			ShowError("Failed connect to MySQL server: ".htmlEntitiesEx(mysqli_error($mysql_connection)));
			$error = true;
		}
	}
	if(!$error){
		$db = addslashes($pd_mysql_db);
		ShowProgress("Selecting DB <b>'{$pd_mysql_db}'</b>.");

		if(!@mysqli_query($mysql_connection, "CREATE DATABASE IF NOT EXISTS `{$db}`"))
		{
			ShowError("Failed to create database: ".htmlEntitiesEx(mysqli_error($mysql_connection)));
			$error = true;
		}
		else if(!@mysqli_select_db($mysql_connection, $pd_mysql_db))
		{
			ShowError("Failed to select database: ".htmlEntitiesEx(mysqli_error($mysql_connection)));
			$error = true;
		}
		@mysqli_query($mysql_connection, "ALTER DATABASE `{$db}` CHARACTER SET ".MYSQL_CODEPAGE." COLLATE ".MYSQL_COLLATE);
	}

	if( !$error ){
		$step++;
	}
}

if( isset($_POST['step']) && $_POST['step'] == "2" )
{
    $pd_mysql_host = checkPostData('mysql_host',   1, 255);
    $pd_mysql_user = checkPostData('mysql_user',   1, 255);
    $pd_mysql_pass = checkPostData('mysql_pass',   0, 255);
    $pd_mysql_db   = checkPostData('mysql_db',     1, 255);
    $pd_user       = checkPostData('user',         1,  20);
    $pd_pass       = checkPostData('pass',         6,  64);  
	$mysql_connection = @mysqli_connect($pd_mysql_host, $pd_mysql_user, $pd_mysql_pass);
	@mysqli_select_db($mysql_connection, $pd_mysql_db);

    if( $pd_user === NULL || $pd_pass === NULL )
    {
      ShowError('Bad format of login data.');
      $error = true;
    }

	if(!$error){
		foreach($_TABLES as $table => $v)
		{
			$error = !CreateTable($table);
			if($error)
				break;
		}
	}

	if(!$error){
		ShowProgress("Configure application settings ...");
		foreach($_ADDITIONAL_SQL as $sql)
		{
			$error = !exec_sql($sql);
			if($error)
				break;
		}
	}

	if(!$error)
	{
		ShowProgress("Adding user <b>'{$pd_user}'</b>.");
		$pd_pass = hash_password($pd_pass);
		$sql = "INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES (1, '127.0.0.1', '{$pd_user}', '{$pd_pass}', '', 'admin@admin.com', '', NULL, NULL, '3o6rg9FOxhHe31KAtJaLG.', 1268889823, 1483117005, 1, 'Admin', 'istrator', 'ADMIN', '0');";
		$error = !exec_sql($sql);
	}

	if(!$error)
	{
		$sql = "INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES (1, 1, 1),(2, 1, 2);";
		$error = !exec_sql($sql);
	}

	if(!$error)
	{
    	ShowProgress("Writing config file");
    	$pd_path = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    	$pd_path = substr($pd_path, 0, strripos($pd_path, "/")+1);
    
	    $updateList['mysql_host'] = $pd_mysql_host;
	    $updateList['mysql_user'] = $pd_mysql_user;
	    $updateList['mysql_pass'] = $pd_mysql_pass;
	    $updateList['mysql_db']   = $pd_mysql_db;
	    $updateList['base_url']   = $pd_path;

	    if(!updateConfig($updateList))
	    {
	      ShowError("Failed write to config file.");
	      $error = true;
	    }
	}

	if( !$error ){
		$MSG_PROGRESS .= "<p>Installation complete!</p>";
		$step++;
	}
}

if( isset($_POST['step']) && $_POST['step'] == "3" )
{
	$step++;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
	<title>Radio Station - Installer</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta content="Radio station" name="description" />
	<meta content="bessemzitouni" name="author" />
	<link rel="SHORTCUT ICON" href="assets/img/favicon.png"/>
	<link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="assets/plugins/jquery-slider/css/jquery.sidr.light.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/animate.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="assets/css/style.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/custom-icon-set.css" rel="stylesheet" type="text/css" />
	<script src="assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script> 
	<style type="text/css">
	.form-horizontal .control-group { margin-bottom: 0px;}
	</style>
</head>
<body class="">
<div class="page-container row-fluid">
  <div class="page-content" style="margin: 0 !important;"> 
    <div class="content">

	<div class="span2"></div>
	<div class="span8">
        <div class="grid simple horizontal red">
          <div class="grid-title text-left">
            <h3>·C·O·D·E·L·I·S·T·.·C·C· - - - 
            RADIO <span class="bold">STATION</span> Installer 
            <span class="label label-important pull-right">STEP <?php echo $step."/".$step_count; ?></span>
            </h3>
          </div>
          <div class="grid-body">

            <div class="form-wizard-steps">
              <ul class="wizard-steps">
                <li class="<?php echo $step==1?"active":""; ?>" ><span class="step">1</span><span class="title">MySQL server</span></li>
                <li class="<?php echo $step==2?"active":""; ?>"><span class="step">2</span><span class="title">Root user</span></li>
                <li class="<?php echo $step==3?"active":""; ?>"><span class="step">3</span><span class="title">Install</span></li>
                <li class="<?php echo $step==4?"active":""; ?>"><span class="step">4</span><span class="title">Finish</span></li>
              </ul>
              <div class="clearfix"></div>
            </div><br /><br />

<?php if ($step == 1): ?>
	<form class="form-login form-horizontal" method="post">
		<input type="hidden" name="step" value="1">
		<h4 class="semi-bold">Step 1 - <span class="light">MySQL server</span></h4>
        <?php if (isset($MSG_PROGRESS) && !empty($MSG_PROGRESS)): ?>
        	<?php echo $MSG_PROGRESS ?>
        <?php endif ?>

		<div class="control-group">
			<label class="control-label required" for="host">Host</label>
			<div class="controls">
				<input type="text" name="mysql_host" id="host" class="span6" value="<?php echo isset($_POST['mysql_host'])?htmlspecialchars($_POST['mysql_host']):"localhost" ?>" autofocus />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label required" for="user">User</label>
			<div class="controls">
				<input type="text" name="mysql_user" id="user" class="span6" value="<?php echo isset($_POST['mysql_user'])?htmlspecialchars($_POST['mysql_user']):"root" ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label required" for="pass">Password</label>
			<div class="controls">
				<input type="text" name="mysql_pass" id="pass" class="span6" value="<?php echo isset($_POST['mysql_pass'])?htmlspecialchars($_POST['mysql_pass']):"" ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label required" for="database">Database</label>
			<div class="controls">
				<input type="text" name="mysql_db" id="database" class="span6" value="<?php echo isset($_POST['mysql_db'])?htmlspecialchars($_POST['mysql_db']):"radiostation" ?>" />
			</div>
		</div>
		<div class="form-actions">
			<div class="pull-right">
				<input type="submit" name="next" value="Next" class="btn btn-primary btn-cons no-margin">
			</div>
		</div>
	</form>
<?php endif ?>


<?php if ($step == 2): ?>
	<form class="form-login form-horizontal" method="post">
		<input type="hidden" name="step" value="2">
		<input type="hidden" name="mysql_host" value="<?php echo htmlspecialchars($pd_mysql_host); ?>">
		<input type="hidden" name="mysql_user" value="<?php echo htmlspecialchars($pd_mysql_user); ?>">
		<input type="hidden" name="mysql_pass" value="<?php echo htmlspecialchars($pd_mysql_pass); ?>">
		<input type="hidden" name="mysql_db" value="<?php echo htmlspecialchars($pd_mysql_db); ?>">
		<h4 class="semi-bold">Step 2 - <span class="light">Root User</span></h4>
        <?php if (isset($MSG_PROGRESS) && !empty($MSG_PROGRESS)): ?>
        	<?php echo $MSG_PROGRESS ?>
        <?php endif ?>
        
		<div class="control-group">
			<label class="control-label required" for="user">Username</label>
			<div class="controls">
				<input type="text" name="user" id="user" class="span6" value="admin" autofocus />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label required" for="pass">Password</label>
			<div class="controls">
				<input type="password" name="pass" id="pass" class="span6" value="" />
			</div>
    		<i class="help" style="margin-left: 12px;">(6-64 characters)</i>
		</div>
		<div class="form-actions">
			<div class="pull-right">
				<input type="submit" name="next" value="Next" class="btn btn-primary btn-cons no-margin">
			</div>
		</div>
	</form>
<?php endif ?>


<?php if ($step == 3): ?>
	<form class="form-login form-horizontal" method="post">
		<input type="hidden" name="step" value="3">
		<h4 class="semi-bold">Step 3 - <span class="light">Install</span></h4>
        <?php if (isset($MSG_PROGRESS) && !empty($MSG_PROGRESS)): ?>
        	<?php echo $MSG_PROGRESS ?>
        <?php endif ?>
				
		<div class="form-actions">
			<div class="pull-right">
				<input type="submit" name="next" value="Next" class="btn btn-primary btn-cons no-margin">
			</div>
		</div>
	</form>
<?php endif ?>


<?php if ($step == 4): ?>
	<form class="form-login form-horizontal" method="post">
		<h4 class="semi-bold">Step 4 - <span class="light">Finish</span></h4>
		<div class="well well-small">
			Thank you for installing Radio<span class="semi-bold">Station</span> v 1.0 <br>
		</div>
		<div class="form-actions">
			<div class="pull-right">
				<a href="index.php" class="btn btn-primary btn-cons no-margin">Login</a>
			</div>
		</div>
	</form>
<?php endif ?>

          </div>
        </div>
      </div>
    </div>
  </div>
 </div>

<div class="modal fade" id="player_modal" style="width: 420px;margin-left:-210px;"></div>
<script src="assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min