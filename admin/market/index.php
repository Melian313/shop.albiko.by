<?php
/**
 * Market.
 *
 * @package HostCMS
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
require_once('../../bootstrap.php');

Core_Auth::authorization($sModule = 'market');

$sAdminFormAction = '/admin/market/index.php';

// Контроллер формы
$oAdmin_Form_Controller = Admin_Form_Controller::create();
$oAdmin_Form_Controller
	->module(Core_Module::factory($sModule))
	->setUp()
	->path($sAdminFormAction)
	->title(Core::_('Market.title'));

ob_start();

$oAdmin_View = Admin_View::create();
$oAdmin_View
	->module(Core_Module::factory($sModule))
	->pageTitle(Core::_('Market.title'))
	;

$oMarket_Controller = Market_Controller::instance();

// Установка модуля
if (Core_Array::getRequest('install'))
{
	$oMarket_Controller
		->setMarketOptions()
		->getModule(intval(Core_Array::getRequest('install')));
}

// Вывод списка
$data = $oMarket_Controller
	->setMarketOptions()
	->getMarket();

$sWindowId = $oAdmin_Form_Controller->getWindowId();

if ($oMarket_Controller->error == 0)
{
	$aCategories = $aItems = array();
	$aCategories = $oMarket_Controller->categories;
	$aItems = $oMarket_Controller->items;

	$aTmp = array('Выбрать категорию');

	foreach($aCategories as $object)
	{
		$aTmp[$object->id] = $object->name;
	}

	$oMainTab = Admin_Form_Entity::factory('Tab')->name('main');
	$oMainTab->add(Admin_Form_Entity::factory('Div')->class('row')->add(
		Admin_Form_Entity::factory('Select')
			->name('category_id')
			->value($oMarket_Controller->category_id)
			->onchange('changeCategory(this)')
			->options($aTmp)
			->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6'))
	));

	//$oRow = Admin_Form_Entity::factory('Div')->class('row');
	$sHtml = '<div class="market col-lg-12 col-md-12 col-sm-12 col-xs-12">';
	$count_pages = ceil($oMarket_Controller->total/$oMarket_Controller->limit);

	foreach($aItems as $object)
	{
		$sHtml .= '
			<div class="module_block col-lg-4 col-md-4 col-sm-6 col-xs-12">
				<div class="module-img"><img src="' . $object->image_small . '"/></div>
				<span class="title">' . htmlspecialchars($object->name) . '</span>
				<span class="category_name">' . htmlspecialchars($object->category_name) . '</span>';

		if ($object->installed)
		{
			$sHtml .= '<span class="installed">Установлен</span>';
		}
		else
		{
			if ($object->paid && !$object->installed || $object->price == 0)
			{
				$sHtml .= '<a class="install" onclick="res =confirm(\'' . Core::_('Market.install_warning') . '\'); if (res){ $.adminLoad({path:\'/admin/market/index.php\',action:\'\',operation:\'\',additionalParams:\'install=' . $object->id . '&category_id=' . $oMarket_Controller->category_id . '&current=' .  $oMarket_Controller->page . '\',windowId:\'' . $sWindowId . '\'}); } return false"  href="/admin/market/index.php?hostcms[window]=' . $sWindowId . '&install=' . $object->id . '&category_id=' . $oMarket_Controller->category_id . '&current=' . $oMarket_Controller->page . '">Установить</a>';
			}
			else
			{
				$sHtml .= '
					<a class="price" target="_blank" href="' .  $object->url . '">' . round($object->price) . ' ' . $object->currency . ' ▶</a>';
			}
		}

		$sHtml .= '</div>';
	}

	$sHtml .= '</div>';

	if ($oMarket_Controller->category_id && $count_pages > 1)
	{
		$sHtml .= '<div class="pagination">';
		for ($i = 1; $i <= $count_pages; $i++)
		{
			if ($oMarket_Controller->page == $i)
			{
				$sHtml .= "<span class=\"current\">{$i}</span>";
			}
			else
			{
				$sHtml .='<a class="page_link" onclick="$.adminLoad({path:\'/admin/market/index.php\',action:\'\',operation:\'\',additionalParams:\'category_id=' . $oMarket_Controller->category_id . '&current=' . $i . '\',windowId:\'' . $sWindowId . '\'}); return false"  href="/admin/market/index.php?hostcms[window]=' . $sWindowId . '&category_id=' . $oMarket_Controller->category_id . '&current=' . $i . '">' . $i . '</a>';
			}
		}

		$sHtml .= '</div>';
	}

	$oMainTab->add(Admin_Form_Entity::factory('Div')->class('row')->add(Admin_Form_Entity::factory('Code')->html($sHtml)));

	//$oMainTab->add($oRow);

	/*$aCategories = $aItems = array();

	$aCategories = $oMarket_Controller->categories;
	$aItems = $oMarket_Controller->items;

	$oAdmin_Form_Entity_Select_Category = Admin_Form_Entity::factory('Select');
	$oAdmin_Form_Entity_Select_Category
		->name('category_id')
		->value($oMarket_Controller->category_id)
		->onchange('changeCategory(this)')
		->style('width: 200px');

	$aTmp = array(
		array(
			//'attr' => array('disabled' => 'disabled'),
			'value' => 'Выбрать категорию'
		)
	);

	foreach($aCategories as $object)
	{
		$aTmp[$object->id] = $object->name;
	}

	$oAdmin_Form_Entity_Select_Category
		->options($aTmp)
		->execute();

	$count_pages = ceil($oMarket_Controller->total/$oMarket_Controller->limit);

	$sHtml = '<div class="market">';

	//$i = 1;
	$iCountItems = count($aItems);
	if($iCountItems)
	{
		foreach($aItems as $object)
		{
			$sHtml .= '
				<div class="module_block">
					<img src="' . $object->image_small . '"/>
					<span class="title">' . htmlspecialchars($object->name) . '</span>
					<span class="category_name">' . htmlspecialchars($object->category_name) . '</span>';

			if ($object->installed)
			{
				$sHtml .= '<span class="installed">Установлен</span>';
			}
			else
			{
				if ($object->paid && !$object->installed || $object->price == 0)
				{


					$sHtml .= '<a class="install" onclick="res =confirm(\'' . Core::_('Market.install_warning') . '\'); if (res){ $.adminLoad({path:\'/admin/market/index.php\',action:\'\',operation:\'\',additionalParams:\'install=' . $object->id . '&category_id=' . $oMarket_Controller->category_id . '&current=' .  $oMarket_Controller->page . '\',windowId:\'' . $sWindowId . '\'}); } return false"  href="/admin/market/index.php?hostcms[window]=' . $sWindowId . '&install=' . $object->id . '&category_id=' . $oMarket_Controller->category_id . '&current=' . $oMarket_Controller->page . '">Установить</a>';
				}
				else
				{
					$sHtml .= '
						<a class="price" target="_blank" href="' .  $object->url . '">' . round($object->price) . ' ' . $object->currency . ' ▶</a>';
				}
			}

			$sHtml .= '</div>';
		}
	}

	$sHtml .= '</div>';

	if ($oMarket_Controller->category_id && $count_pages > 1)
	{
		$sHtml .= '<div class="pagination">';
		for ($i = 1; $i <= $count_pages; $i++)
		{
			if ($oMarket_Controller->page == $i)
			{
				$sHtml .= "<span class=\"current\">{$i}</span>";
			}
			else
			{
				$sHtml .='<a class="page_link" onclick="$.adminLoad({path:\'/admin/market/index.php\',action:\'\',operation:\'\',additionalParams:\'category_id=' . $oMarket_Controller->category_id . '&current=' . $i . '\',windowId:\'' . $sWindowId . '\'}); return false"  href="/admin/market/index.php?hostcms[window]=' . $sWindowId . '&category_id=' . $oMarket_Controller->category_id . '&current=' . $i . '">' . $i . '</a>';
			}
		}

		$sHtml .= '</div>';
	}

	$sHtml .='<script type="text/javascript">
	function changeCategory(object)
	{
		if (object && object.tagName == "SELECT")
		{
			category_id = parseInt(object.options[object.selectedIndex].value);
			$.adminLoad({path: "/admin/market/index.php", windowId:"' . $sWindowId . '", additionalParams: "category_id=" + category_id});
		}
		return false;
	}</script>';*/

	$oAdmin_Form_Entity_Form = Admin_Form_Entity::factory('Form')
		->controller($oAdmin_Form_Controller)
		->action($sAdminFormAction)
		->add($oMainTab)
		->add(Admin_Form_Entity::factory('Code')
				->html('<script type="text/javascript">
				function changeCategory(object)
				{
					if (object && object.tagName == "SELECT")
					{
						category_id = parseInt(object.options[object.selectedIndex].value);
						$.adminLoad({path: "/admin/market/index.php", windowId:"' . $sWindowId . '", additionalParams: "category_id=" + category_id});
					}
					return false;
				}</script>'))
		/*->add(
			Admin_Form_Entity::factory('Code')
				->html($sHtml)
		)*/
		->execute();

	$content = ob_get_clean();

	ob_start();
	$oAdmin_View
		->content($content)
		->show();
}
else
{
	// Показ ошибок
	Core_Message::show(Core::_('Update.server_error_respond_' . $oMarket_Controller->error), 'error');
}

/*$oAdmin_Answer = Core_Skin::instance()->answer();
$oAdmin_Answer
	->ajax(Core_Array::getRequest('_', FALSE))
	->content(ob_get_clean())
	->message('')
	->title(Core::_('Market.title'))
	->execute();*/

Core_Skin::instance()
	->answer()
	->ajax(Core_Array::getRequest('_', FALSE))
	->content(ob_get_clean())
	->title(Core::_('Market.title'))
	->module($sModule)
	->execute();