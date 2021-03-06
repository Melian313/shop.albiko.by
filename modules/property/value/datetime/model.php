<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Properties.
 *
 * @package HostCMS 6\Property
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Property_Value_Datetime_Model extends Core_Entity
{
	/**
	 * Model name
	 * @var mixed
	 */
	protected $_modelName = 'property_value_datetime';

	/**
	 * Column consist item's name
	 * @var string
	 */
	protected $_nameColumn = 'id';

	/**
	 * Disable markDeleted()
	 * @var mixed
	 */
	protected $_marksDeleted = NULL;

	/**
	 * Belongs to relations
	 * @var array
	 */
	protected $_belongsTo = array(
		'property' => array()
	);

	/**
	 * Forbidden tags. If list of tags is empty, all tags will show.
	 * @var array
	 */
	protected $_forbiddenTags = array(
		'value'
	);

	/**
	 * Set value
	 * @param string $value value
	 * @return self
	 */
	public function setValue($value)
	{
		$this->value = strval($value);
		return $this;
	}

	/**
	 * Name of tag
	 * @var string
	 */
	protected $_tagName = 'property_value';

	/**
	 * Get XML for entity and children entities
	 * @return string
	 * @hostcms-event property_value_datetime.onBeforeRedeclaredGetXml
	 */
	public function getXml()
	{
		Core_Event::notify($this->_modelName . '.onBeforeRedeclaredGetXml', $this);

		$this->clearXmlTags()
			->addXmlTag('property_dir_id', $this->Property->property_dir_id)
			->addXmlTag('tag_name', $this->Property->tag_name);

		$value = '';
		if ($this->value != '0000-00-00 00:00:00')
		{
			$value = $this->Property->type == 8
				? Core_Date::sql2date($this->value)
				: Core_Date::sql2datetime($this->value);
		}

		$this->addXmlTag('value', $value);

		return parent::getXml();
	}
}