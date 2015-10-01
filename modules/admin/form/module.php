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
class Admin_Form_Module extends Core_Module
	 * Module version
	 * @var string
	 */
	public $version = '6.5';

	/**
	 * Module date
	 * @var date
	 */
	public $date = '2015-07-10';
	/**
	 * Module name
	 * @var string
	 */
	protected $_moduleName = 'admin_form';
	
	/**
	 * Constructor.
	 */
		parent::__construct();

				'sorting' => 174,
				'ico' => 'fa fa-table',