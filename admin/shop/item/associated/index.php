<?php
/**
 * Online shop.
 *
 * @package HostCMS
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
require_once('../../../../bootstrap.php');

Core_Auth::authorization($sModule = 'shop');

// Код формы
$iAdmin_Form_Id = 79;
$sFormAction = '/admin/shop/item/associated/index.php';

$oAdmin_Form = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id);

$oShopItem = Core_Entity::factory('Shop_Item', Core_Array::getGet('shop_item_id', 0));
$oShop = $oShopItem->Shop;
$oShopGroup = $oShopItem->modification_id ? $oShopItem->Modification->Shop_Group  : $oShopItem->Shop_Group;
$oShopDir = $oShop->Shop_Dir;
$oShopGroupAssociated = Core_Entity::factory('Shop_Group', Core_Array::getGet('shop_group_id', 0));

$sFormTitle = Core::_('Shop_Item.show_tying_products_title', $oShopItem->name);

// Контроллер формы
$oAdmin_Form_Controller = Admin_Form_Controller::create($oAdmin_Form);
$oAdmin_Form_Controller
	->module(Core_Module::factory($sModule))
	->setUp()
	->path($sFormAction)
	->title($sFormTitle)
	->pageTitle($sFormTitle);

$oAdmin_Form_Entity_Breadcrumbs = Admin_Form_Entity::factory('Breadcrumbs');

// Первая крошка на список магазинов
$oAdmin_Form_Entity_Breadcrumbs->add(
	Admin_Form_Entity::factory('Breadcrumb')
	->name(Core::_('Shop.menu'))
	->href($oAdmin_Form_Controller->getAdminLoadHref(
			'/admin/shop/index.php'
		))
	->onclick($oAdmin_Form_Controller->getAdminLoadAjax(
			'/admin/shop/index.php'
		))
);

// Крошки по директориям магазинов
if($oShopDir->id)
{
	$oShopDirBreadcrumbs = $oShopDir;

	$aBreadcrumbs = array();

	do
	{
		$aBreadcrumbs[] = Admin_Form_Entity::factory('Breadcrumb')
			->name($oShopDirBreadcrumbs->name)
			->href($oAdmin_Form_Controller->getAdminLoadHref(
				'/admin/shop/index.php', NULL, NULL, "shop_dir_id={$oShopDirBreadcrumbs->id}"
			))
			->onclick($oAdmin_Form_Controller->getAdminLoadAjax(
				'/admin/shop/index.php', NULL, NULL, "shop_dir_id={$oShopDirBreadcrumbs->id}"
			));
	}while($oShopDirBreadcrumbs = $oShopDirBreadcrumbs->getParent());

	$aBreadcrumbs = array_reverse($aBreadcrumbs);

	foreach ($aBreadcrumbs as $oBreadcrumb)
	{
		$oAdmin_Form_Entity_Breadcrumbs->add($oBreadcrumb);
	}
}

// Крошка на список товаров и групп товаров магазина
$oAdmin_Form_Entity_Breadcrumbs->add(
	Admin_Form_Entity::factory('Breadcrumb')
	->name($oShop->name)
	->href($oAdmin_Form_Controller->getAdminLoadHref(
		'/admin/shop/item/index.php', NULL, NULL, "shop_id={$oShop->id}"
	))
	->onclick($oAdmin_Form_Controller->getAdminLoadAjax(
		'/admin/shop/item/index.php', NULL, NULL, "shop_id={$oShop->id}"
	))
);

// Крошки по группам товаров
if($oShopGroup->id)
{
	$oShopGroupBreadcrumbs = $oShopGroup;

	$aBreadcrumbs = array();

	do
	{
		$aBreadcrumbs[] = Admin_Form_Entity::factory('Breadcrumb')
			->name($oShopGroupBreadcrumbs->name)
			->href($oAdmin_Form_Controller->getAdminLoadHref(
				'/admin/shop/item/index.php', NULL, NULL, "shop_id={$oShop->id}&shop_group_id={$oShopGroupBreadcrumbs->id}"
			))
			->onclick($oAdmin_Form_Controller->getAdminLoadAjax(
				'/admin/shop/item/index.php', NULL, NULL, "shop_id={$oShop->id}&shop_group_id={$oShopGroupBreadcrumbs->id}"
			));
	}while($oShopGroupBreadcrumbs = $oShopGroupBreadcrumbs->getParent());

	$aBreadcrumbs = array_reverse($aBreadcrumbs);

	foreach ($aBreadcrumbs as $oBreadcrumb)
	{
		$oAdmin_Form_Entity_Breadcrumbs->add($oBreadcrumb);
	}
}

// Если товар - модификация, значит мы пришли из формы списка модификаций,
// добавляем соответствующую крошку
if($oShopItem->modification_id)
{
	// Крошка на текущую форму
	$oAdmin_Form_Entity_Breadcrumbs->add(
	Admin_Form_Entity::factory('Breadcrumb')
		->name(Core::_("Shop_Item.item_modification_title", $oShopItem->Modification->name))
		->href($oAdmin_Form_Controller->getAdminLoadHref(
			'/admin/shop/item/modification/index.php', NULL, NULL, "shop_item_id={$oShopItem->Modification->id}"
		))
		->onclick($oAdmin_Form_Controller->getAdminLoadAjax(
			'/admin/shop/item/modification/index.php', NULL, NULL, "shop_item_id={$oShopItem->Modification->id}"
		))
	);
}

// Крошка на текущую форму
$oAdmin_Form_Entity_Breadcrumbs->add(
	Admin_Form_Entity::factory('Breadcrumb')
	->name($sFormTitle)
	->href($oAdmin_Form_Controller->getAdminLoadHref(
		$oAdmin_Form_Controller->getPath(), NULL, NULL, "shop_item_id={$oShopItem->id}"
	))
	->onclick($oAdmin_Form_Controller->getAdminLoadAjax(
		$oAdmin_Form_Controller->getPath(), NULL, NULL, "shop_item_id={$oShopItem->id}"
	))
);

// Крошки по группам товаров при выборе сопутствующего товара
if($oShopGroupAssociated->id)
{
	$aBreadcrumbs = array();

	$oShopGroupBreadcrumbs = $oShopGroupAssociated;

	do
	{
		$aBreadcrumbs[] = Admin_Form_Entity::factory('Breadcrumb')
		->name($oShopGroupBreadcrumbs->name)
		->href($oAdmin_Form_Controller->getAdminLoadHref(
				$oAdmin_Form_Controller->getPath(), NULL, NULL, "shop_item_id={$oShopItem->id}&shop_group_id={$oShopGroupBreadcrumbs->id}"
		))
		->onclick($oAdmin_Form_Controller->getAdminLoadAjax(
				$oAdmin_Form_Controller->getPath(), NULL, NULL, "shop_item_id={$oShopItem->id}&shop_group_id={$oShopGroupBreadcrumbs->id}"
		));
	}while($oShopGroupBreadcrumbs = $oShopGroupBreadcrumbs->getParent());

	$aBreadcrumbs = array_reverse($aBreadcrumbs);

	foreach ($aBreadcrumbs as $oBreadcrumb)
	{
		$oAdmin_Form_Entity_Breadcrumbs->add($oBreadcrumb);
	}
}

$oAdmin_Form_Controller->addEntity($oAdmin_Form_Entity_Breadcrumbs);

// Действие "Применить"
$oAdminFormActionApply = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id)
	->Admin_Form_Actions
	->getByName('apply');

if ($oAdminFormActionApply && $oAdmin_Form_Controller->getAction() == 'apply')
{
	$Shop_Item_Associated_Apply = Admin_Form_Action_Controller::factory(
		'Shop_Item_Associated_Controller_Apply', $oAdminFormActionApply
	);

	$oAdmin_Form_Controller->addAction($Shop_Item_Associated_Apply);
}

// Действие "Копировать"
$oAdminFormActionCopy = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id)
	->Admin_Form_Actions
	->getByName('copy');

if ($oAdminFormActionCopy && $oAdmin_Form_Controller->getAction() == 'copy')
{
	$oControllerCopy = Admin_Form_Action_Controller::factory(
		'Admin_Form_Action_Controller_Type_Copy', $oAdminFormActionCopy
	);

	// Добавляем типовой контроллер редактирования контроллеру формы
	$oAdmin_Form_Controller->addAction($oControllerCopy);
}

// Группы товаров
$oAdmin_Form_Dataset = new Admin_Form_Dataset_Entity(Core_Entity::factory('Shop_Group'));

$oAdmin_Form_Dataset
	->addCondition(array('select' => array('shop_groups.*', /*array('shop_groups.id', 'key'),*/ array(Core_QueryBuilder::expression("''"), 'count'))))
	->addCondition(array('where' => array('shop_id', '=', $oShop->id)))
	->addCondition(array('where' => array('parent_id', '=', intval($oShopGroupAssociated->id))))
;

$oAdmin_Form_Dataset
	->changeField('name', 'type', 4)
	->changeField('name', 'link',
		'/admin/shop/item/associated/index.php?shop_id={shop_id}&shop_group_id={id}&shop_item_id=' . $oShopItem->id)
	->changeField('name', 'onclick',
		"$.adminLoad({path: '/admin/shop/item/associated/index.php', additionalParams: 'shop_id={shop_id}&shop_group_id={id}&shop_item_id={$oShopItem->id}', windowId: '{windowId}'}); return false")
;

$oAdmin_Form_Controller->addDataset($oAdmin_Form_Dataset);

// Товары
$oAdmin_Form_Dataset = new Admin_Form_Dataset_Entity(Core_Entity::factory('Shop_Item'));

$oAdmin_Form_Dataset
	->addCondition(array('select' => array('shop_items.*', /*array('shop_items.id', 'key'),*/ 'count')))
	->addCondition(array('where' => array('shop_id', '=', $oShop->id)))
	->addCondition(array('where' => array('shop_group_id', '=', intval($oShopGroupAssociated->id))))
	->addCondition(array('where' => array('id', '!=', intval($oShopItem->id))))
	->addCondition(array('where' => array('modification_id', '=', 0)))
	->addCondition(array('leftJoin' => array('shop_item_associated', 'shop_item_associated.shop_item_associated_id', '=', 'shop_items.id', array(
	array('AND' => array('shop_item_id', '=', intval($oShopItem->id)))
))));

$oAdmin_Form_Controller->addDataset($oAdmin_Form_Dataset);

$oAdmin_Form_Controller->execute();