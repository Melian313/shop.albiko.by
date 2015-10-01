<?php
/**
* Kad modules installer v 2.2
* 
* @author Kuts Artem, KAD Systems (©) 2014	
* @date 11-06-2014
*/

// Конфигурация
define('MODULE_INTERNAL_NAME','multiload');
define('MODULE_NAME', "Мультизагрузка изображений");
define('MODULE_VERSION', "1.0.6");
define('SELF_NAME', "mlinstall.php");
define('OBSERVER_CONSTRING', "");

@set_time_limit(90000);
@error_reporting(E_ERROR);

require_once('bootstrap.php');
define('CURRENT_SITE', Core_Entity::factory('Site')->getFirstSite()->id);
$oSite = Core_Entity::factory('Site', CURRENT_SITE);
Core::initConstants($oSite);

define('MODULEFILE_PATH', 'http://artemkuts.ru/upload/modules/'.MODULE_INTERNAL_NAME.'.tar');
define('MODULE_PAGE', "http://artemkuts.ru/develop" . MODULE_INTERNAL_NAME . "/");
define('KAD_MODULE_PATH', 'http://artemkuts.ru/upload/modules/kadmodule.tar');

$session_var = MODULE_INTERNAL_NAME;

if (Core_Array::getPost('install', 0) || isset($_SESSION[$session_var]))
{
	if (!isset($_SESSION[$session_var]))
	{
		// Получение и распаковка основного архива
		$f_name = CMS_FOLDER . basename(MODULEFILE_PATH);

		if (!is_file($f_name))
		{
			Core_File::write($f_name,
				file_get_contents(MODULEFILE_PATH)
				);
		}

		if (is_file($f_name))
		{
			$Core_Tar = new Core_Tar($f_name);
			$Core_Tar->extractModify(CMS_FOLDER, CMS_FOLDER);
			@unlink($f_name);
		} else
		{
			echo("Файл {$f_name} не существует! Проверьте права на корневую директорию.");
		}
		
		// Получение и распаковка общей библиотеки для модулей
		$f_name = CMS_FOLDER . basename(KAD_MODULE_PATH);

		if (!is_file($f_name))
		{
			Core_File::write($f_name,
				file_get_contents(KAD_MODULE_PATH)
				);
		}

		if (is_file($f_name))
		{
			$Core_Tar = new Core_Tar($f_name);
			$Core_Tar->extractModify(CMS_FOLDER, CMS_FOLDER);
			@unlink($f_name);
		} else
		{
			echo("Файл {$f_name} не существует! Проверьте права на корневую директорию.");
		}
		
		// Редирект здесь нужен для того, чтобы файлы модуля 
		// перезагрузились и при установке модуля сработали 
		// только что скаченные файлы
		$_SESSION[$session_var] = 1;
		header('Location: http://'.$_SERVER['HTTP_HOST']."/".SELF_NAME);
		exit();
	} else
	{
		if (OBSERVER_CONSTRING != "")
		{
			$text = Core_File::read(CMS_FOLDER . 'bootstrap.php');
			if (strpos($text, OBSERVER_CONSTRING) == 0)
			{
				$fp = fopen( CMS_FOLDER . 'bootstrap.php', 'a');
				fwrite($fp, "\n\r".OBSERVER_CONSTRING);
				fclose($fp);
			}
		}
			
		@unlink(SELF_NAME);
		
		// Добавляем запись в модули
		$oModule = Core_Entity::factory('Module')->getByPath(MODULE_INTERNAL_NAME);
		
		if (!$oModule)
		{
			$oModule = Core_Entity::factory('Module');
			$oModule->path = MODULE_INTERNAL_NAME;
			$oModule->name = MODULE_NAME;
			$oModule->active = 0;
			$oModule->save();
		} else
		{			
			if ($oModule->active)
			{
				ob_start();
				$oModule->changeActive();
				ob_clean();			
			}
		}
		
		ob_start();
		$oModule->changeActive();
		ob_clean();	
		
		unset($_SESSION[$session_var]);
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/admin/' . MODULE_INTERNAL_NAME . '/index.php?action=install');
		exit();
	}
} else
{

	unset($_SESSION[$session_var]);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Установка модуля <?php echo MODULE_NAME;?></title>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type"></meta>
		<link type="text/css" href="http://www.hostcms.ru/download/6/install/css/style.css" rel="stylesheet"></link>
		<link type="text/css" href="http://www.hostcms.ru/download/6/install/css/skin/style.css" rel="stylesheet"></link>
	</head>
	<body class="hostcms6">

	<div id="top">
		<div id="hostCmsLogo">
			<a href="/admin/"><img id="hostCmsLogoImg" src="http://www.hostcms.ru/download/6/install/images/logo.png" alt="(^) HostCMS" title="HostCMS"></img></a>
		</div>
	</div>

	<div id="id_content" class="hostcmsWindow">
		<div id="authorization">
			<div id="form">
				
					<p><strong>Загрузка и установка модуля
						<div>							
							<img src="http://artemkuts.ru/upload/modules/<?php echo MODULE_INTERNAL_NAME;?>/<?php echo MODULE_INTERNAL_NAME;?>.png" style="float:left; padding-right:10px;"><?php echo MODULE_NAME;?>
						</div>
					</strong></p>
					<p>Версия: <strong><?php echo MODULE_VERSION;?></strong></p>

				<form name="authorization" action="./<?php echo SELF_NAME;?>" method="post">
					<p>Программа загрузит и распакует файлы модуля, а также импортирует базу данных модуля</p>
						<div style="margin: 10px 0 0 0;"><input name="install" class="start" type="submit" value="Установить"/></div>				</form>
							</div>
			<div id="rightImage" class="globe">
				<div id="theme" class="disableSelect"></div>
			</div>
		</div>

		</div>
		<div id="footer">
			<div id="links">
				<p>Страница модуля:  <a href="<?php echo MODULE_PAGE;?>"><?php echo MODULE_NAME;?></a></p>
				<p>Официальный сайт HostCMS:  <a href="http://www.hostcms.ru" target="_blank">www.hostcms.ru</a></p>
			</div>
		</div>
	</body>
</html>	
<?php
}
?>