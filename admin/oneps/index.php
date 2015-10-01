<?php
/**
 * 1PS.
 *
 * @package HostCMS
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
require_once('../../bootstrap.php');

Core_Auth::authorization($sModule = 'oneps');

$sAdminFormAction = '/admin/oneps/index.php';

// Контроллер формы
$oAdmin_Form_Controller = Admin_Form_Controller::create();
$oAdmin_Form_Controller
	->module(Core_Module::factory($sModule))
	->setUp()
	->path($sAdminFormAction)
	->title(Core::_('oneps.title'));
	
ob_start();

$oAdmin_View = Admin_View::create();
$oAdmin_View
	->module(Core_Module::factory($sModule))
	->pageTitle(Core::_('oneps.title'))
	;

Core_Message::show(Core::_('oneps.introduction'));

$oMainTab = Admin_Form_Entity::factory('Tab')->name('main');
$oMainTab->add(Admin_Form_Entity::factory('Div')->class('row')->add(
	Admin_Form_Entity::factory('Code')->html('<div class="oneps_patch form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<p><img src="/modules/oneps/image/add.png"><a href="http://go.1ps.ru/pr/p.php?610331&amp;http://1ps.ru/info/" target="_blank">Регистрация в каталогах</a></p>
		<p>Регистрация в каталогах помогает улучшить видимость сайта в поисковиках. Регистрация сайта в каталогах необходима для увеличения ссылочной массы. Это недорогой способ получения множества ссылок на Ваш сайт с нужными ключевыми словами на тематических страницах. Информация о сайте отправляется в более чем 11 000 каталогов сайтов и поисковых систем. </p>
		<input value="Заказать" class="applyButton btn btn-blue" onclick="window.open(\'http://go.1ps.ru/pr/p.php?610331&amp;http://1ps.ru/info/\'); return false" type="submit">
	</div>')
)->add(
	Admin_Form_Entity::factory('Code')->html('<div class="oneps_patch form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<p><img src="/modules/oneps/image/chart_up.png"><a href="http://go.1ps.ru/pr/p.php?610331&amp;http://1ps.ru/cost/copyright/" target="_blank">Написание оптимизированных текстов</a></p>
		<p>	Мы напишем для Вас продающие тексты, которые помогут увеличить конверсию, а значит, повысить продажи, а также информационные и оптимизированные тексты, необходимые для продвижения Вашего сайта. Новые статьи помогают продвижению, а посетители видят, что ресурс обновляется, а значит, повышается доверие к нему.</p>
		<p>Хорошие уникальные тексты – основа эффективного продвижения!</p>
		<input value="Заказать" class="applyButton btn btn-blue" onclick="window.open(\'http://go.1ps.ru/pr/p.php?610331&amp;http://1ps.ru/cost/copyright/\'); return false" type="submit">
	</div>')
))->add(Admin_Form_Entity::factory('Div')->class('row')->add(
	Admin_Form_Entity::factory('Code')->html('<div class="oneps_patch form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<p><img src="/modules/oneps/image/school_board.png"><a href="http://go.1ps.ru/pr/p.php?610331&amp;http://1ps.ru/cost/posting/" target="_blank">Регистрация в каталогах статей</a></p>
		<p>Регистрация сайта в каталогах статей также помогает улучшить видимость сайта в поисковиках. Вы получаете качественные и весомые ссылки на сайт, т.к. все отобранные каталоги хорошо индексируются поисковиками и посещаются реальными людьми. Каталоги не требуют взамен размещения своей ссылки на Вашем сайте и допускают размещение до трех ссылок со статьи.</p>
		<input value="Заказать" class="applyButton btn btn-blue" onclick="window.open(\'http://go.1ps.ru/pr/p.php?610331&amp;http://1ps.ru/cost/posting/\'); return false" type="submit">
	</div>')
)->add(
	Admin_Form_Entity::factory('Code')->html('<div class="oneps_patch form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<p><img src="/modules/oneps/image/mail.png"><a href="http://go.1ps.ru/pr/p.php?610331&amp;http://1ps.ru/cost/#dop" target="_blank">Внутренняя оптимизация сайта</a></p>
		<p>Для достижения максимального эффекта от продвижения, в первую очередь необходимо уделить внимание внутренней оптимизации сайта. Составление семантического ядра сайта, прописывание метатегов и title, расстановка заголовков h1, p, h3 на всех станицах, заполнение тегов alt и title для всех картинок, создание семантической разметки и карты сайта и это еще далеко не весь список обязательных процедур.</p>
		<p>Напишите нам, и специалисты 1PS.RU помогут подготовить Ваш сайт к продвижению - проведут все необходимые процедуры по внутренней оптимизации сайта.</p>
		<input value="Написать" class="applyButton btn btn-blue" onclick="window.open(\'http://go.1ps.ru/pr/p.php?610331&amp;http://1ps.ru/cost/#dop\'); return false" type="submit">
	</div>')
));

Admin_Form_Entity::factory('Form')
	->controller($oAdmin_Form_Controller)
	->action($sAdminFormAction)
	->add($oMainTab)
	->execute();
	
$content = ob_get_clean();

ob_start();
$oAdmin_View
	->content($content)
	->show();
	
Core_Skin::instance()
	->answer()
	->ajax(Core_Array::getRequest('_', FALSE))
	->content(ob_get_clean())
	->title(Core::_('oneps.title'))
	->module($sModule)
	->execute();