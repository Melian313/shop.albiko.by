<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * SEO.
 *
 * @package HostCMS 6\Seo
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Seo_Query_Position_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		parent::setObject($object);

		$this->title(Core::_('Seo_Query_Position.edit_title'));

		$oMainTab = $this->getTab('main');

		$oMainTab
			->add($oMainRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow2 = Admin_Form_Entity::factory('Div')->class('row'));

		$aFormat = array(
			'minlen' => array('value' => 1),
			'maxlen' => array('value' => 5),
			'lib' => array('value' => 'integer')
		);

		$oMainTab
			->move($this->getField('yandex')->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6')), $oMainRow1)
			->move($this->getField('google')->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6')), $oMainRow1)
			->move($this->getField('yahoo')->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6')), $oMainRow2)
			->move($this->getField('bing')->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6')), $oMainRow2);

		return $this;
	}
}