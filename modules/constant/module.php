<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Constants.
 *
 * @package HostCMS 6\Constant
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Constant_Module extends Core_Module{	/**
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
	protected $_moduleName = 'constant';
	
	/**
	 * Constructor.
	 */	public function __construct()	{
		parent::__construct();
		$this->menu = array(			array(				'sorting' => 250,				'block' => 3,
				'ico' => 'fa fa-wrench',				'name' => Core::_('constant.menu'),				'href' => "/admin/constant/index.php",				'onclick' => "$.adminLoad({path: '/admin/constant/index.php'}); return false"			)		);	}}