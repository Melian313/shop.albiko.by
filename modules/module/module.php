<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Modules.
 *
 * @package HostCMS 6\Module
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Module_Module extends Core_Module
	 * Module version
	 * @var string
	 */
	public $version = '6.5';

	/**
	 * Module date
	 * @var date
	 */
	public $date = '2015-06-05';
	/**
	 * Module name
	 * @var string
	 */
	protected $_moduleName = 'module';
	
	/**
	 * Constructor.
	 */
		parent::__construct();

				'ico' => 'fa fa-puzzle-piece',
	}