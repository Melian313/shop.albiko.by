<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * 1PS.
 *
 * @package HostCMS 6\1PS
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Oneps_Module extends Core_Module
	 * Module version
	 * @var string
	 */
	public $version = '6.5';

	/**
	 * Module date
	 * @var date
	 */
	public $date = '2014-08-22';
	/**
	 * Module name
	 * @var string
	 */
	protected $_moduleName = 'oneps';
	
	/**
	 * Constructor.
	 */
		parent::__construct();

				'ico' => 'fa fa-bell-o',