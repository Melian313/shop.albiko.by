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
class Admin_Form_Module extends Core_Module{	/**
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
	 */	public function __construct()	{
		parent::__construct();
		$this->menu = array(			array(
				'sorting' => 174,				'block' => 3,
				'ico' => 'fa fa-table',				'name' => Core::_('Admin_Form.menu'),				'href' => "/admin/admin_form/index.php",				'onclick' => "$.adminLoad({path: '/admin/admin_form/index.php'}); return false"			)		);	}}