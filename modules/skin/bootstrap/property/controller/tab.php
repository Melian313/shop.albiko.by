<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Admin forms.
 *
 * @package HostCMS 6\Admin
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Skin_Bootstrap_Property_Controller_Tab extends Property_Controller_Tab {

	protected function _imgBox($oAdmin_Form_Entity, $oProperty, $addFunction = '$.cloneProperty', $deleteOnclick = '$.deleteNewProperty(this)')
	{
		$windowId = $this->_Admin_Form_Controller->getWindowId();
		
		$oAdmin_Form_Entity
			->add(
				Admin_Form_Entity::factory('Div')
					->class('input-group-addon no-padding add-remove-property')
					->add(
						Admin_Form_Entity::factory('Div')
						//->class('no-padding-right col-lg-12')
						->class('no-padding-' . ($oProperty->type == 2 || $oProperty->type == 5 || $oProperty->type == 12 ? 'left' : 'right') . ' col-lg-12')
						->add(
							Admin_Form_Entity::factory('Div')
								->class('btn btn-palegreen')
								->add(Admin_Form_Entity::factory('Code')->html('<i class="fa fa-plus-circle close"></i>'))
								->onclick("{$addFunction}('{$windowId}', '{$oProperty->id}'); event.stopPropagation();")
						)
						->add(
							Admin_Form_Entity::factory('Div')
								->class('btn btn-darkorange')
								->add(Admin_Form_Entity::factory('Code')->html('<i class="fa fa-minus-circle close"></i>'))
								->onclick($deleteOnclick . '; event.stopPropagation();')
						)
					)
			)
			//->add($this->_getImgAdd($oProperty, $addFunction))
			//->add($this->_getImgDelete($deleteOnclick))
			;

		return $this;
	}
}