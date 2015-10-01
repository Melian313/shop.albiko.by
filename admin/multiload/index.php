<?php
/**
* Модуль мультизагрузки картинок 
*
* Версия для HostCMS v.6x
* @author KAD
* http://www.artemkuts.ru/
* artem.kuts@gmail.com
* Copyright © 2010-2011 ООО "Интернет-Эксперт" http://www.internet-expert.ru
*/
 
require_once('../../bootstrap.php');

$oController = new Kad_Module_Controller(2);

$module_name = $oController->get('internal_name');
$module_version = $oController->get('version');
$titles = $oController->get('client_name');

define('MODULE_SITE', 'http://artemkuts.ru/coding/hostcms/' . $module_name . '/');

$module_path = '/' . $module_name . '/';
$step = 1;
$module_link = '/admin/'.$module_name.'/index.php';
$domain = $_SERVER['HTTP_HOST'];
$sAdminFormAction = $module_path;
$fm_action = Core_Array::getGet('action', ''); 

// получаем данные												  	| step
//$module_id = Core_Array::getGet('module_id', -1); // id модуля      | 1

Core_Auth::authorization($module_name);
$Controller = new Multiload_Controller();

$site_id = CURRENT_SITE;
	
//$oAdmin_Form = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id);
$oAdmin_Form_Controller = new Admin_Form_Controller();
$oAdmin_Form_Controller
	->setUp()
	->path($module_link)
	->title($titles)
	->pageTitle($titles);
	
$sWindowId = $oAdmin_Form_Controller->getWindowId();

ob_start();

//действие "установлено"
if ( $fm_action == 'install')
{
	Core_Message::show('Модуль "' . $titles . '" версии ' . $module_version . ' успешно установлен!', 'message');
}

$oAdmin_Form_Entity_Form = new Admin_Form_Entity_Form($oAdmin_Form_Controller);

Core::factory('Admin_Form_Entity_Title')
	->name($titles)
	->execute();
	
// Меню формы
$oAdmin_Form_Entity_Menus = new Admin_Form_Entity_Menus();
$oAdmin_Form_Entity_Menus
->add(
	Core::factory('Admin_Form_Entity_Menu')
		->name('Помощь')
		->add(
			Core::factory('Admin_Form_Entity_Menu')
				->name("О модуле..")
				->img('/admin/images/bug.gif')
				->href(
					MODULE_SITE
				)
				->onclick(
					"window.open('".MODULE_SITE."'); return false;"
				)
		)
);

// Добавляем все меню контроллеру
$oAdmin_Form_Entity_Menus->execute();

$oAdmin_Form_Entity_Tabs = Admin_Form_Entity::factory('Tabs');
$oAdmin_Form_Entity_Tabs->formId(123456789);

// Вкладки
$oTabs = $oAdmin_Form_Entity_Tabs;
$oInfosystemsTab = Core::factory('Admin_Form_Entity_Tab')	
				->name('infosystem')
				->caption("Информационная система")
				->add(Core::factory('Admin_Form_Entity_Radiogroup')
					->radio(array(
						"Загрузка в основное фото", "Загрузка в дополнительное свойство"
					))
					->divAttr(array('style' => 'margin-bottom: 20px', 'id' => 'type_selector_div'))
					->id('type_selector')
					->value(0)
				)
				->add(
					Core::factory('Admin_Form_Entity_Select')
						->name('information_systems_id')
						->id("infsysselect")
						->caption("Информационная система")
						->options(array(0 => "..") + $Controller->GetInfomationSystems($site_id))
				)
				->add(
					Core::factory('Admin_Form_Entity_Select')
						->name('groups_id')
						->id("infsysgroupselect")
						->caption("Информационная группа")
				)
				->add(
					Core::factory('Admin_Form_Entity_Select')
						->name('item_id')
						->id("infsysitemselect")
						->caption("Информационный элемент")
						->divAttr(array('style' => 'display: none', 'id' => 'infsysitemselect_div'))
				)
				->add(
					Core::factory('Admin_Form_Entity_Select')
						->name('props_id')
						->id("infsyspropselect")
						->caption("Дополнительное свойство")
						->divAttr(array('style' => 'display: none', 'id' => 'infsyspropselect_div'))
				);
$oTabs->add($oInfosystemsTab);
	
if (Core::moduleIsActive('shop'))
{
	$oShopTab = Core::factory('Admin_Form_Entity_Tab');
	$oShopTab->name('shop')
			->caption("Интернет-магазин")
			->add(Core::factory('Admin_Form_Entity_Radiogroup')
				->radio(array(
					"Загрузка в дополнительное свойство"
				))
				->divAttr(array('style' => 'margin-bottom: 20px', 'id' => 'type_selector_div'))
				->id('type_shop_selector')
				->value(0)
			)
			->add(
				Core::factory('Admin_Form_Entity_Select')
					->name('information_systems_id')
					->id("shopselect")
					->caption("Интернет-магазин")
					->options(array(0 => "..") + $Controller->GetShops($site_id))
			)
			->add(
				Core::factory('Admin_Form_Entity_Select')
					->name('groups_id')
					->id("shopgroupselect")
					->caption("Раздел")
			)
			->add(
				Core::factory('Admin_Form_Entity_Select')
					->name('item_id')
					->id("shopitemselect")
					->caption("Товар")
					->divAttr(array('id' => 'shopitemselect_div'))
			)
			->add(
				Core::factory('Admin_Form_Entity_Select')
					->name('props_id')
					->id("shoppropselect")
					->caption("Дополнительное свойство")
					->divAttr(array('id' => 'shoppropselect_div'))
			);
			
	$oTabs->add($oShopTab);
}

$oAdmin_Form_Entity_Form->add($oTabs);
/// Вкладки

$oAdmin_Form_Entity_Form
	->action($sAdminFormAction)
		
	->add(		
		Core::factory('Admin_Form_Entity_Code')
			->html('<link rel="stylesheet" type="text/css" href="/admin/multiload/jquery/uploadify.css" />')
	)
	->add(Core::factory('Admin_Form_Entity_Code')
		->html("<script>$(function() {
		$('#{$sWindowId} #type_selector_div').buttonset();
		});</script>")
	)
	/*->add(		
		Core::factory('Admin_Form_Entity_Code')
			->html('<div>Инструкция:<br/>
			Выберите информационную систему и группу. Выберите также информационный элемент и дополнительное свойство, если требуется загрузить файлы в дополнительное свойство информационного элемента.<br/>
			Нажмите кнопку "Обзор", выделите группу файлов и нажмите "Открыть".<br/>
			Появится список выбранных файлов.<br/>
			Нажмите на кнопку "Загрузить" - начнется
			процесс закачки картинок на сервер.<br/>
			После завершения закачки вы увидите количество успешно загруженных файлов.</br></br>
			</div>')
	)*/
	->add(
		Core::factory('Admin_Form_Entity_Input')
			->name('loadtype')
			->id("loadtype")
			->value(1)
			->type('hidden')
	)	
	->add(
		Core::factory('Admin_Form_Entity_Input')
			->name('itemname')
			->id("itemname")
			->value("$1")
			->caption("Названия элементов ($1 заменяется на название картинки, $2 на название без расширения)")
	)		
	->add(
		Core::factory('Admin_Form_Entity_Code')
			->html('<input type="file" name="uploadify" id="uploadify" /><br /><br />')
	)
	->add(
		Core::factory('Admin_Form_Entity_Code')
			->html('<script type="text/javascript">var sc = "'.session_id().'";</script>')
	)
	->add(
		Core::factory('Admin_Form_Entity_Code')
			->html('<script type="text/javascript" src="/admin/'.$module_path.'/jquery/iexmultiload.js"></script>')
	);
	
$oAdmin_Form_Entity_Form
	->add(
		Core::factory('Admin_Form_Entity_Button')
			->name('button')
			->type('button')
			->value("Загрузить")
			->class('applyButton')
			->onclick("UploadButton()")
	)
	->execute();
	

$oAdmin_Answer = Core_Skin::instance()->answer();

$oAdmin_Answer
	->ajax(Core_Array::getRequest('_', FALSE))
	->content(ob_get_clean())
	->message('')
	->title($titles)
	->execute();