<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * XSL.
 *
 * @package HostCMS 6\Xsl
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Xsl_Module extends Core_Module
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
	protected $_moduleName = 'xsl';
	
	/**
	 * Constructor.
	 */
		parent::__construct();

				'ico' => 'fa fa-code',