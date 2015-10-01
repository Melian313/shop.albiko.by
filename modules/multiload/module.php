<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');
 
 /**
* multiload module controller
* 
* @author Kuts Artem, KAD Systems (©) 2014	
* @date 11-06-2014
*/
 
class MultiLoad_Module extends Core_Module{	/**
	 * Module version
	 * @var string
	 */
	public $version = '1.0.6';
	public $version_number = 6;	
	private $_module_id = 2;	
	private $_internal_name = "multiload";	
	private $_client_name = "Мультизагрузка изображений";
	private $_admin_name = "Мультизагрузка";		
	private $_module_path;	
	
	/**
	 * Module date
	 * @var date
	 */
	public $date = '2013-02-15';
	/**
	 * Constructor.
	 */	public function __construct()	{
		parent::__construct();
		$this->menu = array(			array(				'sorting' => 260,				'block' => 2,				'name' => $this->_admin_name,				'href' => "/admin/" . $this->_internal_name . "/index.php",				'onclick' => "$.adminLoad({path: '/admin/" . $this->_internal_name . "/index.php'}); return false"			)		);
		
		$this->_module_path = CMS_FOLDER . "/modules/{$this->_internal_name}/";	}
	
	public function install()
	{
		// Импорт таблиц модуля
		$query = Core_File::read($this->_module_path . 'install.sql');

		// Выполняем запрос
		Sql_Controller::instance()->execute($query);

		Kad_Module_Controller::install();
		$oController = new Kad_Module_Controller($this->_module_id);			
		
		// Задаем параметры
		$oController->set('version', $this->version);		
		$oController->set('version_number', $this->version_number);
		$oController->set('internal_name', $this->_internal_name);		
		$oController->set('client_name', $this->_client_name);	
		$oController->set('admin_name', $this->_admin_name);				
		$oController->set('module_id', $this->_module_id);	
		
		echo '<div id="message">Модуль успешно установлен!</div>';
	}
	
	public function uninstall()
	{
		$query = Core_File::read($this->_module_path . 'uninstall.sql');
		
		// Выполняем запрос
		Sql_Controller::instance()->execute($query);
		
		echo '<div id="message">Модуль успешно удален!</div>';
	}
}