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
class Skin_Bootstrap_Admin_Form_Entity_Stars extends Skin_Default_Admin_Form_Entity_Input {

	public function execute()
	{
		$oScript = Admin_Form_Entity::factory('Script')
			->type("text/javascript")
			->value("$('#" . $this->id . "').rating({'step':1, 'size':'xs', 'showCaption': false, 'ratingClass':' rating-star', 'clearButtonTitle': 'Очистить'});");

		$this->add($oScript);

		return parent::execute();
	}
}