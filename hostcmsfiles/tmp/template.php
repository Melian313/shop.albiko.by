<?php
// Временно до внедрения в систему!
/*header("Content-type: text/html; charset=UTF-8");
!isset($aReplace) && $aReplace = array();
require_once(dirname(__FILE__) . '/../' . 'bootstrap.php');
define('CURRENT_SITE', 1);
$oSite = Core_Entity::factory('Site', CURRENT_SITE);
Core::initConstants($oSite);*/

/*
 * $aReplace содержит массив замен по схеме:
 * $aReplace['%имя_макроса%'] = 'значение для замены';
 */

$tmpDir = CMS_FOLDER . TMP_DIR;

$Install_Controller = Install_Controller::instance();
$Install_Controller->setTemplatePath($tmpDir);

$sCurrentDate = date('d.m.Y H:i:s');
$sql_current_date = date('Y-m-d H:i:s');

$sSiteName = Core_Array::get($aReplace, '%company_name%', 'undefined');
$sCompanyEmail = Core_Array::get($aReplace, '%company_email%', 'undefined@undefined.com');
$sCompanyPhone = Core_Array::get($aReplace, '%company_phone%', '');
$sCompanyAddress = Core_Array::get($aReplace, '%company_address%', '');

// Будет выбираться язык
$sLng = 'ru';

//Массив языков
$aSitei18n = array(
	'ru' => array(
		'name' => 'Демонстрационый сайт HostCMS 6',
		'sitePostfix' => 'Сайт%d',
	),
	'en' => array(
		'name' => 'HostCMS 6 demosite',
		'sitePostfix' => 'Site%d',
	),
);

//Массив XSL-шаблонов
$aXsli18n = array(
	'ru' => array(
		//Услуги
		'13.xsl' => array('name' => 'СписокУслуг', 'dirName' => 'Услуги'),
		'16.xsl' => array('name' => 'ВыводУслуги', 'dirName' => 'Услуги'),
		'7.xsl' => array('name' => 'ОтправкаПисьмаАдминистраторуДобавлениеКомментария', 'dirName' => 'Новости и статьи'),
		'5.xsl' => array('name' => 'УведомлениеДобавлениеКомментария', 'dirName' => 'Новости и статьи'),

		//Новости
		'3.xsl' => array('name' => 'СписокЭлементовИнфосистемы', 'dirName' => 'Новости и статьи'),
		'4.xsl' => array('name' => 'ВыводЕдиницыИнформационнойСистемы', 'dirName' => 'Новости и статьи'),

		//Гостевая книга
		'10.xsl' => array('name' => 'СписокЗаписейГостевойКниги', 'dirName' => 'Гостевая книга'),

		//Фотогалерея
		'11.xsl' => array('name' => 'СписокКартинок', 'dirName' => 'Фотогалерея'),
		'12.xsl' => array('name' => 'ОтображениеПодробнойИнформацииОФотографии', 'dirName' => 'Фотогалерея'),

		//Поиск
		'21.xsl' => array('name' => 'Поиск', 'dirName' => 'Поиск'),

		//Обратная связь
		'22.xsl' => array('name' => 'ОтобразитьФорму', 'dirName' => 'Формы'),
		'24.xsl' => array('name' => 'ПисьмоКураторуФормы', 'dirName' => 'Формы'),

		//Личный кабинет
		'27.xsl' => array('name' => 'ЛичныйКабинетПользователя', 'dirName' => 'Пользователи сайта'),

		//Восстановление пароля
		'59.xsl' => array('name' => 'ПисьмоВосстановлениеПароля', 'dirName' => 'Пользователи сайта'),

		//Пользователи сайта/Почтовые рассылки
		'29.xsl' => array('name' => 'СписокРассылок', 'dirName' => 'Пользователи сайта/Почтовые рассылки'),

		//Регистрация
		'60.xsl' => array('name' => 'ПисьмоПодтверждениеРегистрации', 'dirName' => 'Пользователи сайта'),
		'28.xsl' => array('name' => 'РегистрацияПользователя', 'dirName' => 'Пользователи сайта'),

		//Заказы
		'74.xsl' => array('name' => 'СписокЗаказов', 'dirName' => 'Интернет-магазин'),

		//Информация о пользователе
		'174.xsl' => array('name' => 'АнкетныеДанные', 'dirName' => 'Пользователи сайта'),

		//Пользователи сайта/Лицевые счета
		'178.xsl' => array('name' => 'СписокЛицевыхСчетов', 'dirName' => 'Пользователи сайта/Лицевые счета'),
		'179.xsl' => array('name' => 'ДвиженияПоЛицевомуСчету', 'dirName' => 'Пользователи сайта/Лицевые счета'),

		//Пополнение лицевого счета
		'134.xsl' => array('name' => 'ПополнениеЛицевогоСчетаРеквизиты', 'dirName' => 'Пользователи сайта/Лицевые счета'),
		'135.xsl' => array('name' => 'ПополнениеЛицевогоСчетаПлатежныеСистемы', 'dirName' => 'Пользователи сайта/Лицевые счета'),

		//Код приглашения
		'221.xsl' => array('name' => 'КодПриглашения', 'dirName' => 'Пользователи сайта'),

		//Структура приглашенных
		'186.xsl' => array('name' => 'ПарнерскиеПрограммы', 'dirName' => 'Пользователи сайта'),

		//Бонусы
		'202.xsl' => array('name' => 'ВыводБонусов', 'dirName' => 'Пользователи сайта'),

		//Служба поддержки
		'183.xsl' => array('name' => 'ВыводСпискаТикетов', 'dirName' => 'Службы техподдержки'),
		'184.xsl' => array('name' => 'ВыводСпискаСообщенийТикета', 'dirName' => 'Службы техподдержки'),
		'185.xsl' => array('name' => 'ВыводРежимаРаботыСлужбыТехподдержки', 'dirName' => 'Службы техподдержки'),

		//Мои объявления
		'187.xsl' => array('name' => 'ВыводСпискаОбъявленийПользователя', 'dirName' => 'Пользователи сайта'),
		'188.xsl' => array('name' => 'ФормаРедактированияОбъявленияПользователя', 'dirName' => 'Пользователи сайта'),

		//Личные сообщения
		'225.xsl' => array('name' => 'ЛичныеСообщения', 'dirName' => 'Пользователи сайта/Личные сообщения'),
		'226.xsl' => array('name' => 'ЛентаЛичныхСообщений', 'dirName' => 'Пользователи сайта/Личные сообщения'),

		//Почтовые рассылки
		'30.xsl' => array('name' => 'ПочтовыеРассылки', 'dirName' => 'Почтовые рассылки'),

		//Опросы
		'67.xsl' => array('name' => 'ОтображениеРезультатовОпроса', 'dirName' => 'Опросы'),
		'64.xsl' => array('name' => 'ОтображениеОпросаБезРезультатов', 'dirName' => 'Опросы'),

		//Доска объявлений
		'173.xsl' => array('name' => 'ПодробнаяИнформацияОбОбъявлении', 'dirName' => 'Доска объявлений'),
		'172.xsl' => array('name' => 'СписокОбъявлений', 'dirName' => 'Доска объявлений'),

		//Форум
		'40.xsl' => array('name' => 'СозданиеТемы', 'dirName' => 'Форумы'),
		'87.xsl' => array('name' => 'ПисьмоДобавленияСообщенияПользователю', 'dirName' => 'Форумы/Письма'),
		'43.xsl' => array('name' => 'ПисьмоОДобавленииСообщения', 'dirName' => 'Форумы/Письма'),
		'86.xsl' => array('name' => 'ПисьмоРедактированияСообщенияПользователю', 'dirName' => 'Форумы/Письма'),
		'88.xsl' => array('name' => 'ПисьмоРедактированияСообщенияКуратору', 'dirName' => 'Форумы/Письма'),
		'41.xsl' => array('name' => 'РедактированиеСообщения', 'dirName' => 'Форумы'),
		'39.xsl' => array('name' => 'СообщенияТемы', 'dirName' => 'Форумы'),
		'38.xsl' => array('name' => 'ТемыФорума', 'dirName' => 'Форумы'),
		'42.xsl' => array('name' => 'АнкетныеДанныеПользователя', 'dirName' => 'Форумы/Устаревшие'),
		'37.xsl' => array('name' => 'Форумы', 'dirName' => 'Форумы'),
		'84.xsl' => array('name' => 'СообщенияПользователя', 'dirName' => 'Форумы'),
		'44.xsl' => array('name' => 'ПисьмоОДобавленииТемы', 'dirName' => 'Форумы/Письма'),

		//Карта сайта
		'47.xsl' => array('name' => 'КартаСайта', 'dirName' => 'Карта сайта'),

		//Реклама
		'94.xsl' => array('name' => 'ОтображениеБаннера', 'dirName' => 'Реклама'),

		//Google SiteMap
		'177.xsl' => array('name' => 'GoogleSiteMap', 'dirName' => 'Google SiteMap'),

		//Интернет-магазин
		'55.xsl' => array('name' => 'МагазинКаталогТоваров', 'dirName' => 'Интернет-магазин'),
		'56.xsl' => array('name' => 'МагазинТовар', 'dirName' => 'Интернет-магазин'),
		'15.xsl' => array('name' => 'ПисьмоАдминистраторуОДобавленииКомментарияВФорматеHTML', 'dirName' => 'Интернет-магазин'),
		'76.xsl' => array('name' => 'ПисьмоАдминистратору', 'dirName' => 'Интернет-магазин'),
		'77.xsl' => array('name' => 'ПисьмоПользователю', 'dirName' => 'Интернет-магазин'),
		'231.xsl' => array('name' => 'МагазинПоследнийЗаказ', 'dirName' => 'Интернет-магазин'),
		'180.xsl' => array('name' => 'ОблакоТэговМагазин', 'dirName' => 'Интернет-магазин'),
		'230.xsl' => array('name' => 'МагазинФильтр', 'dirName' => 'Интернет-магазин'),

		//Производители
		'219.xsl' => array('name' => 'МагазинПроизводитель', 'dirName' => 'Интернет-магазин'),
		'214.xsl' => array('name' => 'МагазинСписокПроизводителей', 'dirName' => 'Интернет-магазин'),
		'232.xsl' => array('name' => 'МенюПроизводители', 'dirName' => 'Интернет-магазин'),

		//Прайс-лист
		'83.xsl' => array('name' => 'МагазинПрайс', 'dirName' => 'Интернет-магазин'),

		//Сравнение товаров
		'72.xsl' => array('name' => 'СравнениеТоваров', 'dirName' => 'Интернет-магазин'),

		//Продавцы
		'71.xsl' => array('name' => 'МагазинПродавец', 'dirName' => 'Интернет-магазин'),
		'70.xsl' => array('name' => 'МагазинСписокПродавцов', 'dirName' => 'Интернет-магазин'),

		//Корзина
		'62.xsl' => array('name' => 'МагазинДоставки', 'dirName' => 'Интернет-магазин'),
		'61.xsl' => array('name' => 'МагазинАдресДоставки', 'dirName' => 'Интернет-магазин'),
		'68.xsl' => array('name' => 'МагазинПлатежнаяСистема', 'dirName' => 'Интернет-магазин'),
		'69.xsl' => array('name' => 'ОформлениеЗаказа', 'dirName' => 'Интернет-магазин'),
		'57.xsl' => array('name' => 'МагазинКорзина', 'dirName' => 'Интернет-магазин'),
		'79.xsl' => array('name' => 'МагазинКорзинаКраткая', 'dirName' => 'Интернет-магазин'),
		'58.xsl' => array('name' => 'МагазинБыстраяРегистрация', 'dirName' => 'Интернет-магазин'),

		//Menu
		'2.xsl' => array('name' => 'ВерхнееМеню', 'dirName' => 'Меню'),
		'224.xsl' => array('name' => 'НижнееМеню', 'dirName' => 'Меню'),

		//Other
		'189.xsl' => array('name' => 'СписокНовостейНаГлавной', 'dirName' => 'Новости и Статьи'),
		'6.xsl' => array('name' => 'СписокУслугНаГлавной', 'dirName' => 'Услуги'),
		'176.xsl' => array('name' => 'МагазинКаталогТоваровНаГлавнойСпецПред', 'dirName' => 'Интернет-магазин'),
		'182.xsl' => array('name' => 'МагазинГруппыТоваровНаГлавной', 'dirName' => 'Интернет-магазин'),
		'132.xsl' => array('name' => 'ХлебныеКрошки', 'dirName' => 'Хлебные крошки'),
	),
	'en' => array(
		'222.xsl' => array('name' => 'NewsList', 'dirName' => 'Siteusers/Affiliats')
	),
);

//Массив типовых динамических страниц
$aLibi18n = array(
	'ru' => array(
		//Информационные системы
		1 => array(
			'name' => 'Информационная система',
			'dirName' => 'Информационные системы',
			'code' => 'lib_1.php',
			'config' => 'lib_config_1.php',
			'params' => array(
				91 => array(
					'name' => 'Код информационной системы',
					'varible_name' => 'informationsystemId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `informationsystems` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				93 => array(
					'name' => 'Текстовая информация для указания номера страницы',
					'varible_name' => 'page',
					'type' => 'input',
					'default_value' => 'страница',
					'sorting' => 30,
				),
				94 => array(
					'name' => 'Разделитель в заголовке страницы',
					'varible_name' => 'separator',
					'type' => 'input',
					'default_value' => ' -',
					'sorting' => 40,
				),
				114 => array(
					'name' => 'Отображать комментарии',
					'varible_name' => 'showComments',
					'type' => 'checkbox',
					'sorting' => 50,
				),
				395 => array(
					'name' => 'Отображать добавление комментария',
					'varible_name' => 'showAddComment',
					'type' => 'list',
					'values' => array(
						0 => 'Никому',
						1 => 'Только авторизированным',
						2 => 'Всем',
					),
					'sorting' => 52,
				),
				115 => array(
					'name' => 'Добавленные комментарии публиковать сразу',
					'varible_name' => 'addedCommentActive',
					'type' => 'checkbox',
					'sorting' => '55',
				),
				193 => array(
					'name' => 'Формат письма с уведомлением о добавлении комментария',
					'varible_name' => 'commentMailNoticeType',
					'type' => 'list',
					'values' => array(
						0 => 'HTML',
						1 => 'Текст',
					),
					'sorting' => 56,
				),
				95 => array(
					'name' => 'XSL шаблон для отображения списка элементов информационной системы',
					'varible_name' => 'informationsystemXsl',
					'type' => 'xsl',
					'default_value' => 'СписокЭлементовИнфосистемы',
					'sorting' => 60,
				),
				96 => array(
					'name' => 'XSL шаблон для отображения элемента информационной системы',
					'varible_name' => 'informationsystemItemXsl',
					'type' => 'xsl',
					'default_value' => 'ВыводЕдиницыИнформационнойСистемы',
					'sorting' => 70,
				),
				97 => array(
					'name' => 'XSL шаблон для отправки уведомления администратору о добавлении комментария',
					'varible_name' => 'addCommentAdminMailXsl',
					'type' => 'xsl',
					'default_value' => 'ОтправкаПисьмаАдминистраторуДобавлениеКомментария',
					'sorting' => 80,
				),
				98 => array(
					'name' => 'XSL шаблон для уведомления пользователя о добавлении комментария',
					'varible_name' => 'addCommentNoticeXsl',
					'type' => 'xsl',
					'default_value' => 'УведомлениеДобавлениеКомментария',
					'sorting' => 90,
				)
			)
		),
		2 => array(
			'name' => 'Информационная система для Вопросы-ответы, Гостевая книга и т.п.',
			'dirName' => 'Информационные системы',
			'code' => 'lib_2.php',
			'config' => 'lib_config_2.php',
			'params' => array(
				100 => array(
					'name' => 'Код информационной системы',
					'varible_name' => 'informationsystemId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `informationsystems` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				397 => array(
					'name' => 'Статус добавляемых элементов',
					'varible_name' => 'addedItemActive',
					'type' => 'list',
					'values' => array(
						0 => 'Публиковать после проверки',
						1 => 'Сразу публиковать',
					),
					'default_value' => 0,
					'sorting' => 15,
				),
				102 => array(
					'name' => 'Текстовая информация для указания номера страницы',
					'varible_name' => 'page',
					'type' => 'input',
					'default_value' => 'страница',
					'sorting' => 30,
				),
				103 => array(
					'name' => 'Разделитель в заголовке страницы',
					'varible_name' => 'separator',
					'type' => 'input',
					'default_value' => ' -',
					'sorting' => 40,
				),
				108 => array(
					'name' => 'Идентификатор дополнительного свойства "Автор"',
					'varible_name' => 'authorPropertyId',
					'type' => 'input',
					'sorting' => 50,
				),
				413 => array(
					'name' => 'Отображать добавление комментария',
					'varible_name' => 'showAddComment',
					'type' => 'list',
					'values' => array(
						0 => 'Никому',
						1 => 'Только авторизированным',
						2 => 'Всем',
					),
					'sorting' => 52,
				),
				496 => array(
					'name' => 'Добавленный ответ(комментарий) публиковать сразу',
					'varible_name' => 'addedCommentActive',
					'type' => 'list',
					'values' => array(
						0 => 'Нет',
						1 => 'Да',
					),
					'sorting' => 53,
				),
				109 => array(
					'name' => 'Идентификатор дополнительного свойства "E-mail"',
					'varible_name' => 'emailPropertyId',
					'type' => 'input',
					'sorting' => 55,
				),
				345 => array(
					'name' => 'Формат письма с уведомлением о добавлении комментария',
					'varible_name' => 'commentMailNoticeType',
					'type' => 'list',
					'values' => array(
						0 => 'HTML',
						1 => 'Текст',
					),
					'sorting' => 56,
				),
				104 => array(
					'name' => 'XSL шаблон для отображения списка элементов информационной системы',
					'varible_name' => 'informationsystemXsl',
					'type' => 'xsl',
					'sorting' => 60,
				),
				105 => array(
					'name' => 'XSL шаблон для отображения элемента информационной системы',
					'varible_name' => 'informationsystemItemXsl',
					'type' => 'xsl',
					'sorting' => 70,
				),
				106 => array(
					'name' => 'XSL шаблон для отправки уведомления администратору о добавлении комментария',
					'varible_name' => 'addCommentAdminMailXsl',
					'type' => 'xsl',
					'default_value' => 'ОтправкаПисьмаАдминистраторуДобавлениеКомментария',
					'sorting' => 80,
				),
				107 => array(
					'name' => 'XSL шаблон для уведомления пользователя о добавлении комментария',
					'varible_name' => 'addCommentNoticeXsl',
					'type' => 'xsl',
					'default_value' => 'УведомлениеДобавлениеКомментария',
					'sorting' => 90,
				)
			)
		),
		37 => array(
			'name' => 'Информационная система для глоссария',
			'dirName' => 'Информационные системы',
			'code' => 'lib_37.php',
			'config' => 'lib_config_37.php',
			'params' => array(
				'546' => array(
					'name' => 'Код информационной системы',
					'varible_name' => 'informationsystemId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `informationsystems` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				548 => array(
					'name' => 'Текстовая информация для указания номера страницы',
					'varible_name' => 'page',
					'type' => 'input',
					'default_value' => 'страница',
					'sorting' => 30,
				),
				549 => array(
					'name' => 'Разделитель в заголовке страницы',
					'varible_name' => 'separator',
					'type' => 'input',
					'default_value' => ' -',
					'sorting' => 40,
				),
				550 => array(
					'name' => 'Отображать комментарии',
					'varible_name' => 'showComments',
					'type' => 'checkbox',
					'sorting' => 50,
				),
				551 => array(
					'name' => 'Отображать добавление комментария',
					'varible_name' => 'showAddComment',
					'type' => 'list',
					'values' => array(
						0 => 'Никому',
						1 => 'Только авторизированным',
						2 => 'Всем',
					),
					'sorting' => 52,
				),
				552 => array(
					'name' => 'Добавленные комментарии публиковать сразу',
					'varible_name' => 'addedCommentActive',
					'type' => 'checkbox',
					'sorting' => 55,
				),
				553 => array(
					'name' => 'Формат письма с уведомлением о добавлении комментария',
					'varible_name' => 'commentMailNoticeType',
					'type' => 'list',
					'values' => array(
						0 => 'HTML',
						1 => 'Текст',
					),
					'sorting' => 56,
				),
				554 => array(
					'name' => 'XSL шаблон для отображения списка элементов информационной системы',
					'varible_name' => 'informationsystemXsl',
					'type' => 'xsl',
					'default_value' => 'СписокЭлементовИнфосистемы',
					'sorting' => 60,
				),
				555 => array(
					'name' => 'XSL шаблон для отображения элемента информационной системы',
					'varible_name' => 'informationsystemItemXsl',
					'type' => 'xsl',
					'default_value' => 'ВыводЕдиницыИнформационнойСистемы',
					'sorting' => 70,
				),
				556 => array(
					'name' => 'XSL шаблон для отправки уведомления администратору о добавлении комментария',
					'varible_name' => 'addCommentAdminMailXsl',
					'type' => 'xsl',
					'default_value' => 'ОтправкаПисьмаАдминистраторуДобавлениеКомментария',
					'sorting' => 80,
				),
				557 => array(
					'name' => 'XSL шаблон для уведомления пользователя о добавлении комментария',
					'varible_name' => 'addCommentNoticeXsl',
					'type' => 'xsl',
					'default_value' => 'УведомлениеДобавлениеКомментария',
					'sorting' => 90,
				)
			)
		),
		44 => array(
			'name' => 'Информационная система для Фотогалереи',
			'dirName' => 'Информационные системы',
			'code' => 'lib_44.php',
			'config' => 'lib_config_44.php',
			'params' => array(
				591 => array(
					'name' => 'Код информационной системы',
					'varible_name' => 'informationsystemId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `informationsystems` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				593 => array(
					'name' => 'Текстовая информация для указания номера страницы',
					'varible_name' => 'page',
					'type' => 'input',
					'default_value' => 'страница',
					'sorting' => 30,
				),
				594 => array(
					'name' => 'Разделитель в заголовке страницы',
					'varible_name' => 'separator',
					'type' => 'input',
					'default_value' => ' -',
					'sorting' => 40,
				),
				595 => array(
					'name' => 'Отображать комментарии',
					'varible_name' => 'showComments',
					'type' => 'checkbox',
					'sorting' => 50,
				),
				596 => array(
					'name' => 'Отображать добавление комментария',
					'varible_name' => 'showAddComment',
					'type' => 'list',
					'values' => array(
						0 => 'Никому',
						1 => 'Только авторизированным',
						2 => 'Всем',
					),
					'sorting' => 52,
				),
				597 => array(
					'name' => 'Добавленные комментарии публиковать сразу',
					'varible_name' => 'addedCommentActive',
					'type' => 'checkbox',
					'sorting' => 55,
				),
				598 => array(
					'name' => 'Формат письма с уведомлением о добавлении комментария',
					'varible_name' => 'commentMailNoticeType',
					'type' => 'list',
					'values' => array(
						0 => 'HTML',
						1 => 'Текст',
					),
					'sorting' => 56,
				),
				599 => array(
					'name' => 'XSL шаблон для отображения списка элементов информационной системы',
					'varible_name' => 'informationsystemXsl',
					'type' => 'xsl',
					'default_value' => 'СписокКартинок',
					'sorting' => 60,
				),
				600 => array(
					'name' => 'XSL шаблон для отображения элемента информационной системы',
					'varible_name' => 'informationsystemItemXsl',
					'type' => 'xsl',
					'default_value' => 'ОтображениеПодробнойИнформацииОФотографии',
					'sorting' => 70,
				),
				601 => array(
					'name' => 'XSL шаблон для отправки уведомления администратору о добавлении комментария',
					'varible_name' => 'addCommentAdminMailXsl',
					'type' => 'xsl',
					'default_value' => 'ОтправкаПисьмаАдминистраторуДобавлениеКомментария',
					'sorting' => 80,
				),
				602 => array(
					'name' => 'XSL шаблон для уведомления пользователя о добавлении комментария',
					'varible_name' => 'addCommentNoticeXsl',
					'type' => 'xsl',
					'default_value' => 'УведомлениеДобавлениеКомментария',
					'sorting' => 90,
				)
			)
		),
		//Службы техподдержки
		33 => array(
			'name' => 'Служба техподдержки',
			'dirName' => 'Службы техподдержки',
			'code' => 'lib_33.php',
			'config' => 'lib_config_33.php',
			'params' => array(
				425 => array(
					'name' => 'Код службы техподдержки',
					'varible_name' => 'helpdeskId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `helpdesks` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				426 => array(
					'name' => 'Число выводимых тикетов на страницу',
					'varible_name' => 'itemsOnPage',
					'type' => 'input',
					'default_value' => 10,
					'sorting' => 20,
				),
				429 => array(
					'name' => 'Текстовая информация для указания номера страницы',
					'varible_name' => 'page',
					'type' => 'input',
					'default_value' => 'страница',
					'sorting' => 30,
				),
				430 => array(
					'name' => 'Разделитель в заголовке страницы',
					'varible_name' => 'separator',
					'type' => 'input',
					'default_value' => ' — ',
					'sorting' => 40,
				),
				427 => array(
					'name' => 'XSL шаблон для отображения списка тикетов службы техподдежки',
					'varible_name' => 'helpdeskXsl',
					'type' => 'xsl',
					'default_value' => 'ВыводСпискаТикетов',
					'sorting' => 50,
				),
				428 => array(
					'name' => 'XSL шаблон для отображения сообщений тикета',
					'varible_name' => 'ticketXsl',
					'type' => 'xsl',
					'default_value' => 'ВыводСпискаСообщенийТикета',
					'sorting' => 60,
				),
				431 => array(
					'name' => 'XSL-шаблон для отображения графика работы службы техподдержки',
					'varible_name' => 'workingHoursXsl',
					'type' => 'xsl',
					'default_value' => 'ВыводРежимаРаботыСлужбыТехподдержки',
					'sorting' => 70,
				)
			)
		),
		//Google SiteMap
		29 => array(
			'name' => 'Google SiteMap',
			'dirName' => 'Google SiteMap',
			'code' => 'lib_29.php',
			'config' => 'lib_config_29.php',
			'params' => array(
				405 => array(
					'name' => 'XSL-шаблон',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'default_value' => 'GoogleSiteMap',
					'sorting' => 10,
				),
				403 => array(
					'name' => 'Отображать группы информационных систем',
					'varible_name' => 'showInformationsystemGroups',
					'type' => 'checkbox',
					'sorting' => 20,
				),
				404 => array(
					'name' => 'Отображать элементы информационных систем',
					'varible_name' => 'showInformationsystemItems',
					'type' => 'checkbox',
					'sorting' => 30,
				),
				414 => array(
					'name' => 'Отображать группы магазина',
					'varible_name' => 'showShopGroups',
					'type' => 'checkbox',
					'sorting' => 40,
				),
				415 => array(
					'name' => 'Отображать товары магазина',
					'varible_name' => 'showShopItems',
					'type' => 'checkbox',
					'sorting' => 50,
				),
				587 => array(
					'name' => 'Отображать модификации товара',
					'varible_name' => 'showModifications',
					'type' => 'checkbox',
					'sorting' => 60,
				),
				589 => array(
					'name' => 'Создать индекс Sitemap',
					'varible_name' => 'createIndex',
					'type' => 'checkbox',
					'sorting' => 60,
				),
			)
		),
		//Доска объявлений
		4 => array(
			'name' => 'Доска объявлений',
			'dirName' => 'Доска объявлений',
			'code' => 'lib_4.php',
			'config' => 'lib_config_4.php',
			'params' => array(
				389 => array(
					'name' => 'Идентификатор доски объявлений',
					'varible_name' => 'shopId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `shops` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				390 => array(
					'name' => 'XSL раздела',
					'varible_name' => 'shopXsl',
					'type' => 'xsl',
					'sorting' => 20,
				),
				391 => array(
					'name' => 'XSL объявления',
					'varible_name' => 'shopItemXsl',
					'type' => 'xsl',
					'sorting' => 30,
				),
				398 => array(
					'name' => 'Отображать объявление после добавления',
					'varible_name' => 'addedItemActive',
					'type' => 'list',
					'values' => array(
						0 => 'Нет',
						1 => 'Да',
					),
					'sorting' => 40,
				),
				399 => array(
					'name' => 'Максимальная высота большого изображения',
					'varible_name' => 'imageMaxLargeHeight',
					'type' => 'input',
					'default_value' => 500,
					'sorting' => 50,
				),
				400 => array(
					'name' => 'Максимальная ширина большого изображения',
					'varible_name' => 'imageMaxLargeWidth',
					'type' => 'input',
					'default_value' => 500,
					'sorting' => 60,
				),
				401 => array(
					'name' => 'Максимальная высота малого изображения',
					'varible_name' => 'imageMaxSmallHeight',
					'type' => 'input',
					'default_value' => 100,
					'sorting' => 70,
				),
				402 => array(
					'name' => 'Максимальная ширина малого изображения',
					'varible_name' => 'imageMaxSmallWidth',
					'type' => 'input',
					'default_value' => 100,
					'sorting' => 80,
				),
			)
		),
		//Почтовые рассылки
		19 => array(
			'name' => 'Почтовые рассылки',
			'dirName' => 'Почтовые рассылки',
			'code' => 'lib_19.php',
			'config' => 'lib_config_19.php',
			'params' => array(
				140 => array(
					'name' => 'XSL почтовых рассылок',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'default_value' => 'ПочтовыеРассылки',
					'sorting' => 10,
				),
			)
		),
		//Форум
		17 => array(
			'name' => 'Форум',
			'dirName' => 'Форум',
			'code' => 'lib_17.php',
			'config' => 'lib_config_17.php',
			'params' => array(
				125 => array(
					'name' => 'Идентификатор конференции',
					'varible_name' => 'forum_id',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `forums` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				126 => array(
					'name' => 'XSL создания темы',
					'varible_name' => 'newTopicXsl',
					'type' => 'xsl',
					'default_value' => 'СозданиеТемы',
					'sorting' => 20,
				),
				127 => array(
					'name' => 'XSL письма пользователю о добавления сообщения',
					'varible_name' => 'addMessageUserNotificationXsl',
					'type' => 'xsl',
					'default_value' => 'ПисьмоДобавленияСообщенияПользователю',
					'sorting' => 30,
				),
				128 => array(
					'name' => 'XSL письма куратору о добавления сообщения',
					'varible_name' => 'addMessageAdminNotificationXsl',
					'type' => 'xsl',
					'default_value' => 'ПисьмоОДобавленииСообщения',
					'sorting' => 40,
				),
				129 => array(
					'name' => 'XSL письма пользователю о редактировании сообщения',
					'varible_name' => 'editMessageUserNotificationXsl',
					'type' => 'xsl',
					'default_value' => 'ПисьмоРедактированияСообщенияПользователю',
					'sorting' => 50,
				),
				130 => array(
					'name' => 'XSL письма куратору о редактировании сообщения',
					'varible_name' => 'editMessageAdminNotificationXsl',
					'type' => 'xsl',
					'default_value' => 'ПисьмоРедактированияСообщенияКуратору',
					'sorting' => 60,
				),
				131 => array(
					'name' => 'XSL редактирования сообщения',
					'varible_name' => 'editMessageXsl',
					'type' => 'xsl',
					'default_value' => 'РедактированиеСообщения',
					'sorting' => 70,
				),
				132 => array(
					'name' => 'XSL сообщений темы',
					'varible_name' => 'topicMessagesXsl',
					'type' => 'xsl',
					'default_value' => 'СообщенияТемы',
					'sorting' => 80,
				),
				133 => array(
					'name' => 'XSL тем форума',
					'varible_name' => 'topicsXsl',
					'type' => 'xsl',
					'default_value' => 'ТемыФорума',
					'sorting' => 90,
				),
				134 => array(
					'name' => 'XSL анкетных данных пользователя',
					'varible_name' => 'userInfoXsl',
					'type' => 'xsl',
					'default_value' => 'АнкетныеДанныеПользователя',
					'sorting' => 100,
				),
				135 => array(
					'name' => 'XSL списка форумов',
					'varible_name' => 'forumXsl',
					'type' => 'xsl',
					'default_value' => 'Форумы',
					'sorting' => 110,
				),
				507 => array(
					'name' => 'XSL письмо о добавлении темы',
					'varible_name' => 'addTopicAdminNotificationXsl',
					'type' => 'xsl',
					'default_value' => 'ПисьмоОДобавленииТемы',
					'sorting' => 120,
				),
				505 => array(
					'name' => 'XSL списка сообщений пользователя',
					'varible_name' => 'messagesXsl',
					'type' => 'xsl',
					'default_value' => 'СообщенияПользователя',
					'sorting' => 130,
				),
				506 => array(
					'name' => 'Число сообщений пользователя на страницу',
					'varible_name' => 'itemsOnPage',
					'type' => 'input',
					'default_value' => 10,
					'sorting' => 140,
				),
			)
		),
		//Опросы
		16 => array(
			'name' => 'Опросы',
			'dirName' => 'Опросы',
			'code' => 'lib_16.php',
			'config' => 'lib_config_16.php',
			'params' => array(
				118 => array(
					'name' => 'Группа опросов',
					'varible_name' => 'pollGroupId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `poll_groups` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				119 => array(
					'name' => 'Число опросов, выводимых в списке',
					'varible_name' => 'count',
					'type' => 'input',
					'default_value' => 1,
					'sorting' => 20,
				),
				121 => array(
					'name' => 'Направление сортировки',
					'varible_name' => 'orderDirection',
					'type' => 'list',
					'values' => array(
						'RAND()' => 'В произвольном порядке',
						'desc' => 'По-убыванию',
						'asc' => 'По-возрастанию',
					),
					'sorting' => 30,
				),
				122 => array(
					'name' => 'Показать результаты до голосования',
					'varible_name' => 'showResultsWithoutVoting',
					'type' => 'checkbox',
					'sorting' => 40,
				),
				123 => array(
					'name' => 'XSL-шаблон для отображения результатов опросов',
					'varible_name' => 'pollResultXsl',
					'type' => 'xsl',
					'default_value' => 'ОтображениеРезультатовОпроса',
					'sorting' => 50,
				),
				124 => array(
					'name' => 'XSL-шаблон для отображения списка опросов',
					'varible_name' => 'pollGroupXsl',
					'type' => 'xsl',
					'default_value' => 'ОтображениеОпросаБезРезультатов',
					'sorting' => 60,
				),
			)
		),
		//Пользователи сайта
		23 => array(
			'name' => 'Пользователи сайта',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_23.php',
			'config' => 'lib_config_23.php',
			'params' => array(
				111 => array(
					'name' => 'XSL личного кабинета',
					'varible_name' => 'userAuthorizationXsl',
					'type' => 'xsl',
					'default_value' => 'ЛичныйКабинетПользователя',
					'sorting' => 10,
				),
			)
		),
		24 => array(
			'name' => 'Регистрация',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_24.php',
			'config' => 'lib_config_24.php',
			'params' => array(
				138 => array(
					'name' => 'XSL письма подтверждения',
					'varible_name' => 'xslRestorePasswordMailXsl',
					'type' => 'xsl',
					'default_value' => 'ПисьмоПодтверждениеРегистрации',
					'sorting' => 10,
				),
				139 => array(
					'name' => 'XSL регистрации пользователя',
					'varible_name' => 'userRegistrationXsl',
					'type' => 'xsl',
					'default_value' => 'РегистрацияПользователя',
					'sorting' => 20,
				),
			)
		),
		25 => array(
			'name' => 'Заказы',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_25.php',
			'config' => 'lib_config_25.php',
			'params' => array(
				558 => array(
					'name' => 'Идентификатор магазина',
					'varible_name' => 'shopId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `shops` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				112 => array(
					'name' => 'XSL заказов',
					'varible_name' => 'orderXsl',
					'type' => 'xsl',
					'sorting' => 20,
				),
			)
		),
		26 => array(
			'name' => 'Восстановление пароля',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_26.php',
			'config' => 'lib_config_26.php',
			'params' => array(
				113 => array(
					'name' => 'XSL письма восстанавления пароля',
					'varible_name' => 'xslRestorePasswordMailXsl',
					'type' => 'xsl',
					'sorting' => 10,
				),
			)
		),
		27 => array(
			'name' => 'Почтовые рассылки',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_27.php',
			'config' => 'lib_config_27.php',
			'params' => array(
				137 => array(
					'name' => 'XSL списка рассылок',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'default_value' => 'СписокРассылок',
					'sorting' => 10,
				),
			)
		),
		28 => array(
			'name' => 'Информация о пользователе',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_28.php',
			'config' => 'lib_config_28.php',
			'params' => array(
				396 => array(
					'name' => 'XSL информации о пользователе',
					'varible_name' => 'userInfoXsl',
					'type' => 'xsl',
					'default_value' => 'АнкетныеДанные',
					'sorting' => 10,
				),
			)
		),
		30 => array(
			'name' => 'Лицевой счет',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_30.php',
			'config' => 'lib_config_30.php',
			'params' => array(
				406 => array(
					'name' => 'XSL-шаблон списка лицевых счетов',
					'varible_name' => 'siteuserAccountsXsl',
					'type' => 'xsl',
					'default_value' => 'СписокЛицевыхСчетов',
					'sorting' => 10,
				),
				407 => array(
					'name' => 'XSL-шаблон транзакций лицевого счета',
					'varible_name' => 'siteuserAccountTransactionsXsl',
					'type' => 'xsl',
					'default_value' => 'ДвиженияПоЛицевомуСчету',
					'sorting' => 20,
				),
			)
		),
		31 => array(
			'name' => 'Пополнение лицевого счета пользователя',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_31.php',
			'config' => 'lib_config_31.php',
			'params' => array(
				408 => array(
					'name' => 'XSL реквизитов адреса доставки',
					'varible_name' => 'deliveryAddressXsl',
					'type' => 'xsl',
					'default_value' => 'ПополнениеЛицевогоСчетаРеквизиты',
					'sorting' => 10,
				),
				545 => array(
					'name' => 'XSL платежной системы',
					'varible_name' => 'paymentSystemXsl',
					'type' => 'xsl',
					'default_value' => 'ПополнениеЛицевогоСчета',
					'sorting' => 20,
				),
			)
		),
		34 => array(
			'name' => 'Аффилиаты',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_34.php',
			'config' => 'lib_config_34.php',
			'params' => array(
				566 => array(
					'name' => 'XSL-шаблон для отображения кода приглашения',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'default_value' => 'КодПриглашения',
					'sorting' => 10,
				),
			)
		),
		35 => array(
			'name' => 'Мои объявления',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_35.php',
			'config' => 'lib_config_35.php',
			'params' => array(
				603 => array(
					'name' => 'Идентификатор доски объявлений',
					'varible_name' => 'shopId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `shops` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 0,
				),
				604 => array(
					'name' => 'XSL формы редактирования',
					'varible_name' => 'shopItemXsl',
					'type' => 'xsl',
					'sorting' => 10,
				),
				432 => array(
					'name' => 'XSL раздела для отображения списка объявлений',
					'varible_name' => 'shopXsl',
					'type' => 'xsl',
					'sorting' => 20,
				),
				433 => array(
					'name' => 'Число выводимых объявлений на страницу',
					'varible_name' => 'itemsOnPage',
					'type' => 'input',
					'default_value' => 10,
					'sorting' => 30,
				),
				434 => array(
					'name' => 'Текстовая информация для указания номера страницы',
					'varible_name' => 'page',
					'type' => 'input',
					'default_value' => 'страница',
					'sorting' => 40,
				),
				435 => array(
					'name' => 'Разделитель в заголовке страницы',
					'varible_name' => 'separator',
					'type' => 'input',
					'default_value' => ' — ',
					'sorting' => 50,
				),
			)
		),
		38 => array(
			'name' => 'Партнерские программы',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_38.php',
			'config' => 'lib_config_38.php',
			'params' => array(
				564 => array(
					'name' => 'XSL-шаблон личного кабинета',
					'varible_name' => 'personalAreaXsl',
					'type' => 'xsl',
					'default_value' => 'ЛичныйКабинетПользователя',
					'sorting' => 10,
				),
			)
		),
		39 => array(
			'name' => 'Структура приглашенных',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_39.php',
			'config' => 'lib_config_39.php',
			'params' => array(
				565 => array(
					'name' => 'XSL-шаблон для отображения партнерских программ',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'default_value' => 'ПарнерскиеПрограммы',
					'sorting' => 10,
				),
			)
		),
		40 => array(
			'name' => 'Бонусы',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_40.php',
			'config' => 'lib_config_40.php',
			'params' => array(
				567 => array(
					'name' => 'XSL-шаблон для вывода бонусов по партнерской программе',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'default_value' => 'ВыводБонусов',
					'sorting' => 10,
				),
			)
		),
		42 => array(
			'name' => 'Личные сообщения',
			'dirName' => 'Пользователи сайта',
			'code' => 'lib_42.php',
			'config' => 'lib_config_42.php',
			'params' => array(
				578 => array(
					'name' => 'Xsl-шаблон',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'default_value' => 'ЛичныеСообщения',
					'sorting' => 10,
				),
				579 => array(
					'name' => 'Xsl-шаблон ленты сообщений',
					'varible_name' => 'messagesListXsl',
					'type' => 'xsl',
					'default_value' => 'ЛентаЛичныхСообщений',
					'sorting' => 20,
				),
				580 => array(
					'name' => 'Количество сообщений на странице',
					'varible_name' => 'itemsOnPage',
					'type' => 'input',
					'default_value' => 30,
					'sorting' => 30,
				),
			)
		),
		//Формы
		18 => array(
			'name' => 'Отображение формы',
			'dirName' => 'Формы',
			'code' => 'lib_18.php',
			'config' => 'lib_config_18.php',
			'params' => array(
				80 => array(
					'name' => 'Код формы',
					'varible_name' => 'formId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `forms` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				81 => array(
					'name' => 'Тип отправляемого письма',
					'varible_name' => 'mailType',
					'type' => 'list',
					'values' => array(
						0 => 'HTML',
						1 => 'Текст',
					),
					'sorting' => 20,
				),
				82 => array(
					'name' => 'Имя поля, содержащего эл. почту отправителя',
					'varible_name' => 'emailFieldName',
					'type' => 'input',
					'default_value' => 'email',
					'sorting' => 30,
				),
				84 => array(
					'name' => 'XSL шаблон для письма куратору формы',
					'varible_name' => 'notificationMailXsl',
					'type' => 'xsl',
					'sorting' => 40,
				),
				85 => array(
					'name' => 'XSL шаблон для отображения формы',
					'varible_name' => 'formXsl',
					'type' => 'xsl',
					'sorting' => 50,
				),
			)
		),
		//RSS
		14 => array(
			'name' => 'RSS канал для информационной системы',
			'dirName' => 'RSS',
			'code' => 'lib_14.php',
			'config' => 'lib_config_14.php',
			'params' => array(
				55 => array(
					'name' => 'Код информационной системы',
					'varible_name' => 'informationsystemId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `informationsystems` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				56 => array(
					'name' => 'Число выводимых элементов в ленте',
					'varible_name' => 'count',
					'type' => 'input',
					'default_value' => 10,
					'sorting' => 20,
				),
				57 => array(
					'name' => 'Позиция, с которой начинается вывод элементов',
					'varible_name' => 'begin',
					'type' => 'input',
					'default_value' => 0,
					'sorting' => 30,
				),
				58 => array(
					'name' => 'Код выводимой группы',
					'varible_name' => 'informationGroupId',
					'type' => 'input',
					'default_value' => 'false',
					'sorting' => 40,
				),
				59 => array(
					'name' => 'Удалять теги из RSS',
					'varible_name' => 'stripTags',
					'type' => 'checkbox',
					'sorting' => 50,
				),
				60 => array(
					'name' => 'Показывать в формате Yandex:full-text',
					'varible_name' => 'yandexFullText',
					'type' => 'checkbox',
					'sorting' => 60,
				),
				75 => array(
					'name' => 'Заголовок RSS-канала',
					'varible_name' => 'rssTitle',
					'type' => 'input',
					'sorting' => 70,
				),
				76 => array(
					'name' => 'Описание RSS-канала',
					'varible_name' => 'rssDescription',
					'type' => 'input',
					'sorting' => 80,
				),
				77 => array(
					'name' => 'URL RSS-канала',
					'varible_name' => 'rssUrl',
					'type' => 'input',
					'sorting' => 90,
				),
				79 => array(
					'name' => 'Изображение для RSS-канала (URL)',
					'varible_name' => 'rssImage',
					'type' => 'input',
					'sorting' => 100,
				),
				78 => array(
					'name' => 'Отображать изображение для информационного элемента в RSS',
					'varible_name' => 'showImage',
					'type' => 'checkbox',
					'sorting' => 110,
				),
			)
		),
		15 => array(
			'name' => 'Импорт RSS-каналов',
			'dirName' => 'RSS',
			'code' => 'lib_15.php',
			'config' => 'lib_config_15.php',
			'params' => array(
				392 => array(
					'name' => 'Код информационной системы',
					'varible_name' => 'informationsystemId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `informationsystems` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				393 => array(
					'name' => 'Информационная группа',
					'varible_name' => 'informationGroupId',
					'type' => 'input',
					'sorting' => 20,
				),
				394 => array(
					'name' => 'Адреса источников',
					'varible_name' => 'sourcesList',
					'type' => 'textarea',
					'sorting' => 30,
				),
			)
		),
		//Поиск
		3 => array(
			'name' => 'Поиск',
			'dirName' => 'Поиск',
			'code' => 'lib_3.php',
			'config' => 'lib_config_3.php',
			'params' => array(
				61 => array(
					'name' => 'Число строк результата на страницу',
					'varible_name' => 'itemsOnPage',
					'type' => 'input',
					'default_value' => 10,
					'sorting' => 10,
				),
				62 => array(
					'name' => 'Максимальная длина поискового запроса',
					'varible_name' => 'maxlen',
					'type' => 'input',
					'default_value' => 200,
					'sorting' => 20,
				),
				63 => array(
					'name' => 'Поиск только по текущему сайту',
					'varible_name' => 'searchOnCurrentSite',
					'type' => 'checkbox',
					'sorting' => 30,
				),
				64 => array(
					'name' => 'XSL шаблон результата поиска',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'default_value' => 'Поиск',
					'sorting' => 40,
				),
			)
		),
		//Реклама
		21 => array(
			'name' => 'Переход по ссылке баннера',
			'dirName' => 'Реклама',
			'code' => 'lib_21.php',
			'config' => 'lib_config_21.php',
		),
		22 => array(
			'name' => 'Переход по ссылке баннера (JavaScript)',
			'dirName' => 'Реклама',
			'code' => 'lib_22.php',
			'config' => 'lib_config_22.php',
		),
		//Карта сайта
		5 => array(
			'name' => 'Карта сайта',
			'dirName' => 'Карта сайта',
			'code' => 'lib_5.php',
			'config' => 'lib_config_5.php',
			'params' => array(
				5 => array(
					'name' => 'XSL шаблон',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'default_value' => 'КартаСайта',
					'sorting' => 10,
				),
				6 => array(
					'name' => 'Отображать группы информационных систем',
					'varible_name' => 'showInformationsystemGroups',
					'type' => 'checkbox',
					'sorting' => 20,
				),
				7 => array(
					'name' => 'Отображать элементы информационных систем',
					'varible_name' => 'showInformationsystemItems',
					'type' => 'checkbox',
					'sorting' => 30,
				),
				411 => array(
					'name' => 'Отображать группы магазина',
					'varible_name' => 'showShopGroups',
					'type' => 'checkbox',
					'sorting' => 40,
				),
				412 => array(
					'name' => 'Отображать товары магазина',
					'varible_name' => 'showShopItems',
					'type' => 'checkbox',
					'sorting' => 50,
				),
				409 => array(
					'name' => 'Родительский узел',
					'varible_name' => 'structureParentId',
					'type' => 'input',
					'default_value' => 0,
					'sorting' => 60,
				),
			)
		),
		//Интернет-магазин
		6 => array(
			'name' => 'Интернет-магазин',
			'dirName' => 'Интернет-магазин',
			'code' => 'lib_6.php',
			'config' => 'lib_config_6.php',
			'params' => array(
				41 => array(
					'name' => 'Идентификатор магазина',
					'varible_name' => 'shopId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `shops` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				42 => array(
					'name' => 'XSL каталога',
					'varible_name' => 'shopXsl',
					'type' => 'xsl',
					'sorting' => 20,
				),
				43 => array(
					'name' => 'XSL товара',
					'varible_name' => 'shopItemXsl',
					'type' => 'xsl',
					'sorting' => 30,
				),
				493 => array(
					'name' => 'XSL письма администратору о добавлении комментария',
					'varible_name' => 'addCommentAdminMailXsl',
					'type' => 'xsl',
					'default_value' => 'ПисьмоАдминистраторуОДобавленииКомментарияВФорматеHTML',
					'sorting' => 40,
				),
				494 => array(
					'name' => 'Формат письма администратору о добавлении комментария',
					'varible_name' => 'commentMailNoticeType',
					'type' => 'list',
					'values' => array(
						1 => 'HTML',
						0 => 'Текст',
					),
					'sorting' => 50,
				),
				49 => array(
					'name' => 'XSL письма администратору',
					'varible_name' => 'orderAdminNotificationXsl',
					'type' => 'xsl',
					'default_value' => 'ПисьмоАдминистратору',
					'sorting' => 60,
				),
				581 => array(
					'name' => 'XSL шаблон для уведомления пользователя о добавлении комментария',
					'varible_name' => 'addCommentNoticeXsl',
					'type' => 'xsl',
					'default_value' => 'УведомлениеДобавлениеКомментария',
					'sorting' => 70,
				),
				50 => array(
					'name' => 'XSL письма пользователю',
					'varible_name' => 'orderUserNotificationXsl',
					'type' => 'xsl',
					'default_value' => 'ПисьмоПользователю',
					'sorting' => 80,
				),
			)
		),
		7 => array(
			'name' => 'Интернет-магазин корзина',
			'dirName' => 'Интернет-магазин',
			'code' => 'lib_7.php',
			'config' => 'lib_config_7.php',
			'params' => array(
				44 => array(
					'name' => 'Идентификатор магазина',
					'varible_name' => 'shopId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `shops` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				54 => array(
					'name' => 'XSL магазина доставки',
					'varible_name' => 'deliveryXsl',
					'type' => 'xsl',
					'default_value' => 'МагазинДоставки',
					'sorting' => 15,
				),
				45 => array(
					'name' => 'XSL адреса доставки',
					'varible_name' => 'deliveryAddressXsl',
					'type' => 'xsl',
					'default_value' => 'МагазинАдресДоставки',
					'sorting' => 20,
				),
				47 => array(
					'name' => 'XSL платежной системы',
					'varible_name' => 'paymentSystemXsl',
					'type' => 'xsl',
					'default_value' => 'МагазинПлатежнаяСистема',
					'sorting' => 30,
				),
				48 => array(
					'name' => 'XSL оформления заказа',
					'varible_name' => 'orderXsl',
					'type' => 'xsl',
					'default_value' => 'ОформлениеЗаказа',
					'sorting' => 40,
				),
				51 => array(
					'name' => 'XSL корзины',
					'varible_name' => 'cartXsl',
					'type' => 'xsl',
					'default_value' => 'МагазинКорзина',
					'sorting' => 50,
				),
				410 => array(
					'name' => 'XSL краткой корзины',
					'varible_name' => 'littleCartXsl',
					'type' => 'xsl',
					'default_value' => 'МагазинКорзинаКраткая',
					'sorting' => 60,
				),
				582 => array(
					'name' => 'XSL личного кабинета',
					'varible_name' => 'userAuthorizationXsl',
					'type' => 'xsl',
					'default_value' => 'ЛичныйКабинетПользователя',
					'sorting' => 70,
				),
				583 => array(
					'name' => 'XSL регистрации пользователя',
					'varible_name' => 'userRegistrationXsl',
					'type' => 'xsl',
					'default_value' => 'РегистрацияПользователя',
					'sorting' => 80,
				),
			)
		),
		8 => array(
			'name' => 'Версия для печати',
			'dirName' => 'Интернет-магазин',
			'code' => 'lib_8.php',
			'config' => 'lib_config_8.php',
		),
		9 => array(
			'name' => 'Продавцы',
			'dirName' => 'Интернет-магазин',
			'code' => 'lib_9.php',
			'config' => 'lib_config_9.php',
			'params' => array(
				585 => array(
					'name' => 'Идентификатор магазина',
					'varible_name' => 'shopId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `shops` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				88 => array(
					'name' => 'XSL продавцов',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'default_value' => 'МагазинПродавец',
					'sorting' => 20,
				),
				584 => array(
					'name' => 'XSL списка продавцов',
					'varible_name' => 'listXsl',
					'type' => 'xsl',
					'default_value' => 'МагазинСписокПродавцов',
					'sorting' => 30,
				),
				586 => array(
					'name' => 'Число выводимых элементов на страницу',
					'varible_name' => 'itemsOnPage',
					'type' => 'input',
					'default_value' => '10',
					'sorting' => 40,
				),
			)
		),
		10 => array(
			'name' => 'Сравнение товаров',
			'dirName' => 'Интернет-магазин',
			'code' => 'lib_10.php',
			'config' => 'lib_config_10.php',
			'params' => array(
				86 => array(
					'name' => 'Идентификатор магазина',
					'varible_name' => 'shopId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `shops` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				87 => array(
					'name' => 'XSL сравнения товаров',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'sorting' => 20,
				),
			)
		),
		11 => array(
			'name' => 'Прайс',
			'dirName' => 'Интернет-магазин',
			'code' => 'lib_11.php',
			'config' => 'lib_config_11.php',
			'params' => array(
				89 => array(
					'name' => 'Идентификатор магазина',
					'varible_name' => 'shopId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `shops` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				90 => array(
					'name' => 'XSL шаблон',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'sorting' => 20,
				),
			)
		),
		12 => array(
			'name' => 'Экспорт в Яндекс.Маркет',
			'dirName' => 'Интернет-магазин',
			'code' => 'lib_12.php',
			'config' => 'lib_config_12.php',
			'params' => array(
				136 => array(
					'name' => 'Идентификатор магазина',
					'varible_name' => 'shopId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `shops` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
			)
		),
		36 => array(
			'name' => 'Обмен с 1С:Управление торговлей',
			'dirName' => 'Интернет-магазин',
			'code' => 'lib_36.php',
			'config' => 'lib_config_36.php',
			'params' => array(
				544 => array(
					'name' => 'Идентификатор магазина',
					'varible_name' => 'shopId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `shops` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
			)
		),
		43 => array(
			'name' => 'Экспорт в Яндекс.Недвижимость',
			'dirName' => 'Интернет-магазин',
			'code' => 'lib_43.php',
			'config' => 'lib_config_43.php',
			'params' => array(
				588 => array(
					'name' => 'Идентификатор магазина',
					'varible_name' => 'shopId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `shops` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
			)
		),
		41 => array(
			'name' => 'Производители',
			'dirName' => 'Интернет-магазин',
			'code' => 'lib_41.php',
			'config' => 'lib_config_41.php',
			'params' => array(
				572 => array(
					'name' => 'Идентификатор магазина',
					'varible_name' => 'shopId',
					'type' => 'sql',
					'sql' => 'SELECT * FROM `shops` WHERE `site_id` = \'{SITE_ID}\' AND `deleted` = 0 ORDER BY `name`;',
					'sql_caption_field' => 'name',
					'sql_value_field' => 'id',
					'sorting' => 10,
				),
				573 => array(
					'name' => 'Число выводимых элементов на страницу',
					'varible_name' => 'itemsOnPage',
					'type' => 'input',
					'default_value' => 10,
					'sorting' => 20,
				),
				570 => array(
					'name' => 'XSL вывода производителя',
					'varible_name' => 'xsl',
					'type' => 'xsl',
					'default_value' => 'МагазинПроизводитель',
					'sorting' => 30,
				),
				571 => array(
					'name' => 'XSL вывода списка производителей',
					'varible_name' => 'listXsl',
					'type' => 'xsl',
					'default_value' => 'МагазинСписокПроизводителей',
					'sorting' => 40,
				),
			)
		),
	),
	'en' => array(
	//
	),
);

//Массив макетов шаблона
$aTemplatei18n = array(
	'ru' => array(
		1 => array(
			'name' => 'Основной макет сайта [header, footer]',
			'dirName' => '',
			'parent_template_id' => 0,
		),
		13 => array(
			'name' => 'Макет col-3, col-9',
			'parent_template_id' => 1,
			'dirName' => '',
		),
		14 => array(
			'name' => 'Макет col-12',
			'parent_template_id' => 1,
			'dirName' => '',
		),
		3 => array(
			'name' => 'Макет для RSS-лент',
			'parent_template_id' => 0,
			'dirName' => '',
		),
		6 => array(
			'name' => 'Версия для печати',
			'parent_template_id' => 0,
			'dirName' => '',
		),
		7 => array(
			'name' => 'Для главной страницы',
			'parent_template_id' => 13,
			'dirName' => '',
		),
		9 => array(
			'name' => 'Внутренний шаблон со строкой навигации',
			'parent_template_id' => 13,
			'dirName' => '',
		),
		11 => array(
			'name' => 'Внутренний шаблон',
			'parent_template_id' => 13,
			'dirName' => '',
		),
	),
	'en' => array(
	//
	),
);

//Массив документов
$aDoci18n = array(
	'ru' => array(
		6 => array(
			'name' => 'Сайт отключен',
			'dirName' => 'Страницы ошибок',
			'versions' => array(
				18 => array(
					'datetime' => '2008-03-14 15:05:14',
					'current' => 0,
					'template_id' => 1,
				),
				19 => array(
					'datetime' => '2008-04-24 14:14:50',
					'current' => 1,
					'template_id' => 9,
				)
			),
		),
		5 => array(
			'name' => 'Ошибка 404',
			'dirName' => 'Страницы ошибок',
			'versions' => array(
				9 => array(
					'datetime' => '2008-03-14 15:05:14',
					'current' => 1,
					'template_id' => 9,
				)
			),
		),
		4 => array(
			'name' => 'Ошибка 403',
			'dirName' => 'Страницы ошибок',
			'versions' => array(
				10 => array(
					'datetime' => '2008-03-14 15:05:33',
					'current' => 1,
					'template_id' => 9,
				)
			),
		),
		2 => array(
			'name' => 'Главная страница',
			'dirName' => '',
			'versions' => array(
				16 => array(
					'datetime' => '2008-04-22 10:30:44',
					'current' => 1,
					'template_id' => 7,
				)
			),
		),
		7 => array(
			'name' => 'Краткие контактные данные',
			'dirName' => 'Контактные данные',
			'versions' => array(
				20 => array(
					'datetime' => '2009-11-22 17:03:04',
					'current' => 1,
					'template_id' => 1,
				)
			),
		),
		8 => array(
			'name' => 'Контактные данные',
			'dirName' => 'Контактные данные',
			'versions' => array(
				21 => array(
					'datetime' => '2009-11-22 17:08:58',
					'current' => 1,
					'template_id' => 9,
				)
			),
		),
	),
	'en' => array(
	//
	),
);

//Массив меню
$aMenui18n = array(
	'ru' => array(
		1 => 'Верхнее Меню',
		2 => 'Левое меню',
	),
	'en' => array(
	//
	),
);

//Массив списков
$aListi18n = array(
	'ru' => array(
		4 => array(
			'name' => 'Цвета',
			'dirName' => '',
			'list_items' => array(
				8 => array(
					'value' => 'Красный',
					'sorting' => 10,
					'description' => '',
					'active' => 1,
				),
				9 => array(
					'value' => 'Синий',
					'sorting' => 20,
					'description' => 'Описание синего',
					'active' => 1,
				),
				10 => array(
					'value' => 'Зеленый',
					'sorting' => 30,
					'description' => '',
					'active' => 1,
				),
				16 => array(
					'value' => 'Серебристый',
					'sorting' => 40,
					'description' => '',
					'active' => 1,
				),
				17 => array(
					'value' => 'Черный',
					'sorting' => 50,
					'description' => '',
					'active' => 1,
				),
			)
		),
	),
	'en' => array(
	//
	),
);

//Массив форм
$aFormi18n = array(
	'ru' => array(
		1 => array(
			'name' => 'Обратная связь',
			'email' => 'admin@localhost.ru',
			'button_name' => 'Submit',
			'button_value' => 'Отправить',
			'email_subject' => 'Форма заявки',
			'use_captcha' => 1,
			'form_fields' => array(
				1 => array(
					'caption' => 'ФИО',
					'dirName' => '',
					'type' => 'input',
					'name' => 'fio',
					'size' => 50,
					'default_value' => '',
					'sorting' => 10,
					'obligatory' => 1,
				),
				2 => array(
					'caption' => 'Адрес',
					'dirName' => '',
					'type' => 'input',
					'name' => 'address',
					'size' => 50,
					'default_value' => '',
					'sorting' => 20,
					'obligatory' => 0,
				),
				3 => array(
					'caption' => 'E-mail',
					'dirName' => '',
					'type' => 'input',
					'name' => 'email',
					'size' => 50,
					'default_value' => '',
					'sorting' => 30,
					'obligatory' => 1,
				),
				4 => array(
					'caption' => 'Комментарий',
					'dirName' => '',
					'type' => 'textarea',
					'name' => 'comment',
					'cols' => 50,
					'rows' => 10,
					'default_value' => '',
					'sorting' => 40,
					'obligatory' => 0,
				),
				77 => array(
					'caption' => 'Изображение',
					'dirName' => '',
					'type' => 'file',
					'name' => 'field_file1',
					'size' => 50,
					'default_value' => '',
					'sorting' => 50,
					'obligatory' => 0,
				),
				80 => array(
					'caption' => 'Список',
					'dirName' => '',
					'type' => 'list',
					'name' => 'list',
					'list_id' => 4,
					'default_value' => '',
					'sorting' => 60,
					'obligatory' => 0,
				),
			)
		),
	),
	'en' => array(
	//
	),
);

//Массив опросов
$aPollGroupi18n = array(
	'ru' => array(
		1 => array(
			'name' => 'Группа опросов',
			'structure_id' => 33,
			'polls' => array(
				1 => array(
					'name' => 'Вам нравится новый сайт?',
					'type' => 0,
					'show_results' => 1,
					'active' => 1,
					'start_date' => '2012-03-14',
					'end_date' => '2026-04-30',
					'poll_responses' => array(
						1 => array(
							'name' => 'Потрясающе',
							'grade' => 1,
							'sorting' => 10,
						),
						2 => array(
							'name' => 'Гениально',
							'grade' => 1,
							'sorting' => 20,
						),
						3 => array(
							'name' => 'Прелестно',
							'grade' => 1,
							'sorting' => 30,
						),
					)
				),
				2 => array(
					'name' => 'Что Вы думаете о HostCMS 6.x?',
					'type' => 1,
					'show_results' => 1,
					'active' => 1,
					'start_date' => '2012-03-14',
					'end_date' => '2026-04-30',
					'poll_responses' => array(
						6 => array(
							'name' => 'Это шедевр',
							'grade' => 1,
							'sorting' => 10,
						),
						7 => array(
							'name' => 'Лучше еще не видел',
							'grade' => 1,
							'sorting' => 20,
						),
						8 => array(
							'name' => 'Я уже ничего не думаю',
							'grade' => 1,
							'sorting' => 30,
						),
					)
				)
			)
		)
	),
	'en' => array(
	//
	),
);

//Массив информационных систем
$aInformationsystemi18n = array(
	'ru' => array(
		1 => array(
			'name' => 'Новости',
			'dirName' => '',
			'structure_id' => 7,
			'items_on_page' => 5,
			'items_sorting_field' => 0,
			'items_sorting_direction' => 1,
			'image_large_max_width' => 500,
			'image_large_max_height' => 500,
			'image_small_max_width' => 80,
			'image_small_max_height' => 80,
			'siteuser_group_id' => 0,
			'typograph_default_items' => 1,
			'typograph_default_groups' => 1,
			'informationsystem_groups' => array(
				2 => array(
					'name' => 'Фильмы',
					'parent_id' => 0,
					'description' => '',
					'active' => 1,
					'indexing' => 1,
				)
			),
			'informationsystem_items' => array(
				1 => array(
					'name' => 'Apple снизила рублевые цены на MacBook и iPad',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 10,
					'description' => 'Apple снизила в России примерно на 10 процентов стоимость на ряд своих продуктов, включая планшеты iPad и ноутбуки MacBook. Об этом свидетельствуют данные с официального сайта компании.',
					'text' => '<p>Apple снизила в России примерно на 10 процентов стоимость на ряд своих продуктов, включая планшеты iPad и ноутбуки MacBook. Об этом свидетельствуют данные с официального сайта компании.</p>
					<p>Так, iPad Air 2 с Wi-Fi и памятью 16 гигабайт можно купить за 33 490 рублей (вместо 37 490 рублей), а iPad Mini 3 с Wi-Fi и 16 гигабайтами — за 26 990 рублей (вместо 29 990 рублей).</p>
					<p>Стоимость MacBook Air с экраном 11 дюймов и памятью 128 гигабайт снизилась до 62 990 рублей (вместо 69 990 рублей), тогда как MacBook Air с 13 дюймами и 128 гигабайтами — до 75 990 рублей (вместо 77 990 рублей). Продвинутая версия ноутбука MacBook Pro c 13 дюймами и дисплеем Retina теперь обойдется в 89 990 рублей (вместо 99 990 рублей).</p>',
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Apple,macbook,ipad',
					'property_values' => array(
						12 => array(
							'property_id' => 11,
							'value' => 123,
						),
					),
				),
				2 => array(
					'name' => 'IPhone может сравниться по качеству съемки с зеркальными камерами',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 20,
					'description' => 'Apple приобрела компанию LinX Imaging, специализирующуюся на камерах для смартфонов.',
					'text' => '<p>Apple приобрела компанию LinX Imaging, специализирующуюся на камерах для смартфонов.</p>
					<p>По заявлениям ее представителей, разработанная в LinX технология позволяет добиться качества изображения на камерах мобильных устройств, которое сравнимо с устройствами DSLR. LinX была основана в 2011 году в Израиле и с тех пор занимал выпуском высококачественных камер для смартфонов и других мобильных устройств. Камеры Linx уникальны тем, что используют больше одного сенсора, чтобы улучшить показатели при съемке с небольшим количеством света, повысить производительность HDR, улучшить фокусировку и уменьшить задержку срабатывания затвора.</p>',
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Apple,Linx',
					'property_values' => array(
						23 => array(
							'property_id' => 11,
							'value' => '',
						),
					),
				),
				3 => array(
					'name' => 'YouTube исчез из старых устройств Apple',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 30,
					'description' => 'С 20 апреля YouTube начнёт постепенно отказываться от поддержки API v2 в пользу более современного API v3, из-за чего сервис прекратит работу на нескольких поколениях iOS-устройств, Smart TV и Blu-ray-плееров. Об этом сообщается в официальном блоге видеохостинга.',
					'text' => '<p>Главной «жертвой» нового стандарта станет приложение YouTube, которое было встроено в версии iOS с первой по шестую. Пользователи iOS 6 смогут скачать официальную программу видеохостинга из App Store, однако те, кто решил не обновляться с iOS 5 и более ранних версий системы, возможно, полностью потеряют доступ к сервису.</p>
					<p>Перестанет работать YouTube и на втором поколении Apple TV, от поддержки программного обеспечения Apple уже отказалась, а также на большинстве телевизоров, Blu-ray-плееров и других видов техники, выпущенных до 2013 года. Более новые устройства и домашние игровые консоли последних двух поколений будут по-прежнему воспроизводить ролики сервиса.</p>',
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Apple,iOS',
					'property_values' => array(
						34 => array(
							'property_id' => 11,
							'value' => 14,
						),
					),
				),
				4 => array(
					'name' => 'Nokia намерена вернуться на рынок в 2016 году',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 30,
					'description' => 'Компания Nokia, чье мобильное подразделение было продано Microsoft в 2014 году, планирует вернуться на рынок телефонов уже в следующем году. Об этом сообщает портал Re/code со ссылкой на собственные источники.',
					'text' => '<p>Компания Nokia, чье мобильное подразделение было продано Microsoft в 2014 году, планирует вернуться на рынок телефонов уже в следующем году. Об этом сообщает портал Re/code со ссылкой на собственные источники.<p>
					<p>По их данным, инициатором подобных планов стало одно из трех оставшихся после сделки с софтверным гигантом подразделений финской компании — Nokia Technologies. Оно занимается лицензированием 10 тысяч патентов, имеющихся у корпорации.</p>
					<p>Кроме того, подразделение также отвечает за разработку продуктов и лицензирование их у других компаний. Пока что Nokia Technologies выпустила только два продукта: Android-программу Zlauncher и планшет N1, который стал первым устройством, анонсированным компанией после продажи Microsoft мобильного подразделения. Планшет N1 работает под управлением операционной системы Android и имеет фирменную графическую оболочку, однако был выпущен сторонним производителем, который продает его в Китае под брендом Nokia. Как полагает Re/code, в случае с будущими телефонами произойдет нечто похожее.</p>',
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Nokia,Microsoft',
					'property_values' => array(
						45 => array(
							'property_id' => 11,
							'value' => 10,
						),
					),
				),
			),
			'properties' => array(
				11 => array(
					'name' => 'Строка',
					'dirName' => '',
					'type' => 'string',
					'description' => '',
					'default_value' => '',
					'tag_name' => 'string',
					'sorting' => 10,
					'multiple' => 1,
				),
			)
		),
		3 => array(
			'name' => 'Гостевая книга',
			'dirName' => '',
			'structure_id' => 13,
			'items_on_page' => 5,
			'items_sorting_field' => 0,
			'items_sorting_direction' => 1,
			'image_large_max_width' => 500,
			'image_large_max_height' => 500,
			'image_small_max_width' => 80,
			'image_small_max_height' => 80,
			'siteuser_group_id' => 0,
			'typograph_default_items' => 1,
			'typograph_default_groups' => 1,
			'informationsystem_items' => array(
				17 => array(
					'name' => 'Первая запись в гостевой книге',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 10,
					'description' => 'Спасибо за отличную систему управления сайтом. HostCMS - вы лучшие!',
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'HostCMS',
				),
			),
		),
		4 => array(
			'name' => 'Фотогалерея',
			'dirName' => '',
			'structure_id' => 15,
			'items_on_page' => 10,
			'items_sorting_field' => 0,
			'items_sorting_direction' => 1,
			'image_large_max_width' => 1000,
			'image_large_max_height' => 1000,
			'image_small_max_width' => 150,
			'image_small_max_height' => 150,
			'siteuser_group_id' => 0,
			'typograph_default_items' => 1,
			'typograph_default_groups' => 1,
			'informationsystem_items' => array(
				153 => array(
					'name' => 'Рыжий кот',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 10,
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Кот',
				),
				154 => array(
					'name' => 'Спящий кот',
					'informationsystem_group_id' => 0,
					'shortcut_id' => '0',
					'active' => 1,
					'indexing' => 1,
					'sorting' => 20,
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Кот',
				),
				155 => array(
					'name' => 'Вишневый цвет',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 30,
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Вишня,Цветы',
				),
				156 => array(
					'name' => 'Веселый пёс',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 40,
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Пёс,Счастье',
				),
				157 => array(
					'name' => 'Хиросима',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 50,
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Хиросима',
				),
				158 => array(
					'name' => 'Поля',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 50,
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Поля',
				),
				159 => array(
					'name' => 'Собачий нос',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 60,
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Пёс,Нос',
				),
				160 => array(
					'name' => 'Ландыши',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 70,
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Ландыши,Цветы',
				),
			),
		),
		5 => array(
			'name' => 'Статьи',
			'dirName' => '',
			'structure_id' => 16,
			'items_on_page' => 5,
			'items_sorting_field' => 0,
			'items_sorting_direction' => 1,
			'image_large_max_width' => 300,
			'image_large_max_height' => 300,
			'image_small_max_width' => 70,
			'image_small_max_height' => 70,
			'siteuser_group_id' => 0,
			'typograph_default_items' => 1,
			'typograph_default_groups' => 1,
			'informationsystem_items' => array(
				9 => array(
					'name' => 'BlackBerry приобретает компанию WatchDox',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 10,
					'description' => 'По сообщению источника, компания BlackBerry намерена приобрести WatchDox. Данный «стартап» занимается разработкой программного обеспечения, которое обеспечивает защиту данных, хранящихся в облачных хранилищах, а также позволяет управлять доступом к ним.',
					'text' => '<p>Предполагается, что стоимость сделки составит 70 млн долларов, а по некоторым данным даже 150 млн долларов. Заинтересованность канадской компании тут вполне ясна, так как одна из основных продвигаемых идей BlackBerry — безопасность данных. К примеру, в конце прошлого года компания объявила о стратегическом сотрудничестве с Samsung, в рамках которого технологии BlackBerry будут интегрированы в смартфоны корейского гиганта.</p>',
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'BlackBerry,WatchDox',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => 'Пух!',
							'text' => '<p>Пух, что ты думаешь по этому поводу?</p>',
							'author' => 'Тигра',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 4,
							'siteuser_id' => 3,
						),
					),
				),
				10 => array(
					'name' => 'Microsoft будет форсировать открытие исходников собственного ПО',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 20,
					'description' => 'Подразделение Open Technologies, отвечающее за взаимодействие с разработчиками открытого ПО, прекратило свое существование в нынешнем виде. В Microsoft подчеркнули, что структура выполнила свою миссию.',
					'text' => '<p>Теперь его сотрудники будут продвигать открытое ПО в других подразделениях корпорации, что позволит ускорить работу с профильными сообществами, и раскрытие кода собственных продуктов. Microsoft, однако, не планирует увольнять сотрудников Open Technologies, и не планирует сокращать объемы финансирования поддержки открытого ПО. Закрытие подразделения обусловлено тем, что Microsoft больше не нуждается в нем как в отдельной структуре.</p>
					<p>В обязанности сотрудников ляжет создание отдела Open Technology Programs Office, который должен будет «масштабировать полученные в Open Tech знания и опыт при работе с открытым исходным кодом и открытыми стандартами на всю корпорацию».</p>
					<p>Посредством нового подразделения Microsoft намерена оснастить команды инженеров инструментами и сервисами, необходимыми для прямой работы с сообществами сторонних разработчиков открытого ПО, и создания проектов с открытым ПО в стенах корпорации, что также упростит вовлечение независимых контрибьюторов в будущие проекты.</p>',
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Microsoft',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p></p>',
							'author' => 'Василий',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 4,
						),
					),
				),
				47 => array(
					'name' => 'Huawei готовится запустить облачный сервис',
					'informationsystem_group_id' => '0',
					'shortcut_id' => '0',
					'active' => 1,
					'indexing' => 1,
					'sorting' => 30,
					'description' => 'Китайский производитель телекоммуникационного оборудования Huawei Technologies Co объявил во вторник, что готовится к запуску собственного облачного сервиса для китайских корпоративных клиентов в июле.',
					'text' => '<p>Китайский производитель телекоммуникационного оборудования Huawei Technologies Co объявил во вторник, что готовится к запуску собственного облачного сервиса для китайских корпоративных клиентов в июле.<p>
					<p>Во время выступления на Глобальном аналитическом саммите компании Huawei в Шэньчжэне, провинция Гуандун, во вторник генеральный директор Сюй Чжицзюнь не назвал китайского оператора связи, с которым он будет сотрудничать для предоставления данной услуги.</p>
					<p>Сюй добавил, что компания не будет конкурировать с другими китайскими провайдерами публичных облачных сервисов.</p>',
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => 'Huawei,Cloud,Облако',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Посмотрим. Возможно получится и достойный конкурент iCloud и OneDrive</p>',
							'author' => 'Артем',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
				),
			),
		),
		6 => array(
			'name' => 'Услуги',
			'dirName' => '',
			'structure_id' => 2,
			'items_on_page' => 5,
			'items_sorting_field' => 0,
			'items_sorting_direction' => 1,
			'image_large_max_width' => 300,
			'image_large_max_height' => 300,
			'image_small_max_width' => 70,
			'image_small_max_height' => 70,
			'siteuser_group_id' => 0,
			'typograph_default_items' => 1,
			'typograph_default_groups' => 1,
			'informationsystem_items' => array(
				133 => array(
					'name' => 'Стратегическое планирование',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 10,
					'description' => 'Развивая эту тему, игровое начало готично образует фарс, так Г.Корф формулирует собственную антитезу. Комплекс агрессивности монотонно выстраивает маньеризм, таким образом, второй комплекс движущих сил получил разработку в трудах А.Берталанфи и Ш.Бюлера.',
					'text' => 'Развивая эту тему, игровое начало готично образует фарс, так Г.Корф формулирует собственную антитезу. Комплекс агрессивности монотонно выстраивает маньеризм, таким образом, второй комплекс движущих сил получил разработку в трудах А.Берталанфи и Ш.Бюлера. Развивая эту тему, игровое начало готично образует фарс, так Г.Корф формулирует собственную антитезу. Комплекс агрессивности монотонно выстраивает маньеризм, таким образом, второй комплекс движущих сил получил разработку в трудах А.Берталанфи и Ш.Бюлера. Развивая эту тему, игровое начало готично образует фарс, так Г.Корф формулирует собственную антитезу. Комплекс агрессивности монотонно выстраивает маньеризм, таким образом, второй комплекс движущих сил получил разработку в трудах А.Берталанфи и Ш.Бюлера.',
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => '',
					'property_values' => array(
						247 => array(
							'property_id' => 63,
							'value' => 'fa-heart-o',
						),
					),
				),
				134 => array(
					'name' => 'Стимулирование сбыта',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 20,
					'description' => 'Медиамикс основан на опыте повседневного применения. Концепция новой стратегии откровенна. Потребительский рынок, следовательно, притягивает социометрический выставочный стенд, не считаясь с затратами.',
					'text' => 'Медиамикс основан на опыте повседневного применения. Концепция новой стратегии откровенна. Потребительский рынок, следовательно, притягивает социометрический выставочный стенд, не считаясь с затратами. Стимулирование сбыта, вопреки мнению П.Друкера, тормозит BTL, признавая определенные рыночные тенденции. Воздействие на потребителя консолидирует потребительский выставочный стенд, осознав маркетинг как часть производства. Стиль менеджмента переворачивает рекламный бриф, осознав маркетинг как часть производства. Можно предположить, что маркетингово-ориентированное издание методически искажает имидж предприятия, учитывая результат предыдущих медиа-кампаний. Бизнес-модель однородно развивает из ряда вон выходящий департамент маркетинга и продаж, невзирая на действия конкурентов. Согласно предыдущему, пак-шот вполне вероятен. Метод изучения рынка, согласно Ф.Котлеру, неоднозначен. Рейт-карта оправдывает целевой сегмент рынка, отвоевывая рыночный сегмент. Бизнес-стратегия, отбрасывая подробности, откровенна. Контекстная реклама наиболее полно синхронизирует культурный ребрендинг, опираясь на опыт западных коллег. Взаимодействие корпорации и клиента отражает SWOT-анализ, опираясь на опыт западных коллег. Стимулирование коммьюнити как всегда непредсказуемо.',
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => '',
					'property_values' => array(
						248 => array(
							'property_id' => 63,
							'value' => 'fa-circle-thin',
						),
					),
				),
				149 => array(
					'name' => 'Эмпирический имидж',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 30,
					'description' => 'Идеология выстраивания бренда изоморфна времени. Итак, ясно, что жизненный цикл продукции нейтрализует тактический стратегический маркетинг, не считаясь с затратами. ',
					'text' => 'Идеология выстраивания бренда изоморфна времени. Итак, ясно, что жизненный цикл продукции нейтрализует тактический стратегический маркетинг, не считаясь с затратами. Управление брендом ускоряет системный анализ, используя опыт предыдущих кампаний. Экспансия, как принято считать, экономит культурный анализ зарубежного опыта, отвоевывая рыночный сегмент. Интересно отметить, что организация слубы маркетинга интегрирована. Косвенная реклама транслирует потребительский портрет потребителя, расширяя долю рынка. Маркетинговая активность уравновешивает обществвенный фактор коммуникации, оптимизируя бюджеты. Инвестиционный продукт, конечно, разнородно тормозит потребительский традиционный канал, признавая определенные рыночные тенденции. Один из признанных классиков маркетинга Ф.Котлер определяет это так: анализ зарубежного опыта основан на анализе телесмотрения. Имидж, конечно, специфицирует нестандартный подход, признавая определенные рыночные тенденции. Таргетирование амбивалентно. Концепция новой стратегии, следовательно, стремительно детерминирует комплексный маркетинг, опираясь на опыт западных коллег. Эволюция мерчандайзинга стабилизирует социометрический целевой трафик, не считаясь с затратами. Выставочный стенд переворачивает коллективный ребрендинг, используя опыт предыдущих кампаний.',
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => '',
					'property_values' => array(
						249 => array(
							'property_id' => 63,
							'value' => 'fa-bell-o',
						),
					),
				),
				150 => array(
					'name' => 'Коллективный анализ цен',
					'informationsystem_group_id' => 0,
					'shortcut_id' => 0,
					'active' => 1,
					'indexing' => 1,
					'sorting' => 40,
					'description' => 'Общество потребления нейтрализует анализ зарубежного опыта, опираясь на опыт западных коллег. Информационная связь с потребителем все еще интересна для многих. Рекламоноситель масштабирует инструмент маркетинга, полагаясь на инсайдерскую информацию.',
					'text' => 'Общество потребления нейтрализует анализ зарубежного опыта, опираясь на опыт западных коллег. Информационная связь с потребителем все еще интересна для многих. Рекламоноситель масштабирует инструмент маркетинга, полагаясь на инсайдерскую информацию. Искусство медиапланирования достижимо в разумные сроки. Адекватная ментальность переворачивает межличностный медиамикс, повышая конкуренцию. Можно предположить, что медийная связь ускоряет эмпирический системный анализ, осознав маркетинг как часть производства. Опрос, следовательно, подсознательно обуславливает BTL, не считаясь с затратами. До недавнего времени считалось, что креатив основан на тщательном анализе. Фокусировка естественно усиливает бюджет на размещение, учитывая современные тенденции. Сегмент рынка, анализируя результаты рекламной кампании, неестественно транслирует департамент маркетинга и продаж, опираясь на опыт западных коллег. Повышение жизненных стандартов, анализируя результаты рекламной кампании, развивает социометрический нестандартный подход, опираясь на опыт западных коллег. Оценка эффективности кампании парадоксально переворачивает SWOT-анализ, осознав маркетинг как часть производства. Экспертиза выполненного проекта, пренебрегая деталями, выражена наиболее полно. Селекция бренда упорядочивает эмпирический отраслевой стандарт, не считаясь с затратами. Емкость рынка вырождена. Стимулирование коммьюнити не критично.',
					'seo_title' => '',
					'seo_description' => '',
					'seo_keywords' => '',
					'tags' => '',
					'property_values' => array(
						16 => array(
							'property_id' => 63,
							'value' => 'fa-bus',
						),
					),
				),
			),
			'properties' => array(
				63 => array(
					'name' => 'Иконка',
					'dirName' => '',
					'type' => 'string',
					'description' => '',
					'default_value' => '',
					'tag_name' => 'badge',
					'sorting' => 10,
					'multiple' => 0,
				),
			)
		),
	),
	'en' => array(
	//
	),
);

$aShopi18n = array(
	'ru' => array(
		1 => array(
			'name' => 'Демонстрационный магазин',
			'dirName' => '',
			'structure_id' => 42,
			'shop_company_id' => 1,
			'shop_currency_id' => 1,
			'shop_country_id' => 175,
			'shop_measure_id' => 0,
			'email' => 'admin@localhost.ru',
			'items_on_page' => 6,
			'items_sorting_field' => 0,
			'items_sorting_direction' => 1,
			'image_large_max_width' => 800,
			'image_large_max_height' => 800,
			'image_small_max_width' => 270,
			'image_small_max_height' => 270,
			'siteuser_group_id' => 0,
			'typograph_default_items' => 0,
			'typograph_default_groups' => 0,
			'send_order_email_admin' => 1,
			'send_order_email_user' => 1,
			'comment_active' => 1,
			'apply_tags_automatically' => 1,
			'write_off_paid_items' => 1,
			'change_filename' => 1,
			'attach_digital_items' => 1,
			'use_captcha' => 1,
			'shop_groups' => array(
				592 => array(
					'name' => 'Посудомоечные машины',
					'parent_id' => 0,
					'sorting' => 10,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				591 => array(
					'name' => 'Плиты',
					'parent_id' => 0,
					'sorting' => 20,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				590 => array(
					'name' => 'Холодильники',
					'parent_id' => 0,
					'sorting' => 30,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				588 => array(
					'name' => 'Цифровые фотоаппараты',
					'parent_id' => 0,
					'sorting' => 40,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				586 => array(
					'name' => 'Видеокамеры',
					'parent_id' => 0,
					'sorting' => 50,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				589 => array(
					'name' => 'Цифровые плееры',
					'parent_id' => 0,
					'sorting' => 60,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
			),
			'shop_items' => array(
				117 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 34,
					'shop_measure_id' => 0,
					'shop_group_id' => 588,
					'shop_discount_id' => 2,
					'type' => 'item',
					'name' => 'Olympus OM-D E-M10 Kit',
					'marking' => '',
					'description' => '',
					'text' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?',
					'price' => '36990.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Olympus,Kit',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => 'о фотоаппарате',
							'text' => '<p>Не плохой фотоаппарат начального уровня, считаю цена не совсем оправдывает качество получаемых с него снимков. Много шумов в кадре, но при грамотных настройках и прямых руках, большинство дефектов можно убрать. Новичкам порекомендую все же nikon 5500 или более бюджетный 3500</p>',
							'author' => 'Михаил Иванченко',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 3,
						),
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Достоинства расписывать не буду, все есть в обзорах, отзыв пишу только чтобы предупредить о том, что для съемки видео она не очень пригодна - все портит нерабочий автофокус - во время записи он фокусируется на чем угодно, кроме того что расположено в кадре (при фотосъемке же отлично работает, почему тут то нет?!), что вместе с его медлительностью полностью уничтожает любое видео (все особенно печально в помещениях). Звук тоже так себе - на низкой чувствительности голоса не слышно, а на той что слышно будут шумы и звуки внутренностей (шумоподавитель не спасает)</p>',
							'author' => 'Алексей',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
					'property_values' => array(
						44 => array(
							'property_id' => 67,
							'value' => 'Строка',
						),
						131 => array(
							'property_id' => 68,
							'value' => 8,
						),
						//Img
						120 => array(
							'property_id' => 66,
						),
						249 => array(
							'property_id' => 66,
						),
					),
				),
				112 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 45,
					'shop_measure_id' => 0,
					'shop_group_id' => 588,
					'type' => 'item',
					'name' => 'Pentax K-3 Body',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '69780.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Pentax,Kit',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Что тут сказать… реально топ среди вообще всего кропа. Кое в чём камера может тягаться и с полнокадровыми моделями конкурентов, в чём-то вплотную подбирается к ним. Я просто не знаю, что в ней можно ЕЩЁ улучшить (в смысле окромя увлечения матрицы до 24*36 мм), ну разве что автофокус – сделать его реально предиктивным. Инженеры Рико-Пентакс сделали действительно новую камеру, но в духе линейки K-7/K-5/K-5II – управление просто идеальное на мой взгляд; затвор просто шедеврален – 8.3 к/с и ТИХИЙ; видоискатель отменный – светлый, большой, но матирование позволяет использовать мануальные стёкла; эргономика такая, что камера (как и K-5II) вростает в мою руку – всё там где надо; качество изготовления корпуса – традиционно отличное ВООБЩЕ без претензий; экспозамер и авто баланс белого просто чумовые – держат картинку как вкопанную даже в условиях выставки с абсолютно разными источниками света; 2 флешки – отлично, наконец-то сделали; матрица хороша, но я привык к 16 мегапиксельной, что в K-5II. И всё это в реально компактном корпусе с пылевлагозащитой! Подскажите мне другую современную систему (которая не начинается на «Лей» и кончается на «ка»), в которой есть зверски фаршированная крепкая камера и объектив 50/1.2, которые влазят в карман зимней куртки? А я ходил и снимал в мокрый снег вечером по родному городу Глазову, и поверьте, это было как эстетическое удовольствие, так и «шедевров» вышло несколько штук. Пентакс – это ОЧЕНЬ приятная в обращении техника, пользуешься ей и понимаешь, что её делали ИНЖЕНЕРЫ, а не маркетологи.
							Могу порекомендовать камеру продвинутым и не очень любителям, а также профи, кому хочется качества и надоело таскать килограммы стекла и железа – тем, кому нужно нечто особенное и интересное, но при этом очень качественное. Ну вот не всем же хочется брать камеру «как у всех» одного из 2-3 самых известных фотобрендов, они большие и оптика под них тоже большая и тяжёлая. А оптика Пентакс – она как правило компактная, но при этом очень качественная.</p>',
							'author' => 'Игорь',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
					'property_values' => array(
						//Img
						122 => array(
							'property_id' => 66,
						),
						250 => array(
							'property_id' => 66,
						),
					),
				),
				42 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 7,
					'shop_measure_id' => 0,
					'shop_group_id' => 588,
					'type' => 'item',
					'name' => 'Sony Alpha SLT-A58 Kit',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '28200.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Sony,Kit',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Покупкой полностью удоволетворен! Признаюсь честно это мой первый зеркальный фотоаппрат и еще не разу не пожалел о своем выборе, по данной цене равным этому апарату я просто не встречал. Отлично фокусируется, макросьемка породовала на ура, много функциональный. В двух словах, покупкой доволен, покупайте не пожалеете.</p>',
							'author' => 'Андрей',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
					'property_values' => array(
						//Img
						123 => array(
							'property_id' => 66,
						),
						160 => array(
							'property_id' => 66,
						),
					),
				),
				165 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 8,
					'shop_measure_id' => 0,
					'shop_group_id' => 586,
					'type' => 'item',
					'name' => 'JVC Everio GZ-R315',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '18990.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'JVC',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Хорошая камера за свои деньги. Получил её на день рождение, теперь хожу на любое мероприятие и снимаю. Качество видео меня устраивает, компактная, легкая, удобно носить с собой. Рекомендую к покупке</p>',
							'author' => 'Евгений Сидельников',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
					'property_values' => array(
						//Img
						102 => array(
							'property_id' => 66,
						),
						251 => array(
							'property_id' => 66,
						),
					),
				),
				29 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 33,
					'shop_measure_id' => 0,
					'shop_group_id' => 586,
					'type' => 'item',
					'name' => 'Kodak Pixpro SP1',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '15990.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Kodak,Pixpro',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Достоинства: Удобно, не дорого, не нужен аквабокс, понятное и информативное меню, со смартом пашет на ура! Функцией фото в экшн уже не удивить, но фотки получаются на свету великолепно и есть возможность "поднастроить" разрешение и углы обзора как фото так и видео! Недостатки: Нет защиты объектива-бленда предохраняет конечно, но когда гаджет на руле вела соринки и пылинки да же в городе садятся очень быстро, а камера большую часть пути вырублена, включаю только во время выкрутасов - пришлось "колхозить". Аккумулятор часа на два. Креплений в комплекте мало - для меня критично что нет крепления на себя, хотя бы для крепления на голову могли бы оснастить. Флэха нужна 10-го класса-чтоб все было классно.</p>',
							'author' => 'Дмитрий',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 4,
						),
					),
					'property_values' => array(
						//Img
						104 => array(
							'property_id' => 66,
						),
					),
				),
				28 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 9,
					'shop_measure_id' => 0,
					'shop_group_id' => 586,
					'type' => 'item',
					'name' => 'Panasonic HC-V130',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '8650.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Panasonic',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Самое то, если нужна хорошая камера за хорошие деньги!! Мы с женой ее всюду с собой берем, когда куда-то едем и в итоге видео получается что надо! Потом вместе садимся и пересматриваем, вспоминаем Греции и Египты))</p>',
							'author' => 'Геннадий',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
					'property_values' => array(
						//Img
						105 => array(
							'property_id' => 66,
						),
					),
				),
				48 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 44,
					'shop_measure_id' => 0,
					'shop_group_id' => 589,
					'type' => 'item',
					'name' => 'Apple iPod touch 5 32Gb',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '19620.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Apple,iPod',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>+Очень легкий и тонкий корпус, в кармане практически не ощущается
							+Шикарная матрица для четырех дюймов
							+*Удовлетворительная ударочность, если ронять на грани (Выдержал уже пять падений на разные типы поверхностей - ни малейшей трещинки на экране)
							+Отличное качество звука, особенно в симбиозе с дорогими наушниками
							+Стабильная работа (За два года использования серьёзных сбоев в работе не замечено)
							+Много приложений
							+Надежность
							+Своевременно прилетают обновления
							+Неплохая камера
							+Шустрая зарядка
							+Корпус из приятного на ощуп алюминия
							+Удачный форм фактор для плеера</p>',
							'author' => 'Влад',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
					'property_values' => array(
						//Img
						252 => array(
							'property_id' => 66,
						),
						125 => array(
							'property_id' => 66,
						),
					),
				),
				47 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 16,
					'shop_measure_id' => 0,
					'shop_group_id' => 589,
					'type' => 'item',
					'name' => 'Explay Art 8Gb',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '2039.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Explay',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Эксплуатировался всего два месяца и то вялотекущие. Но тем не менее, гнездо для наушников начало барахлить практически сразу после покупки, а чуть позже плеер и вовсе стало невозможно слушать. Некоторые треки проигрываются некорректно - с бульканьем и кваканьем и на них же управление плеером зависает. Кроме того, плееры Тексет продаются с точно таким же дизайном, но с другими рисунками. О, это очень серьезная политика компании! Покупался за 1400, сейчас продается за 2000. Всем советую - обязательно купите! Что нибудь другое.</p>',
							'author' => 'Витя',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
					'property_values' => array(
						//Img
						130 => array(
							'property_id' => 66,
						),
						253 => array(
							'property_id' => 66,
						),
					),
				),
				46 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 7,
					'shop_measure_id' => 0,
					'shop_group_id' => 589,
					'shop_discount_id' => 2,
					'type' => 'item',
					'name' => 'Sony NWZ-A17',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '12490.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Sony',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>12 000 за плеер, по мне так это дорого, хотя надо послушать как звучит, учитывая что он вокмановский. Если кто приобретал подобный, дайте пожалуйста знать как звук и стоит ли он таких денег.</p>',
							'author' => 'Илья Игнатюк',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
					'property_values' => array(
						//Img
						129 => array(
							'property_id' => 66,
						),
						254 => array(
							'property_id' => 66,
						),
					),
				),
				164 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 23,
					'shop_measure_id' => 0,
					'shop_group_id' => 590,
					'type' => 'item',
					'name' => 'ATLANT М 7184-003',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '17801.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'ATLANT',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Читал в комментариях, что шумные - неправда, надо к нему ухо приложить, чтобы услышать, что он вообще работает. Компрессор СКН-130, более старой разработки, брал специально, пишут, что более надежный. Качеством исполнения белорусы порадовали, придраться не к чему. Дверь перевесил сам за 10 минут, только нужен шестигранник на 5. После включения вышел на -24 градуса через полтора часа. Дальнейшая эксплуатация покажет его надежность, но гарантию 3 года дают. По соотношению цена/качество лучше не нашел.</p>
							<p>UPD: На сегодняшний день уже 10-й месяц эксплуатации, жаркое лето позади. Забит уже под завязку ;) Ни разу пока не размораживали, пока не нужно, думаю, ровно год будет - разморожу, льда пока немного. Нареканий нет, громкость работы за это время не изменилась. Тьфу 3 раза, пока придраться не к чему.</p>',
							'author' => 'Артем',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
					'property_values' => array(
						//Img
						138 => array(
							'property_id' => 66,
						),
					),
				),
				73 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 13,
					'shop_measure_id' => 0,
					'shop_group_id' => 590,
					'shop_discount_id' => 2,
					'type' => 'item',
					'name' => 'LG GA-B379 SEQA',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '28317.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'LG',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Холодильник фирмы LG выбрали потому что до этого приобрели стиральную машину и так получилось телевизор этой же марки, ранее не сталкивался с ней, но теперь я фанат. Холодильник не шумит, морозильная камера морозит как следует, за пол года эксплуатации дефектов и недостатков не выяснилось, поэтому покупкой доволен и вам рекомендую.</p>',
							'author' => 'Станислав Чернес',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
					'property_values' => array(
						//Img
						135 => array(
							'property_id' => 66,
						),
					),
				),
				72 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 5,
					'shop_measure_id' => 0,
					'shop_group_id' => 590,
					'type' => 'item',
					'name' => 'Samsung RB-37 J5441SA',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '56999.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Samsung',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => 'Холодильник самсунг',
							'text' => '<p>Отличный холодильник, приобретали такой пол года назад, много полезного места, высокая энерго эффективность и очень приятный дизайн, теперь данный холодильник служит украшением на нашей кухне и местом встречи по ночам.</p>',
							'author' => 'Артем',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
					'property_values' => array(
						//Img
						137 => array(
							'property_id' => 66,
						),
					),
				),
				94 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 18,
					'shop_measure_id' => 0,
					'shop_group_id' => 591,
					'type' => 'item',
					'name' => 'Bosch HGG94W325R',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '10598.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Bosch',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => 'Отличная плита',
							'text' => '<p>Хорошая, крепкая плита. Без люфтов и скрипов. Однако сдуру можно погнуть даже чугунные решетки- не надо по ним стучать или вставать на них. Газконтроль работает, поджиг тоже- все четко. Если ручки начали заедать- они просто грязные.</p>
							<p>Надо привыкнуть к телескопическим направляющим - иногда они "залипают" надо дергать, а иногда ты забываешь об их телескопичности и хочешь достать блюдо из духовки не выдвигая поднос, а он зараза едет =) можно и уронить-пролить. Но можно ими и не пользоваться, там есть и простые держатели.</p>
							<p>Если духовку сильно "загадили" жиром и т.д. не надо чистить ее в раскоряку на коленках- там все хромированные части элементарно снимаются и ставится обратно. Нижняя часть тоже откручивается. И еще- интересная функция разморозки- обдувает теплым воздухом ( для тех у кого нет микроволновки к примеру)</p>',
							'author' => 'Тигра',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
				),
				85 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 27,
					'shop_measure_id' => 0,
					'shop_group_id' => 591,
					'type' => 'item',
					'name' => 'Zanussi ZCV 9540H1 W',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '13841.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Zanussi',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => 'Алла Иванова',
							'text' => '<p>Купили плиту две недели назад, все не можем нарадоваться. Красивая, стильная, удобная. В плите устраивает все, даже поругать её не за что. Теперь готовлю с удовольствием и зову постоянно в гости друзей.</p>',
							'author' => 'Тигра',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
				),
				80 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 43,
					'shop_measure_id' => 0,
					'shop_group_id' => 591,
					'type' => 'item',
					'name' => 'Gorenje K 55320 AW',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '9535.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Gorenje',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => 'Я счастлив',
							'text' => '<p>Наконец-то выпечка не пригорает ни снизу, ни с верху. Даже манник на весь поддон равномерно поднимается!!! АААА! Счастье - есть!
							Режим гриля+вентилятор фактически заменяет аэрогриль, даже лучше. Убрал с кухни это стеклянное уродство
							Блокировка газа при потухании огня или незажигании - дети даже если ручки накрутят - ничего не случится.</p>',
							'author' => 'Владимир',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
				),
				122 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 42,
					'shop_measure_id' => 0,
					'shop_group_id' => 592,
					'type' => 'item',
					'name' => 'Asko D 5556 XXL',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '109900.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Asko',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Отличная сборка. Надежная посудомоечная машина. Все внутри и снаружи удобно, продумано. Очень вместительная. Экономно расходует воду. Тихоня в работе. Одних только программ 12. Всегда можно подобрать нужный на данный момент режим. Есть возможность выставить таймер и она начнет мыть тогда, когда вы ей укажите, даже если дома никого нет.</p>',
							'author' => 'Юрий Брынь',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
				),
				121 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 18,
					'shop_measure_id' => 0,
					'shop_group_id' => 592,
					'type' => 'item',
					'name' => 'Bosch SPV 63M50',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '18948.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Bosch',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Это первая ПМ в нашем доме. Само наличие уже было в радость. На семью из 2-х человек хватает вполне. Если народу больше- может и не хватить или придется загружать после каждого приема пищи. Моет тихо. Относительно быстро (в половинной загрузке разница по времени очень небольшая). В силу лени не ковыряюсь в настройках или моющих средствах- мою практически на одной программе со средствами, рекомендованными фирмой. Отмывает все, кроме присохших остатков (если не отмочить). Вилки-ложки стали как новые. Стаканы-бокалы тоже. Рисунок с тарелок до сих пор на месте))) довольна до визга, особенно когда устала, а посуды гора. Звуковой сигнал пищит, не раздражая. Луч на полу сообщает, что ПМ заработала, потому что слышу ее только ночью и стоя рядом. Из другой комнаты уже не слышно. В целом- рекомендую</p>',
							'author' => 'Юлия Барова',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
				),
				120 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 46,
					'shop_measure_id' => 0,
					'shop_group_id' => 592,
					'type' => 'item',
					'name' => 'NEFF S66M64N3',
					'marking' => '',
					'description' => '',
					'text' => '',
					'price' => '15660.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'NEFF',
					'comments' => array(
						array(
							'parent_id' => 0,
							'subject' => '',
							'text' => '<p>Выбрали эту машину, т.к. необходимо было вписать в уже существующую кухню. Вся остальная техника Neff, так что машина вписалась идеально. Качество мойки отличное. используем каждый день, при небольшом количестве посуды с опцией vario Spead - всё отмывает. Были некоторые "косяки" на короткой мойке - видимо программа рассчитана на малое количество посуды. Влезают и большие кастрюли, и противни. Для тех кому нужна компактная машина - это замечательный вариант решения проблемы!</p>',
							'author' => 'Ольга',
							'email' => '',
							'phone' => '',
							'active' => 1,
							'grade' => 5,
						),
					),
				),
			),
			'properties' => array(
				66 => array(
					'name' => 'Доп. изображения',
					'dirName' => '',
					'type' => 'file',
					'description' => '',
					'default_value' => '',
					'tag_name' => 'img',
					'sorting' => 10,
					'multiple' => 1,
					'filter' => 0,
					'show_in_group' => 1,
					'show_in_item' => 1,
					'image_large_max_width' => 1000,
					'image_large_max_height' => 1000,
					'image_small_max_width' => 200,
					'image_small_max_height' => 200,
					'shop_group_id' => '0/588',
				),
				67 => array(
					'name' => 'Строка',
					'dirName' => '',
					'type' => 'string',
					'description' => '',
					'default_value' => '',
					'tag_name' => 'string',
					'sorting' => 10,
					'multiple' => 1,
					'filter' => 0,
					'show_in_group' => 1,
					'show_in_item' => 1,
					'shop_group_id' => 0,
				),
				'68' => array(
					'name' => 'Список',
					'dirName' => '',
					'type' => 'list',
					'list_id' => 4,
					'description' => '',
					'default_value' => '',
					'tag_name' => 'list',
					'sorting' => 10,
					'multiple' => 1,
					'filter' => 2,
					'show_in_group' => 1,
					'show_in_item' => 1,
					'shop_group_id' => '0/588',
				),
			)
		),
		2 => array(
			'name' => 'Доска объявлений',
			'dirName' => '',
			'structure_id' => 34,
			'shop_company_id' => 1,
			'shop_currency_id' => 1,
			'shop_country_id' => 175,
			'shop_measure_id' => 0,
			'email' => 'admin@localhost.ru',
			'items_on_page' => 6,
			'items_sorting_field' => 0,
			'items_sorting_direction' => 1,
			'image_large_max_width' => 800,
			'image_large_max_height' => 800,
			'image_small_max_width' => 270,
			'image_small_max_height' => 270,
			'siteuser_group_id' => 0,
			'typograph_default_items' => 0,
			'typograph_default_groups' => 0,
			'send_order_email_admin' => 1,
			'send_order_email_user' => 1,
			'comment_active' => 1,
			'apply_tags_automatically' => 1,
			'write_off_paid_items' => 1,
			'change_filename' => 1,
			'attach_digital_items' => 1,
			'use_captcha' => 1,
			'shop_groups' => array(
				594 => array(
					'name' => 'Недвижимость',
					'parent_id' => 0,
					'sorting' => 10,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				595 => array(
					'name' => 'Транспорт',
					'parent_id' => 0,
					'sorting' => 20,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				596 => array(
					'name' => 'Личные вещи',
					'parent_id' => 0,
					'sorting' => 30,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				597 => array(
					'name' => 'Услуги',
					'parent_id' => 0,
					'sorting' => 40,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				598 => array(
					'name' => 'Для дома и дачи',
					'parent_id' => 0,
					'sorting' => 50,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				599 => array(
					'name' => 'Бытовая электроника',
					'parent_id' => 0,
					'sorting' => 60,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				600 => array(
					'name' => 'Работа',
					'parent_id' => 0,
					'sorting' => 70,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				601 => array(
					'name' => 'Хобби и отдых',
					'parent_id' => 0,
					'sorting' => 80,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
				602 => array(
					'name' => 'Животные',
					'parent_id' => 0,
					'sorting' => 90,
					'active' => 1,
					'indexing' => 1,
					'description' => '',
				),
			),
			'shop_items' => array(
				187 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 0,
					'shop_measure_id' => 0,
					'shop_group_id' => 602,
					'type' => 'item',
					'name' => 'Котенок',
					'marking' => '',
					'description' => 'Отдам в хорошие руки котенка. Мальчик, 3 месяца.',
					'text' => 'Отдам в хорошие руки котенка. Мальчик, 3 месяца.К лотку приучен.',
					'price' => '0.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Котенок',
				),
				188 => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 0,
					'shop_measure_id' => 0,
					'shop_group_id' => 595,
					'type' => 'item',
					'name' => 'Chevrolet Camaro',
					'marking' => '',
					'description' => 'Продам Chevrolet Camaro 2010 г.в.',
					'text' => 'Chevrolet Camaro – это яркий представитель семейства "muscle", мускулистых автомобилей, которые выбирают люди, ведущие активный образ жизни. Отличительными особенностями данного автомобиля являются сдвоенные квадратные фары, широкая решетка радиатора, наличие большого количества спойлеров и обтекателей, современный дизайн и мощный двигатель.',
					'price' => '1800000.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Авто, Chevrolet',
				),
				'189' => array(
					'shortcut_id' => 0,
					'shop_tax_id' => 0,
					'shop_seller_id' => 0,
					'shop_currency_id' => 1,
					'shop_producer_id' => 0,
					'shop_measure_id' => 0,
					'shop_group_id' => 596,
					'type' => 'item',
					'name' => 'Диван "ФИДЖИ"',
					'marking' => '',
					'description' => 'Продам диван.',
					'text' => '</p>Диван «Фиджи» - грамотное решение квартирного вопроса: комфортный диван днем превращается в просторное спальное место ночью. Кроме того, «Фиджи» обладает компактными габаритами, благодаря чему легко устроится даже в небольшой гостиной. А 2 вида вельвета в бежево-коричневой гамме в сочетании с шоколадной экокожей подходит по стилистике ко всем современным интерьерам.</p>
					<p>Ткань: 1 гр, Микровелюр крем + Экокожа матовая дарк браун</p>',
					'price' => '15000.00',
					'active' => 1,
					'indexing' => 1,
					'modification_id' => 0,
					'tags' => 'Диван',
				),
			),
		),
	),
	'en' => array(
	//
	),
);

$aStructurei18n = array(
	'ru' => array(
		1 => array(
			'name' => 'Главная',
			'path' => '/',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 1,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 10,
			'type' => 'page',
			'document_id' => 2,
			'seo_title' => 'Демонстрационный сайт системы управления сайтом HostCMS',
			'seo_description' => 'Демонстрационный сайт системы управления сайтом HostCMS',
			'seo_keywords' => 'Демонстрационный сайт системы управления сайтом HostCMS',
		),
		2 => array(
			'name' => 'Услуги',
			'path' => '',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 10,
			'type' => 'lib',
			'lib_id' => 1,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		7 => array(
			'name' => 'Новости',
			'path' => 'news',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 11,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 20,
			'type' => 'lib',
			'lib_id' => 1,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		20 => array(
			'name' => 'RSS',
			'path' => '',
			'parent_id' => 7,
			'structure_menu_id' => 1,
			'template_id' => 3,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 14,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		72 => array(
			'name' => 'Импорт новостей',
			'path' => 'import',
			'parent_id' => 7,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 15,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		10 => array(
			'name' => 'Ошибка 404',
			'path' => '404',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 999,
			'type' => 'page',
			'document_id' => 5,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		13 => array(
			'name' => 'Гостевая книга',
			'path' => 'guestbook',
			'parent_id' => 0,
			'structure_menu_id' => 2,
			'template_id' => 11,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 40,
			'type' => 'lib',
			'lib_id' => 2,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		15 => array(
			'name' => 'Фотогалерея',
			'path' => 'photogallery',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 11,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 40,
			'type' => 'lib',
			'lib_id' => 44,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		16 => array(
			'name' => 'Статьи',
			'path' => 'articles',
			'parent_id' => 0,
			'structure_menu_id' => 2,
			'template_id' => 11,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 10,
			'type' => 'lib',
			'lib_id' => 1,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		21 => array(
			'name' => 'RSS',
			'path' => '',
			'parent_id' => 16,
			'structure_menu_id' => 1,
			'template_id' => 3,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 14,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		22 => array(
			'name' => 'Поиск',
			'path' => 'search',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 888,
			'type' => 'lib',
			'lib_id' => 3,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		23 => array(
			'name' => 'Обратная связь',
			'path' => 'feedback',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 50,
			'type' => 'lib',
			'lib_id' => 18,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		26 => array(
			'name' => 'Личный кабинет',
			'path' => 'users',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 14,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 888,
			'type' => 'lib',
			'lib_id' => 23,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		27 => array(
			'name' => 'Восстановление пароля',
			'path' => 'restore_password',
			'parent_id' => 26,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 26,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		30 => array(
			'name' => 'Регистрация',
			'path' => 'registration',
			'parent_id' => 26,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 24,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		54 => array(
			'name' => 'Заказы',
			'path' => 'order',
			'parent_id' => 26,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 25,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		73 => array(
			'name' => 'Информация о пользователе',
			'path' => 'info',
			'parent_id' => 26,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 28,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		75 => array(
			'name' => 'Лицевые счета',
			'path' => 'account',
			'parent_id' => 26,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 30,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		76 => array(
			'name' => 'Пополнение лицевого счета',
			'path' => 'pay',
			'parent_id' => 75,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 0,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 31,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		80 => array(
			'name' => 'Партнерские программы',
			'path' => 'affiliats',
			'parent_id' => 26,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 38,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		85 => array(
			'name' => 'Код приглашения',
			'path' => 'info',
			'parent_id' => 80,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 34,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		86 => array(
			'name' => 'Структура приглашенных',
			'path' => 'invites',
			'parent_id' => 80,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 39,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		87 => array(
			'name' => 'Бонусы',
			'path' => 'bonuses',
			'parent_id' => 80,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 40,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		81 => array(
			'name' => 'Служба поддержки',
			'path' => 'helpdesk',
			'parent_id' => 26,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 33,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		82 => array(
			'name' => 'Мои объявления',
			'path' => 'my_advertisement',
			'parent_id' => 26,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 35,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		411 => array(
			'name' => 'Личные сообщения',
			'path' => 'my_messages',
			'parent_id' => 26,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 42,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		31 => array(
			'name' => 'Почтовые рассылки',
			'path' => 'maillist',
			'parent_id' => 0,
			'structure_menu_id' => 2,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 777,
			'type' => 'lib',
			'lib_id' => 19,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		32 => array(
			'name' => 'Переход по ссылке баннера',
			'path' => 'showbanner',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 888,
			'type' => 'lib',
			'lib_id' => 21,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		33 => array(
			'name' => 'Опросы',
			'path' => 'polls',
			'parent_id' => 0,
			'structure_menu_id' => 2,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 50,
			'type' => 'lib',
			'lib_id' => 16,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		34 => array(
			'name' => 'Доска объявлений',
			'path' => 'board',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 11,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 50,
			'type' => 'lib',
			'lib_id' => 4,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		36 => array(
			'name' => 'Форум',
			'path' => 'forums',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 40,
			'type' => 'lib',
			'lib_id' => 17,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		37 => array(
			'name' => 'Карта сайта',
			'path' => 'map',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 888,
			'type' => 'lib',
			'lib_id' => 5,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		38 => array(
			'name' => 'Ошибка 403',
			'path' => '403',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 0,
			'sorting' => 999,
			'type' => 'page',
			'document_id' => 4,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		74 => array(
			'name' => 'Google SiteMap',
			'path' => 'sitemap',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 0,
			'sorting' => 888,
			'type' => 'lib',
			'lib_id' => 29,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		79 => array(
			'name' => 'Сайт временно недоступен',
			'path' => '503',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 0,
			'sorting' => 999,
			'type' => 'page',
			'document_id' => 6,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		84 => array(
			'name' => 'Контактные данные',
			'path' => 'contacts',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 0,
			'sorting' => 888,
			'type' => 'page',
			'document_id' => 8,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		42 => array(
			'name' => 'Интернет-магазин',
			'path' => 'shop',
			'parent_id' => 0,
			'structure_menu_id' => 1,
			'template_id' => 11,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 10,
			'type' => 'lib',
			'lib_id' => 6,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		412 => array(
			'name' => 'Экспорт в Яндекс.Недвижимость',
			'path' => 'yandex_realty',
			'parent_id' => 42,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 43,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		83 => array(
			'name' => 'Обмен с 1C',
			'path' => '1c',
			'parent_id' => 42,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 0,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 36,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		77 => array(
			'name' => 'Производители',
			'path' => 'producers',
			'parent_id' => 42,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 41,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		63 => array(
			'name' => 'Экспорт в Яндекс.Маркет',
			'path' => 'yandex_market',
			'parent_id' => 42,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 12,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		57 => array(
			'name' => 'Прайс-лист',
			'path' => 'price',
			'parent_id' => 42,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 11,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		52 => array(
			'name' => 'Сравнение товаров',
			'path' => 'compare_items',
			'parent_id' => 42,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 10,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		48 => array(
			'name' => 'Продавцы',
			'path' => 'sellers',
			'parent_id' => 42,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 9,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		43 => array(
			'name' => 'Корзина',
			'path' => 'cart',
			'parent_id' => 42,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 1,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 7,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
		55 => array(
			'name' => 'Версия для печати',
			'path' => 'print',
			'parent_id' => 43,
			'structure_menu_id' => 1,
			'template_id' => 9,
			'show' => 0,
			'active' => 1,
			'indexing' => 1,
			'sorting' => 0,
			'type' => 'lib',
			'lib_id' => 8,
			'seo_title' => '',
			'seo_description' => '',
			'seo_keywords' => '',
		),
	),
	'en' => array(
	//
	),
);

//Массив форумов
$aForumi18n = array(
	'ru' => array(
		1 => array(
			'name' => 'Конференция',
			'structure_id' => 36,
			'description' => 'Демонстрационная конференция',
			'topics_on_page' => 10,
			'posts_on_page' => 10,
			'flood_protection_time' => 1,
			'allow_edit_time' => 14400,
			'allow_delete_time' => 1800,
			'forum_groups' => array (
				1 => array (
					'name' => 'Группа 1',
					'description' => 'Группа форумов 1',
					'sorting' => 10,
					'forum_categories' => array (
						1 => array (
							'name' => 'Форум 1',
							'description' => 'Демонстрационный форум 1',
							'closed' => 0,
							'sorting' => 10,
							'email' => 'admin@localhost.ru',
							'postmoderation' => 1,
							'visible' => 1,
							'use_captcha' => 1,
							'allow_guest_posting' => 1,
							'forum_topics' => array (
								1 => array (
									'visible' => 1,
									'announcement' => 1,
									'closed' => 1,
								),
							),
						),
					),
				),
				2 => array (
					'name' => 'Группа 2',
					'description' => 'Группа форумов 2',
					'sorting' => 20,
				),
			),
		),
	),
	'en' => array(
	//
	),
);

//Массив служб поддержки
$aHelpdeski18n = array(
	'ru' => array(
		1 => array(
			'name' => 'Демонстрационная служба',
			'structure_id' => 81,
			'notify_change_criticality_level' => 1,
			'notify' => 1,
			'delete_attach_in_days' => 0,
		),
	),
	'en' => array(
	//
	),
);

//Массив групп опросов
$aAdvertisementGroupi18n = array(
	'ru' => array(
		1 => array(
			'name' => 'Группа баннеров',
			'description' => '',
			'advertisement' => array(
				1 => array(
					'name' => 'HostCMS',
					'description' => '',
					'type' => 0,
					'file_name' => '1.jpg',
					'href' => 'http://www.hostcms.ru/',
				),
			),
		),
	),
	'en' => array(
	//
	),
);

//Скидки на товары
$aShopDiscounti18n = array(
	'ru' => array(
		2 => array(
			'name' => 'Скидка',
			'start_datetime' => '2008-04-21 14:50:00',
			'end_datetime' => '2015-09-29 14:50:04',
			'active' => 1,
			'value' => 10,
			'type' => 0,
			'shop_id' => 1,
		),
	),
	'en' => array(
	//
	),
);

//Пользователи сайта
$aSiteuseri18n = array(
	'ru' => array(
		3 => array(
			'login' => 'tygra',
			'password' => 'tygra',
			'email' => 'tygra@localhost.ru',
			'active' => 1,
			'name' => 'Тигра',
			'surname' => 'Тигровый',
			'patronymic' => 'Тигрович',
			'company' => 'ООО "Братья тигры"',
			'phone' => '+ 398 (23) 328-710',
			'website' => 'www.hostcms.ru',
			'country' => 'Волшебный лес',
			'city' => 'Простоквашино',
			'address' => 'Лизюкова',
		),
	),
	'en' => array(
	//
	),
);

//Валюты
$aShopCurrencies = array(
	1 => array(
		'name' => 'руб.',
		'code' => 'RUR',
		'exchange_rate' => '1.000000',
		'default' => 1,
		'sorting' => 10,
	),
	2 => array(
		'name' => '€',
		'code' => 'EUR',
		'exchange_rate' => '60.000000',
		'default' => 0,
		'sorting' => 20,
	),
	3 => array(
		'name' => '$',
		'code' => 'USD',
		'exchange_rate' => '53.000000',
		'default' => 0,
		'sorting' => 30,
	),
);

//Массив складов магазина
$aShopWarehouses = array(
	1 => array(
		'name' => 'Основной',
		'sorting' => '',
		'active' => 1,
		'default' => 1,
	),
);

//Массив производителей
$aShopProducers = array(
	5 => array(
		'name' => 'Samsung',
		'active' => '',
		'sorting' => 10,
	),
	7 => array(
		'name' => 'Sony',
		'active' => '',
		'sorting' => 20,
	),
	8 => array(
		'name' => 'JVC',
		'active' => '',
		'sorting' => 30,
	),
	9 => array(
		'name' => 'Panasonic',
		'active' => '',
		'sorting' => 40,
	),
	13 => array(
		'name' => 'LG',
		'active' => '',
		'sorting' => 50,
	),
	16 => array(
		'name' => 'Explay',
		'active' => '',
		'sorting' => 60,
	),
	18 => array(
		'name' => 'Bosch',
		'active' => '',
		'sorting' => 70,
	),
	23 => array(
		'name' => 'ATLANT',
		'active' => '',
		'sorting' => 80,
	),
	27 => array(
		'name' => 'Zanussi',
		'active' => '',
		'sorting' => 80,
	),
	33 => array(
		'name' => 'Kodak',
		'active' => '',
		'sorting' => 80,
	),
	34 => array(
		'name' => 'Olympus',
		'active' => '',
		'sorting' => 90,
	),
	42 => array(
		'name' => 'Asko',
		'active' => '',
		'sorting' => 100,
	),
	43 => array(
		'name' => 'Gorenje',
		'active' => '',
		'sorting' => 110,
	),
	44 => array(
		'name' => 'Apple',
		'active' => '',
		'sorting' => 120,
	),
	45 => array(
		'name' => 'Pentax',
		'active' => '',
		'sorting' => 130,
	),
	46 => array(
		'name' => 'NEFF',
		'active' => '',
		'sorting' => 130,
	),
);

//Массив типов параметров типовых динамических страниц
$aLibPropertyTypes = array(
	'input' => 0,
	'checkbox' => 1,
	'xsl' => 2,
	'list' => 3,
	'sql' => 4,
	'textarea' => 5,
);

//Массив типов полей форм
$aFormFieldTypes = array(
	'input' => 0,
	'password' => 1,
	'file' => 2,
	'radio' => 3,
	'checkbox' => 4,
	'textarea' => 5,
	'list' => 6,
	'hidden' => 7,
	'label' => 8,
	'checkbox_list' => 9,
);

//Массив типов дополнительных свойств
$aPropertyTypes = array(
	'int' => 0,
	'float' => 11,
	'string' => 1,
	'file' => 2,
	'list' => 3,
	'textarea' => 4,
	'informationsystem' => 5,
	'shop' => 12,
	'wysiwyg' => 6,
	'checkbox' => 7,
	'date' => 8,
	'datetime' => 9,
	'hidden' => 10,
);

//Массив типов товаров
$aShopItemTypes = array(
	'item' => 0,
	'electonic' => 1,
	'divisible' => 2,
);

//Массив типов узлов структуры
$aStructureTypes = array(
	'page' => 0,
	'dynamic' => 1,
	'lib' => 2,
);

//Массив соответствий типовых динамических страниц загружаемого и созданного макетов
$aAssosiatedLibs = array();

//Массив соответствий параметров типовых динамических страниц загружаемого и созданного макетов
$aAssosiatedLibFields = array();

//Массив соответствий макетов сайта
$aAssosiatedTemplates = array();

//Массив соответствий узлов структуры
$aAssosiatedStructures = array();

//Массив соответствий списков
$aAssosiatedLists = array();

//Массив соответствий меню
$aAssosiatedStructureMenus = array();

//Массив соответствий документов
$aAssosiatedDocuments = array();

//Массив соответствий элементов списков
$aAssosiatedListValues = array();

//Массив соответствий форм
$aAssosiatedForms = array();

//Массив соответствий опросов
$aAssosiatedPollGroups = array();

//Массив соответствий ИС
$aAssosiatedInformationsystems = array();

//Массив соответствий групп ИС
$aAssosiatedInformationsystemGroups = array();

//Массив соответствий свойств
$aAssosiatedProperties = array();

//Массив соответствий валют
$aAssosiatedShopCurrencies = array();

//Массив соответствий складов
$aAssosiatedShopWarehouses = array();

//Массив соответствий производителей
$aAssosiatedShopProducers = array();

//Массив соответствий магазинов
$aAssosiatedShops = array();

//Массив соответствий групп магазина
$aAssosiatedShopGroups = array();

//Массив соответствий названий XSL-шаблонов
$aAssosiatedXslNames = array();

//Массив соответствий форумов
$aAssosiatedForums = array();

//Массив соответствий служб поддержки
$aAssosiatedHelpdesks = array();

//Массив соответствий скидок
$aAssosiatedShopDiscounts = array();

//Массив соответствий пользователей сайта
$aAssosiatedSiteusers = array();

// Создаем сайт
$oSite = Core_Entity::factory('Site');
$oSite
	->name("{$aSitei18n[$sLng]['name']} {$sCurrentDate}")
	->admin_email($sCompanyEmail)
	->save();

$sSitePostfix = sprintf($aSitei18n[$sLng]['sitePostfix'], $oSite->id);

//Menus
foreach ($aMenui18n[$sLng] as $iMenuId => $sMenuName)
{
	$oStructure_Menu = Core_Entity::factory('Structure_Menu')->getByName($sMenuName . $sSitePostfix, FALSE);

	if (is_null($oStructure_Menu))
	{
		$oStructure_Menu = Core_Entity::factory('Structure_Menu');
		$oStructure_Menu
			->site_id($oSite->id)
			->name($sMenuName . $sSitePostfix)
			->save();
	}

	$aAssosiatedStructureMenus[$iMenuId] = $oStructure_Menu->id;

	$aReplace["->menu({$iMenuId})"] = "->menu({$oStructure_Menu->id})";
}

//Lists
if (Core::moduleIsActive('list'))
{
	foreach ($aListi18n[$sLng] as $iListId => $aList)
	{
		$oList = Core_Entity::factory('List')->getByName($aList['name'] . $sSitePostfix, FALSE);

		if (is_null($oList))
		{
			$aExplodeDir = explode('/', $aList['dirName']);
			array_unshift($aExplodeDir, $sSitePostfix);

			$iParent_Id = 0;
			foreach ($aExplodeDir as $sDirName)
			{
				if($sDirName != '')
				{
					$oList_Dir = Core_Entity::factory('List_Dir');
					$oList_Dir
						->queryBuilder()
						->where('list_dirs.parent_id', '=', $iParent_Id);

					$oList_Dir = $oList_Dir->getByName($sDirName, FALSE);

					if (is_null($oList_Dir))
					{
						$oList_Dir = Core_Entity::factory('List_Dir');
						$oList_Dir
							->parent_id($iParent_Id)
							->site_id($oSite->id)
							->name($sDirName)
							->save();
					}

					$iParent_Id = $oList_Dir->id;
				}
			}

			$oList = Core_Entity::factory('List');
			$oList
				->name($aList['name'] . $sSitePostfix)
				->list_dir_id($iParent_Id)
				->site_id($oSite->id)
				->save();

			$iList_Id = $oList->id;

			if(isset($aList['list_items']))
			{
				foreach ($aList['list_items'] as $iListItemId => $aListItem)
				{
					$oListItem = Core_Entity::factory('List_Item');
					$oListItem
						->list_id($iList_Id)
						->value($aListItem['value'])
						->sorting($aListItem['sorting'])
						->description($aListItem['description'])
						->active($aListItem['active'])
						->save();

					$aAssosiatedListValues[$iListItemId] = $oListItem->id;
				}
			}

			$aAssosiatedLists[$iListId] = $iList_Id;
		}
	}
}

//Forms
if (Core::moduleIsActive('form'))
{
	foreach ($aFormi18n[$sLng] as $iFormId => $aForm)
	{
		$oForm = Core_Entity::factory('Form')->getByName($aForm['name'] . $sSitePostfix, FALSE);

		if (is_null($oForm))
		{
			$oForm = Core_Entity::factory('Form');
			$oForm
				->name($aForm['name'] . $sSitePostfix)
				->email($aForm['email'])
				->button_name($aForm['button_name'])
				->button_value($aForm['button_value'])
				->use_captcha($aForm['use_captcha'])
				->email_subject($aForm['email_subject'])
				->site_id($oSite->id)
				->save();

			$iForm_Id = $oForm->id;

			$aAssosiatedForms[$iFormId] = $iForm_Id;

			if (isset($aForm['form_fields']))
			{
				foreach ($aForm['form_fields'] as $iFormFieldId => $aFormField)
				{
					$aExplodeDir = explode('/', $aFormField['dirName']);
					array_unshift($aExplodeDir, $sSitePostfix);

					$iParent_Id = 0;
					foreach ($aExplodeDir as $sDirName)
					{
						if($sDirName != '')
						{
							$oForm_Field_Dir = Core_Entity::factory('Form_Field_Dir');
							$oForm_Field_Dir
								->queryBuilder()
								->where('form_field_dirs.parent_id', '=', $iParent_Id);

							$oForm_Field_Dir = $oForm_Field_Dir->getByName($sDirName, FALSE);

							if (is_null($oForm_Field_Dir))
							{
								$oForm_Field_Dir = Core_Entity::factory('Form_Field_Dir');
								$oForm_Field_Dir
									->form_id($iForm_Id)
									->parent_id($iParent_Id)
									->name($sDirName)
									->save();
							}

							$iParent_Id = $oForm_Field_Dir->id;
						}
					}

					$oForm_Field = Core_Entity::factory('Form_Field');
					$oForm_Field
						->form_id($iForm_Id)
						->form_field_dir_id($iParent_Id)
						//->list_id(isset($aFormField['list_id']) ? $aFormField['list_id'] : 0)
						->list_id(Core_Array::get($aFormField, 'list_id', 0))
						->type($aFormFieldTypes[$aFormField['type']])
						->size(isset($aFormField['size']) ? $aFormField['size'] : 0)
						->rows(isset($aFormField['rows']) ? $aFormField['rows'] : 0)
						->cols(isset($aFormField['cols']) ? $aFormField['cols'] : 0)
						->checked(isset($aFormField['checked']) ? $aFormField['checked'] : 0)
						->name($aFormField['name'])
						->caption($aFormField['caption'])
						->default_value(isset($aFormField['default_value']) ? $aFormField['default_value'] : '')
						->sorting($aFormField['sorting'])
						->obligatory($aFormField['obligatory'])
						->save();
				}
			}
		}
	}
}

//Xsls
foreach ($aXsli18n[$sLng] as $sFileName => $aXsl)
{
	$oXsl = Core_Entity::factory('Xsl')->getByName($aXsl['name'] . $sSitePostfix, FALSE);

	if (is_null($oXsl))
	{
		$aExplodeDir = explode('/', $aXsl['dirName']);
		array_unshift($aExplodeDir, $sSitePostfix);

		$iParent_Id = 0;
		foreach ($aExplodeDir as $sDirName)
		{
			$oXsl_Dir = Core_Entity::factory('Xsl_Dir');
			$oXsl_Dir
				->queryBuilder()
				->where('xsl_dirs.parent_id', '=', $iParent_Id);

			$oXsl_Dir = $oXsl_Dir->getByName($sDirName, FALSE);

			if (is_null($oXsl_Dir))
			{
				$oXsl_Dir = Core_Entity::factory('Xsl_Dir');
				$oXsl_Dir
					->parent_id($iParent_Id)
					->name($sDirName)
					->save();
			}

			$iParent_Id = $oXsl_Dir->id;
		}

		$oXsl = Core_Entity::factory('Xsl');
		$oXsl
			->name($aXsl['name'] . $sSitePostfix)
			->xsl_dir_id($iParent_Id)
			->save();

		$aReplace["'{$aXsl['name']}'"] = "'" . $aXsl['name'] . $sSitePostfix . "'";

		$oXsl->saveXslFile($Install_Controller->loadFile($tmpDir . "tmp/hostcmsfiles/xsl/" . $sFileName, $aReplace));

		$aAssosiatedXslNames[$aXsl['name']] = $aXsl['name'] . $sSitePostfix;
	}
}

//Libs
foreach ($aLibi18n[$sLng] as $iLibId => $aLib)
{
	$oLib = Core_Entity::factory('Lib')->getByName($aLib['name'] . $sSitePostfix, FALSE);

	if (is_null($oLib))
	{
		$aExplodeDir = explode('/', $aLib['dirName']);
		array_unshift($aExplodeDir, $sSitePostfix);

		$iParent_Id = 0;
		$iLib_Id = 0;
		$iLib_Property_Id = 0;

		foreach ($aExplodeDir as $sDirName)
		{
			$oLib_Dir = Core_Entity::factory('Lib_Dir');
			$oLib_Dir
				->queryBuilder()
				->where('lib_dirs.parent_id', '=', $iParent_Id);

			$oLib_Dir = $oLib_Dir->getByName($sDirName, FALSE);

			if(is_null($oLib_Dir))
			{
				$oLib_Dir = Core_Entity::factory('Lib_Dir');
				$oLib_Dir
					->parent_id($iParent_Id)
					->name($sDirName)
					->save();
			}

			$iParent_Id = $oLib_Dir->id;
		}

		$oLib = Core_Entity::factory('Lib');
		$oLib
			->name(strval($aLib['name'] . $sSitePostfix))
			->lib_dir_id($iParent_Id)
			->save();

		$oLib->saveLibFile($Install_Controller->loadFile($tmpDir . "tmp/hostcmsfiles/lib/lib_" . $iLibId . '/' . $aLib['code'], $aReplace));
		$oLib->saveLibConfigFile($Install_Controller->loadFile($tmpDir . "tmp/hostcmsfiles/lib/lib_" . $iLibId . '/' . $aLib['config'], $aReplace));

		$iLib_Id = $oLib->id;

		$aAssosiatedLibs[$iLibId] = $iLib_Id;

		if(isset($aLib['params']))
		{
			foreach ($aLib['params'] as $iParamId => $aParams)
			{
				$oLib_Property = Core_Entity::factory('Lib_Property');
				$oLib_Property
					->lib_id($iLib_Id)
					->name(strval($aParams['name']))
					->varible_name(strval($aParams['varible_name']))
					->type($aLibPropertyTypes[$aParams['type']])
					->default_value(isset($aParams['default_value']) ? $aParams['default_value'] : '')
					->sorting(strval($aParams['sorting']))
					->sql_request(isset($aParams['sql']) ? $aParams['sql'] : '')
					->sql_caption_field(isset($aParams['sql_caption_field']) ? $aParams['sql_caption_field'] : '')
					->sql_value_field(isset($aParams['sql_value_field']) ? $aParams['sql_value_field'] : '')
					->save();

				$iLib_Property_Id = $oLib_Property->id;

				$aAssosiatedLibFields[$iParamId] = $iLib_Property_Id;

				if (isset($aParams['values']))
				{
					foreach ($aParams['values'] as $value => $name)
					{
						$oLib_Property_List_Value = Core_Entity::factory('Lib_Property_List_Value');
						$oLib_Property_List_Value
							->lib_property_id($iLib_Property_Id)
							->name($name)
							->value($value)
							->save();
					}
				}
			}
		}
	}
}

//Polls
if (Core::moduleIsActive('poll'))
{
	foreach ($aPollGroupi18n[$sLng] as $iPollGroupId => $aPollGroup)
	{
		$oPoll_Group = Core_Entity::factory('Poll_Group')->getByName($aPollGroup['name'] . $sSitePostfix, FALSE);

		if (is_null($oPoll_Group))
		{
			$oPoll_Group = Core_Entity::factory('Poll_Group');
			$oPoll_Group
				->structure_id($aPollGroup['structure_id'])
				->site_id($oSite->id)
				->name($aPollGroup['name'] . $sSitePostfix)
				->save();

			$iPollGroup_Id = $oPoll_Group->id;

			if (isset($aPollGroup['polls']))
			{
				foreach ($aPollGroup['polls'] as $iPollId => $aPoll)
				{
					$oPoll = Core_Entity::factory('Poll');
					$oPoll
						->name($aPoll['name'])
						->type($aPoll['type'])
						->show_results($aPoll['show_results'])
						->active($aPoll['active'])
						->start_date($aPoll['start_date'])
						->end_date($aPoll['end_date'])
						->poll_group_id($iPollGroup_Id)
						->save();

					$iPoll_Id = $oPoll->id;

					if (isset($aPoll['poll_responses']))
					{
						foreach ($aPoll['poll_responses'] as $iPollResponseId => $aPollResponse)
						{
							$oPoll_Response = Core_Entity::factory('Poll_Response');
							$oPoll_Response
								->poll_id($iPoll_Id)
								->name($aPollResponse['name'])
								->grade($aPollResponse['grade'])
								->sorting($aPollResponse['sorting'])
								->save();
						}
					}
				}
			}

			$aAssosiatedPollGroups[$iPollGroupId] = $iPollGroup_Id;

			$aReplace["'Poll_Group', {$iPollGroupId}"] = "'Poll_Group', " . $iPollGroup_Id;
		}
	}
}

//Siteusers
if (Core::moduleIsActive('siteuser'))
{
	foreach ($aSiteuseri18n[$sLng] as $iSiteuserId => $aSiteuser)
	{
		$oSiteuser = Core_Entity::factory('Siteuser');
		$oSiteuser
			->queryBuilder()
			->where('siteusers.site_id', '=', $oSite->id);

		$oSiteuser = $oSiteuser->getByLogin($aSiteuser['login'], FALSE);

		if (is_null($oSiteuser))
		{
			$oSiteuser = Core_Entity::factory('Siteuser');
			$oSiteuser
				->site_id($oSite->id)
				->login(Core_Array::get($aSiteuser, 'login', 'test'))
				->password(Core_Hash::instance()->hash(Core_Array::get($aSiteuser, 'password', 'test')))
				->email(Core_Array::get($aSiteuser, 'email', 'admin@localhost'))
				->name(Core_Array::get($aSiteuser, 'name', ''))
				->surname(Core_Array::get($aSiteuser, 'surname', ''))
				->patronymic(Core_Array::get($aSiteuser, 'patronymic', ''))
				->company(Core_Array::get($aSiteuser, 'company', ''))
				->phone(Core_Array::get($aSiteuser, 'phone', ''))
				->website(Core_Array::get($aSiteuser, 'website', ''))
				->country(Core_Array::get($aSiteuser, 'country', ''))
				->city(Core_Array::get($aSiteuser, 'city', ''))
				->address(Core_Array::get($aSiteuser, 'address', ''))
				->active(Core_Array::get($aSiteuser, 'active', 1))
				->datetime(Core_Date::timestamp2sql(time()))
				->save();

			$aAssosiatedSiteusers[$iSiteuserId] = $oSiteuser->id;
		}
	}
}

//Informationsystems
foreach ($aInformationsystemi18n[$sLng] as $iInformationsystemId => $aInformationsystem)
{
	$oInformationsystem = Core_Entity::factory('Informationsystem');
	$oInformationsystem
		->queryBuilder()
		->where('informationsystems.site_id', '=', $oSite->id);

	$oInformationsystem = $oInformationsystem->getByName($aInformationsystem['name'], FALSE);

	if (is_null($oInformationsystem))
	{
		$aExplodeDir = explode('/', $aInformationsystem['dirName']);

		$iParent_Id = 0;
		$iParent_Property_Id = 0;

		foreach ($aExplodeDir as $sDirName)
		{
			if($sDirName != '')
			{
				$oInformationsystem_Dir = Core_Entity::factory('Informationsystem_Dir');
				$oInformationsystem_Dir
					->queryBuilder()
					->where('informationsystem_dirs.parent_id', '=', $iParent_Id);

				$oInformationsystem_Dir = $oInformationsystem_Dir->getByName($sDirName, FALSE);

				if(is_null($oInformationsystem_Dir))
				{
					$oInformationsystem_Dir = Core_Entity::factory('Informationsystem_Dir');
					$oInformationsystem_Dir
						->parent_id($iParent_Id)
						->site_id($oSite->id)
						->name($sDirName)
						->save();
				}

				$iParent_Id = $oInformationsystem_Dir->id;
			}
		}

		$oInformationsystem = Core_Entity::factory('Informationsystem');
		$oInformationsystem
			->informationsystem_dir_id($iParent_Id)
			->structure_id($aInformationsystem['structure_id'])
			->site_id($oSite->id)
			->name($aInformationsystem['name'])
			->items_on_page($aInformationsystem['items_on_page'])
			->items_sorting_field($aInformationsystem['items_sorting_field'])
			->items_sorting_direction($aInformationsystem['items_sorting_direction'])
			->image_large_max_width($aInformationsystem['image_large_max_width'])
			->image_large_max_height($aInformationsystem['image_large_max_height'])
			->image_small_max_width($aInformationsystem['image_small_max_width'])
			->image_small_max_height($aInformationsystem['image_small_max_height'])
			->typograph_default_items($aInformationsystem['typograph_default_items'])
			->typograph_default_groups($aInformationsystem['typograph_default_groups'])
			->save();

		$iInformationsystem_Id = $oInformationsystem->id;

		$aAssosiatedInformationsystems[$iInformationsystemId] = $iInformationsystem_Id;

		$aReplace["'Informationsystem', {$iInformationsystemId}"] = "'Informationsystem', " . $iInformationsystem_Id;

		if (isset($aInformationsystem['informationsystem_groups']))
		{
			foreach ($aInformationsystem['informationsystem_groups'] as $iInformationsystemGroupId => $aInformationsystemGroup)
			{
				$oInformationsystem_Group = Core_Entity::factory('Informationsystem_Group');

				$oInformationsystem_Group->name = $aInformationsystemGroup['name'];
				$oInformationsystem_Group->indexing = $aInformationsystemGroup['indexing'];

				$oInformationsystem_Group
					->informationsystem_id($iInformationsystem_Id)
					->parent_id($aInformationsystemGroup['parent_id'])
					->description($aInformationsystemGroup['description'])
					->active($aInformationsystemGroup['active'])
					->path(Core_Str::transliteration($aInformationsystemGroup['name']))
					->save();
			}

			$aAssosiatedInformationsystemGroups[$iInformationsystemGroupId] = $oInformationsystem_Group->id;
		}

		if (isset($aInformationsystem['properties']))
		{
			foreach ($aInformationsystem['properties'] as $iPropertyId => $aProperty)
			{
				$aExplodeDir = explode('/', $aProperty['dirName']);
				array_unshift($aExplodeDir, $sSitePostfix);

				foreach ($aExplodeDir as $sDirName)
				{
					if($sDirName != '')
					{
						$oProperty_Dirs = Core_Entity::factory('Informationsystem_Item_Property_List', $iInformationsystem_Id)
							->Property_Dirs;

						$oProperty_Dirs
							->queryBuilder()
							->where('property_dirs.parent_id', '=', $iParent_Property_Id);

						$oProperty_Dir = $oProperty_Dirs->getByName($sDirName, FALSE);

						if(is_null($oProperty_Dir))
						{
							$oProperty_Dir = Core_Entity::factory('Property_Dir');
							$oProperty_Dir
								->parent_id($iParent_Property_Id)
								->name($sDirName)
								->save();
						}

						$iParent_Property_Id = $oProperty_Dir->id;
					}
				}

				$oProperty = Core_Entity::factory('Property');
				$oProperty
					->property_dir_id($iParent_Property_Id)
					//->list_id(isset($aProperty['list_id']) ? $aProperty['list_id'] : 0)
					->list_id(Core_Array::get($aProperty, 'list_id', 0))
					->informationsystem_id(isset($aProperty['informationsystem_id']) ? $aProperty['informationsystem_id'] : 0)
					->shop_id(0)
					->name($aProperty['name'])
					->description($aProperty['description'])
					->type($aPropertyTypes[$aProperty['type']])
					->default_value($aProperty['default_value'])
					->tag_name($aProperty['tag_name'])
					->sorting($aProperty['sorting'])
					->multiple($aProperty['multiple'])
					->save();

				$oInformationsystem_Item_Property_Dir = Core_Entity::factory('Informationsystem_Item_Property_Dir');
				$oInformationsystem_Item_Property_Dir
					->informationsystem_id($iInformationsystem_Id)
					->property_dir_id($iParent_Property_Id)
					->save();

				$oInformationsystem_Item_Property = Core_Entity::factory('Informationsystem_Item_Property');
				$oInformationsystem_Item_Property
					->informationsystem_id($iInformationsystem_Id)
					->property_id($oProperty->id)
					->save();

				$aAssosiatedProperties[$iPropertyId] = $oProperty->id;
			}
		}

		if (isset($aInformationsystem['informationsystem_items']))
		{
			foreach ($aInformationsystem['informationsystem_items'] as $iInformationsystemItemId => $aInformationsystemItem)
			{
				$oInformationsystem_Item = Core_Entity::factory('Informationsystem_Item');
				$oInformationsystem_Item
					->queryBuilder()
					->where('informationsystem_items.informationsystem_id', '=', $iInformationsystem_Id);

				$oInformationsystem_Item = $oInformationsystem_Item->getByName($aInformationsystemItem['name'], FALSE);

				if (is_null($oInformationsystem_Item))
				{
					$oInformationsystem_Item = Core_Entity::factory('Informationsystem_Item');

					$oInformationsystem_Item->name = $aInformationsystemItem['name'];
					$oInformationsystem_Item->indexing = $aInformationsystemItem['indexing'];

					$oInformationsystem_Item
						->informationsystem_id($iInformationsystem_Id)
						->informationsystem_group_id($aInformationsystemItem['informationsystem_group_id'] > 0 ? $aAssosiatedInformationsystemGroups[$aInformationsystemItem['informationsystem_group_id']] : 0)
						->shortcut_id(Core_Array::get($aInformationsystemItem, 'shortcut_id', 0))
						->active(Core_Array::get($aInformationsystemItem, 'active', 1))
						->sorting(Core_Array::get($aInformationsystemItem, 'sorting', 0))
						->description(Core_Array::get($aInformationsystemItem, 'description', ''))
						->text(Core_Array::get($aInformationsystemItem, 'text', ''))
						->seo_title(Core_Array::get($aInformationsystemItem, 'seo_title', ''))
						->seo_description(Core_Array::get($aInformationsystemItem, 'seo_description', ''))
						->seo_keywords(Core_Array::get($aInformationsystemItem, 'seo_keywords', ''))
						->path(Core_Str::transliteration(Core_Array::get($aInformationsystemItem, 'name', '')))
						->save();

						$Install_Controller->moveInformationsystemItemImage($oInformationsystem_Item->id, $sourceInformationsystem = $iInformationsystemId, $sourceInformationsystemItemId = $iInformationsystemItemId);

						$iInformationsystemItem_Id = $oInformationsystem_Item->id;

					//Tags
					if(!empty($aInformationsystemItem['tags']))
					{
						$aExplodeTags = explode(',', $aInformationsystemItem['tags']);

						foreach ($aExplodeTags as $sTagName)
						{
							$oTag = Core_Entity::factory('Tag')->getByName($sTagName, FALSE);

							if(is_null($oTag))
							{
								$oTag = Core_Entity::factory('Tag');
								$oTag
									->tag_dir_id(0)
									->name($sTagName)
									->path($sTagName)
									->save();
							}

							$oTag_Informationsystem_Item = Core_Entity::factory('Tag_Informationsystem_Item');
							$oTag_Informationsystem_Item
								->tag_id($oTag->id)
								->informationsystem_item_id($iInformationsystemItem_Id)
								->site_id($oSite->id)
								->save();
						}
					}

					if (isset($aInformationsystemItem['comments']))
					{
						foreach ($aInformationsystemItem['comments'] as $aComment)
						{
							$oComment = Core_Entity::factory('Comment');

							$oComment->author = $aComment['author'];
							$oComment->email = $aComment['email'];

							$oComment
								->parent_id($aComment['parent_id'])
								->subject($aComment['subject'])
								->text($aComment['text'])
								->phone($aComment['phone'])
								->active($aComment['active'])
								->grade($aComment['grade'])
								->siteuser_id(isset($aComment['siteuser_id'])
									? Core_Array::get($aAssosiatedSiteusers, $aComment['siteuser_id'], 0)
									: 0
								)
								->datetime(Core_Date::timestamp2sql(time()));

							$oInformationsystem_Item->add($oComment);
						}
					}

					if (isset($aInformationsystemItem['property_values']))
					{
						foreach ($aInformationsystemItem['property_values'] as $iPropertyValueId => $aPropertyValue)
						{
							$oProperty = Core_Entity::factory('Property')->getById($aAssosiatedProperties[$aPropertyValue['property_id']], FALSE);

							if (!is_null($oProperty))
							{
								$oProperty_Value = $oProperty->createNewValue($oInformationsystem_Item->id);

								switch ($oProperty->type)
								{
									case 0: // Int
									case 1: // String
									case 4: // Textarea
									case 6: // Wysiwyg
									case 7: // Checkbox
									case 8: // Date
									case 9: // Datetime
										$oProperty_Value
											->value($aPropertyValue['value'])
											->save();
									break;

									case 2: //File
										$Install_Controller->moveInformationsystemItemPropertyImage($oInformationsystem_Item->id, $oProperty->id, $iInformationsystemId, $iInformationsystemItemId, $iPropertyValueId);
									break;

									case 3: //List
										if (Core::moduleIsActive('list'))
										{
											$oProperty_Value
												->value($aAssosiatedListValues[$aPropertyValue['value']])
												->save();
										}
									break;
								}
							}
						}
					}
				}
			}
		}
	}
}

//Shop Currencies
foreach ($aShopCurrencies as $iShopCurrencyId => $aShopCurrency)
{
	$oShop_Currency = Core_Entity::factory('Shop_Currency')->getByCode($aShopCurrency['code'], FALSE);

	if (is_null($oShop_Currency))
	{
		$oShop_Currency = Core_Entity::factory('Shop_Currency');
		$oShop_Currency
			->name($aShopCurrency['name'])
			->code($aShopCurrency['code'])
			->exchange_rate($aShopCurrency['exchange_rate'])
			->default($aShopCurrency['default'])
			->sorting($aShopCurrency['sorting'])
			->save();
	}

	$aAssosiatedShopCurrencies[$iShopCurrencyId] = $oShop_Currency->id;
}

//Shops
foreach ($aShopi18n[$sLng] as $iShopId => $aShop)
{
	$oShop = Core_Entity::factory('Shop');
	$oShop
		->queryBuilder()
		->where('shops.site_id', '=', $oSite->id);

	$oShop = $oShop->getByName($aShop['name'], FALSE);

	if (is_null($oShop))
	{
		$aExplodeDir = explode('/', $aShop['dirName']);

		$iParent_Id = 0;
		$iParent_Property_Id = 0;

		foreach ($aExplodeDir as $sDirName)
		{
			if($sDirName != '')
			{
				$oShop_Dir = Core_Entity::factory('Shop_Dir');
				$oShop_Dir
					->queryBuilder()
					->where('shop_dirs.parent_id', '=', $iParent_Id);

				$oShop_Dir = $oShop_Dir->getByName($sDirName, FALSE);

				if(is_null($oShop_Dir))
				{
					$oShop_Dir = Core_Entity::factory('Shop_Dir');
					$oShop_Dir
						->parent_id($iParent_Id)
						->site_id($oSite->id)
						->name($sDirName)
						->save();
				}

				$iParent_Id = $oShop_Dir->id;
			}
		}

		$oShop = Core_Entity::factory('Shop');
		$oShop
			->shop_dir_id($iParent_Id)
			->shop_company_id($aShop['shop_company_id'])
			->site_id($oSite->id)
			->name($aShop['name'])
			->image_large_max_width($aShop['image_large_max_width'])
			->image_large_max_height($aShop['image_large_max_height'])
			->image_small_max_width($aShop['image_small_max_width'])
			->image_small_max_height($aShop['image_small_max_height'])
			->structure_id($aShop['structure_id'])
			->shop_country_id($aShop['shop_country_id'])
			->shop_currency_id($aAssosiatedShopCurrencies[$aShop['shop_currency_id']])
			->email($aShop['email'])
			->items_on_page($aShop['items_on_page'])
			->send_order_email_admin($aShop['send_order_email_admin'])
			->send_order_email_user($aShop['send_order_email_user'])
			->items_sorting_field($aShop['items_sorting_field'])
			->items_sorting_direction($aShop['items_sorting_direction'])
			->comment_active($aShop['comment_active'])
			->typograph_default_items($aShop['typograph_default_items'])
			->typograph_default_groups($aShop['typograph_default_groups'])
			->apply_tags_automatically($aShop['apply_tags_automatically'])
			->write_off_paid_items($aShop['write_off_paid_items'])
			->change_filename($aShop['change_filename'])
			->attach_digital_items($aShop['attach_digital_items'])
			->use_captcha($aShop['use_captcha'])
			->save();

		$iShop_Id = $oShop->id;

		$aAssosiatedShops[$iShopId] = $iShop_Id;

		$aReplace["'Shop', {$iShopId}"] = "'Shop', " . $iShop_Id;

		//Shop_Discounts
		foreach ($aShopDiscounti18n[$sLng] as $iShopDiscountId => $aShopDiscount)
		{
			$oShop_Discount = Core_Entity::factory('Shop_Discount');

			$oShop_Discount
			->queryBuilder()
			->where('shop_discounts.shop_id', '=', $iShop_Id);

			$oShop_Discount = $oShop_Discount->getByName($aShopDiscount['name'], FALSE);

			if(is_null($oShop_Discount))
			{
				$oShop_Discount = Core_Entity::factory('Shop_Discount');
				$oShop_Discount
					->shop_id($aAssosiatedShops[$aShopDiscount['shop_id']])
					->name($aShopDiscount['name'])
					->active($aShopDiscount['active'])
					->type($aShopDiscount['type'])
					->value($aShopDiscount['value'])
					->start_datetime($aShopDiscount['start_datetime'])
					->end_datetime($aShopDiscount['end_datetime'])
					->save();

				$aAssosiatedShopDiscounts[$iShopDiscountId] = $oShop_Discount->id;
			}
		}

		//Shop groups
		if (isset($aShop['shop_groups']))
		{
			foreach ($aShop['shop_groups'] as $iShopGroupId => $aShopGroup)
			{
				$oShop_Group = Core_Entity::factory('Shop_Group');

				$oShop_Group->name = $aShopGroup['name'];
				$oShop_Group->indexing = $aShopGroup['indexing'];

				$oShop_Group
					->shop_id($iShop_Id)
					->parent_id($aShopGroup['parent_id'] > 0 ? $aAssosiatedShopGroups[$aShopGroup['parent_id']] : 0)
					->description($aShopGroup['description'])
					->active($aShopGroup['active'])
					->path(Core_Str::transliteration($aShopGroup['name']))
					->save();

				$aAssosiatedShopGroups[$iShopGroupId] = $oShop_Group->id;
			}
		}

		//Shop item properties
		if (isset($aShop['properties']))
		{
			foreach ($aShop['properties'] as $iPropertyId => $aProperty)
			{
				$aExplodeDir = explode('/', $aProperty['dirName']);

				foreach ($aExplodeDir as $sDirName)
				{
					if($sDirName != '')
					{
						$oProperty_Dir = Core_Entity::factory('Property_Dir');
						$oProperty_Dir
							->queryBuilder()
							->where('property_dirs.parent_id', '=', $iParent_Property_Id);

						$oProperty_Dir = $oProperty_Dir->getByName($sDirName, FALSE);

						if(is_null($oProperty_Dir))
						{
							$oProperty_Dir = Core_Entity::factory('Property_Dir');
							$oProperty_Dir
								->parent_id($iParent_Property_Id)
								->name($sDirName)
								->save();
						}

						$iParent_Property_Id = $oProperty_Dir->id;

						$oShop_Item_Property_Dir = Core_Entity::factory('Shop_Item_Property_Dir');
						$oShop_Item_Property_Dir
							->shop_id($iShop_Id)
							->property_dir_id($iParent_Property_Id)
							->save();
					}
				}

				$oProperty = Core_Entity::factory('Property');
				$oProperty
					->property_dir_id($iParent_Property_Id)
					->list_id(isset($aProperty['list_id']) && isset($aAssosiatedLists[$aProperty['list_id']]) ? $aAssosiatedLists[$aProperty['list_id']] : 0)
					->informationsystem_id(0)
					->shop_id(isset($aProperty['shop_id']) ? $aProperty['shop_id'] : 0)
					->name($aProperty['name'])
					->description($aProperty['description'])
					->type($aPropertyTypes[$aProperty['type']])
					->default_value($aProperty['default_value'])
					->tag_name($aProperty['tag_name'])
					->sorting($aProperty['sorting'])
					->multiple($aProperty['multiple'])
					->image_large_max_width(isset($aProperty['image_large_max_width']) ? $aProperty['image_large_max_width'] : 300)
					->image_large_max_height(isset($aProperty['image_large_max_height']) ? $aProperty['image_large_max_height'] : 300)
					->image_small_max_width(isset($aProperty['image_large_max_width']) ? $aProperty['image_large_max_width'] : 70)
					->image_small_max_height(isset($aProperty['image_large_max_width']) ? $aProperty['image_large_max_width'] : 70)
					->save();

				$oShop_Item_Property = Core_Entity::factory('Shop_Item_Property');
				$oShop_Item_Property
					->shop_id($iShop_Id)
					->property_id($oProperty->id)
					->filter($aProperty['filter'])
					->show_in_group($aProperty['show_in_group'])
					->show_in_item($aProperty['show_in_item'])
					->save();

				$aAssosiatedProperties[$iPropertyId] = $oProperty->id;

				$aExplodeShopGroupId = explode('/', $aProperty['shop_group_id']);
				foreach ($aExplodeShopGroupId as $iPropertyShopGroupId)
				{
					$oShop_Item_Property_For_Group = Core_Entity::factory('Shop_Item_Property_For_Group');
					$oShop_Item_Property_For_Group
						->shop_group_id($iPropertyShopGroupId > 0 ? $aAssosiatedShopGroups[$iPropertyShopGroupId] : 0)
						->shop_item_property_id($oShop_Item_Property->id)
						->shop_id($iShop_Id)
						->save();
				}
			}
		}

		//Shop Warehouses
		foreach ($aShopWarehouses as $iShopWarehouseId => $aShopWarehouse)
		{
			$oShop_Warehouse = Core_Entity::factory('Shop_Warehouse');
			$oShop_Warehouse
				->queryBuilder()
				->where('shop_warehouses.shop_id', '=', $iShop_Id);

			$oShop_Warehouse = $oShop_Warehouse->getByName($aShopWarehouse['name'], FALSE);

			if (is_null($oShop_Warehouse))
			{
				$oShop_Warehouse = Core_Entity::factory('Shop_Warehouse');
				$oShop_Warehouse
					->shop_id($iShop_Id)
					->name($aShopWarehouse['name'])
					->sorting($aShopWarehouse['sorting'])
					->active($aShopWarehouse['active'])
					->default($aShopWarehouse['default'])
					->guid(Core_Guid::get())
					->save();
			}

			$aAssosiatedShopWarehouses[$iShopWarehouseId] = $oShop_Warehouse->id;
		}

		//Shop Producers
		foreach ($aShopProducers as $iShopProducerId => $aShopProducer)
		{
			$oShop_Producer = Core_Entity::factory('Shop_Producer');
			$oShop_Producer
				->queryBuilder()
				->where('shop_producers.shop_id', '=', $iShop_Id);

			$oShop_Producer = $oShop_Producer->getByName($aShopProducer['name'], FALSE);

			if (is_null($oShop_Producer))
			{
				$oShop_Producer = Core_Entity::factory('Shop_Producer');
				$oShop_Producer
					->shop_id($iShop_Id)
					->name($aShopProducer['name'])
					->active($aShopWarehouse['active'])
					->sorting($aShopProducer['sorting'])
					->path(Core_Str::transliteration($aShopProducer['name']))
					->save();
			}

			$aAssosiatedShopProducers[$iShopProducerId] = $oShop_Producer->id;
		}

		//Shop items
		if (isset($aShop['shop_items']))
		{
			foreach ($aShop['shop_items'] as $iShopItemId => $aShopItem)
			{
				$oShop_Item = Core_Entity::factory('Shop_Item');

				$oShop_Item->name = $aShopItem['name'];
				$oShop_Item->indexing = $aShopItem['indexing'];

				$oShop_Item
					->shop_id($iShop_Id)
					->shortcut_id($aShopItem['shortcut_id'])
					->shop_tax_id($aShopItem['shop_tax_id'])
					->shop_seller_id($aShopItem['shop_seller_id'])
					->shop_currency_id($aAssosiatedShopCurrencies[$aShopItem['shop_currency_id']])
					->shop_producer_id(isset($aAssosiatedShopProducers[$aShopItem['shop_producer_id']]) ? $aAssosiatedShopProducers[$aShopItem['shop_producer_id']] : 0)
					->shop_measure_id($aShopItem['shop_measure_id'])
					->shop_group_id($aAssosiatedShopGroups[$aShopItem['shop_group_id']])
					->type($aShopItemTypes[$aShopItem['type']])
					->marking($aShopItem['marking'])
					->description($aShopItem['description'])
					->text($aShopItem['text'])
					->marking($aShopItem['marking'])
					->active($aShopItem['active'])
					->price($aShopItem['price'])
					->modification_id($aShopItem['modification_id'])
					->path(Core_Str::transliteration($aShopItem['name']))
					->save();

				$Install_Controller->moveShopItemImage($oShop_Item->id, $sourceShopId = $iShopId, $sourceShopItemId = $iShopItemId);

				//Tags
				if(!empty($aShopItem['tags']))
				{
					$aExplodeTags = explode(',', $aShopItem['tags']);

					foreach ($aExplodeTags as $sTagName)
					{
						$oTag = Core_Entity::factory('Tag')->getByName($sTagName, FALSE);

						if(is_null($oTag))
						{
							$oTag = Core_Entity::factory('Tag');
							$oTag
								->tag_dir_id(0)
								->name($sTagName)
								->path($sTagName)
								->save();
						}

						$oTag_Shop_Item = Core_Entity::factory('Tag_Shop_Item');
						$oTag_Shop_Item
							->tag_id($oTag->id)
							->shop_item_id($oShop_Item->id)
							->site_id($oSite->id)
							->save();
					}
				}

				if (isset($aShopItem['comments']))
				{
					foreach ($aShopItem['comments'] as $aShopComment)
					{
						$oComment = Core_Entity::factory('Comment');

						$oComment->author = $aShopComment['author'];
						$oComment->email = $aShopComment['email'];

						$oComment
							->parent_id($aShopComment['parent_id'])
							->subject($aShopComment['subject'])
							->text($aShopComment['text'])
							->phone($aShopComment['phone'])
							->active($aShopComment['active'])
							->grade($aShopComment['grade'])
							->siteuser_id(isset($aShopComment['siteuser_id'])
								? Core_Array::get($aAssosiatedSiteusers, $aShopComment['siteuser_id'], 0)
								: 0
							)
							->datetime(Core_Date::timestamp2sql(time()));

						$oShop_Item->add($oComment);
					}
				}

				if (isset($aShopItem['property_values']))
				{
					foreach ($aShopItem['property_values'] as $iPropertyValueId => $aPropertyValue)
					{
						$oProperty = Core_Entity::factory('Property')->getById($aAssosiatedProperties[$aPropertyValue['property_id']], FALSE);

						if (!is_null($oProperty))
						{
							$oProperty_Value = $oProperty->createNewValue($oShop_Item->id);

							switch ($oProperty->type)
							{
								case 0: // Int
								case 1: // String
								case 4: // Textarea
								case 6: // Wysiwyg
								case 7: // Checkbox
								case 8: // Date
								case 9: // Datetime
									$oProperty_Value
										->value($aPropertyValue['value'])
										->save();
								break;

								case 2: //File
									$Install_Controller->moveShopItemPropertyImage($oShop_Item->id, $oProperty->id, $iShopId, $iShopItemId, $iPropertyValueId);
								break;

								case 3: //List
									if (Core::moduleIsActive('list'))
									{
										$oProperty_Value
											->value($aAssosiatedListValues[$aPropertyValue['value']])
											->save();
									}
								break;
							}
						}
					}
				}

				if (isset($aShopItem['shop_discount_id']))
				{
					$oShop_Item_Discount = Core_Entity::factory('Shop_Item_Discount');
					$oShop_Item_Discount
						->shop_item_id($oShop_Item->id)
						->shop_discount_id($aAssosiatedShopDiscounts[$aShopItem['shop_discount_id']])
						->save();
				}

				// Остатки на складах
				$aShop_Warehouses = $oShop->Shop_Warehouses->findAll(FALSE);
				foreach ($aShop_Warehouses as $oShop_Warehouse)
				{
					$oShop_Warehouse_Item = Core_Entity::factory('Shop_Warehouse_Item');
					$oShop_Warehouse_Item
						->shop_warehouse_id($oShop_Warehouse->id)
						->shop_item_id($oShop_Item->id)
						->count(999)
						->save();
				}
			}
		}
	}
}

//Forums
if (Core::moduleIsActive('forum'))
{
	foreach ($aForumi18n[$sLng] as $iForumId => $aForum)
	{
		$oForum = Core_Entity::factory('Forum');
		$oForum
			->queryBuilder()
			->where('forums.site_id', '=', $oSite->id);

		$oForum = $oForum->getByName($aForum['name'], FALSE);

		if (is_null($oForum))
		{
			$oForum = Core_Entity::factory('Forum');

			$oForum
				->structure_id($aForum['structure_id'])
				->site_id($oSite->id)
				->name($aForum['name'])
				->description($aForum['description'])
				->topics_on_page($aForum['topics_on_page'])
				->posts_on_page($aForum['posts_on_page'])
				->flood_protection_time($aForum['flood_protection_time'])
				->allow_edit_time($aForum['allow_edit_time'])
				->allow_delete_time($aForum['allow_delete_time'])
				->save();

			$aAssosiatedForums[$iForumId] = $oForum->id;

			if (isset($aForum['forum_groups']))
			{
				foreach ($aForum['forum_groups'] as $iForumGroupId => $aForumGroup)
				{
					$oForum_Group = Core_Entity::factory('Forum_Group');
					$oForum_Group
						->queryBuilder()
						->where('forum_groups.forum_id', '=', $oForum->id);

					$oForum_Group = $oForum_Group->getByName($aForumGroup['name'], FALSE);

					if (is_null($oForum_Group))
					{
						$oForum_Group = Core_Entity::factory('Forum_Group');
						$oForum_Group
							->name($aForumGroup['name'])
							->description($aForumGroup['description'])
							->sorting($aForumGroup['sorting'])
							->forum_id($oForum->id)
							->save();

						if (isset($aForumGroup['forum_categories']))
						{
							foreach ($aForumGroup['forum_categories'] as $iForumCategoryId => $aForumCategory)
							{
								$oForum_Category = Core_Entity::factory('Forum_Category');
								$oForum_Category
									->queryBuilder()
									->where('forum_categories.forum_group_id', '=', $oForum_Group->id);

								$oForum_Category = $oForum_Category->getByName($aForumCategory['name'], FALSE);

								if (is_null($oForum_Category))
								{
									$oForum_Category = Core_Entity::factory('Forum_Category');
									$oForum_Category
										->forum_group_id($oForum_Group->id)
										->name($aForumCategory['name'])
										->description($aForumCategory['description'])
										->closed($aForumCategory['closed'])
										->sorting($aForumCategory['sorting'])
										->email($aForumCategory['email'])
										->postmoderation($aForumCategory['postmoderation'])
										->visible($aForumCategory['visible'])
										->use_captcha($aForumCategory['use_captcha'])
										->allow_guest_posting($aForumCategory['allow_guest_posting'])
										->save();

									if (isset($aForumCategory['forum_topics']))
									{
										foreach ($aForumCategory['forum_topics'] as $iForumTopicId => $aForumTopic)
										{
											$oForum_Topic = Core_Entity::factory('Forum_Topic');
											$oForum_Topic
												->forum_category_id($oForum_Category->id)
												->visible($aForumTopic['visible'])
												->announcement($aForumTopic['announcement'])
												->closed($aForumTopic['closed'])
												->save();
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}

//Helpdesks
if (Core::moduleIsActive('helpdesk'))
{
	foreach ($aHelpdeski18n[$sLng] as $iHelpdekId => $aHelpdesk)
	{
		$oHelpdesk = Core_Entity::factory('Helpdesk');
		$oHelpdesk
			->queryBuilder()
			->where('helpdesks.site_id', '=', $oSite->id);

		$oHelpdesk = $oHelpdesk->getByName($aHelpdesk['name'], FALSE);

		if (is_null($oHelpdesk))
		{
			$oHelpdesk = Core_Entity::factory('Helpdesk');

			$oHelpdesk
				->structure_id($aHelpdesk['structure_id'])
				->site_id($oSite->id)
				->name($aHelpdesk['name'])
				->notify($aHelpdesk['notify'])
				->notify_change_criticality_level($aHelpdesk['notify_change_criticality_level'])
				->delete_attach_in_days($aHelpdesk['delete_attach_in_days'])
				->save();

			$aAssosiatedHelpdesks[$iHelpdekId] = $oHelpdesk->id;
		}
	}
}

//Advertisements
if (Core::moduleIsActive('advertisement'))
{
	foreach ($aAdvertisementGroupi18n[$sLng] as $iAdvGroupId => $aAdvertisementGroups)
	{
		$oAdvertisement_Group = Core_Entity::factory('Advertisement_Group');
		$oAdvertisement_Group
			->queryBuilder()
			->where('advertisement_groups.site_id', '=', $oSite->id);

		$oAdvertisement_Group = $oAdvertisement_Group->getByName($aAdvertisementGroups['name'], FALSE);

		if (is_null($oAdvertisement_Group))
		{
			$oAdvertisement_Group = Core_Entity::factory('Advertisement_Group');

			$oAdvertisement_Group
				->name($aAdvertisementGroups['name'])
				->description($aAdvertisementGroups['description'])
				->site_id($oSite->id)
				->save();

			$aReplace["'Advertisement_Group', {$iAdvGroupId}"] = "'Advertisement_Group', " . $oAdvertisement_Group->id;

			if (isset($aAdvertisementGroups['advertisement']))
			{
				foreach ($aAdvertisementGroups['advertisement'] as $iAdvId => $aAdvertisements)
				{
					$oAdvertisement = Core_Entity::factory('Advertisement');
					$oAdvertisement
						->queryBuilder()
						->where('advertisements.site_id', '=', $oSite->id);

					$oAdvertisement = $oAdvertisement->getByName($aAdvertisements['name'], FALSE);

					if (is_null($oAdvertisement))
					{
						$oAdvertisement = Core_Entity::factory('Advertisement');

						$oAdvertisement
							->name($aAdvertisements['name'] . "-" . $oSite->id)
							->description($aAdvertisements['description'])
							->type($aAdvertisements['type'])
							->site_id($oSite->id)
							->href($aAdvertisements['href'])
							->start_datetime(Core_Date::timestamp2sql(time()))
							->end_datetime(Core_Date::timestamp2sql(strtotime("+1 year", time())))
							->save();

						$oAdvertisement->saveFile($tmpDir . "tmp/upload/img/" . $aAdvertisements['file_name'], $aAdvertisements['file_name']);

						$oAdvertisement_Group_List = Core_Entity::factory('Advertisement_Group_List');
						$oAdvertisement_Group_List
							->advertisement_group_id($oAdvertisement_Group->id)
							->advertisement_id($oAdvertisement->id)
							->probability(100)
							->save();
					}
				}
			}
		}
	}
}

//Templates
foreach ($aTemplatei18n[$sLng] as $iTemplateId => $aTemplate)
{
	$oTemplate = Core_Entity::factory('Template')->getByName($aTemplate['name'] . $sSitePostfix, FALSE);

	if (is_null($oTemplate))
	{
		$aExplodeDir = explode('/', $aTemplate['dirName']);
		array_unshift($aExplodeDir, $sSitePostfix);

		$iParent_Id = 0;

		foreach ($aExplodeDir as $sDirName)
		{
			if($sDirName != '')
			{
				$oTemplate_Dir = Core_Entity::factory('Template_Dir');
				$oTemplate_Dir
					->queryBuilder()
					->where('template_dirs.parent_id', '=', $iParent_Id);

				$oTemplate_Dir = $oTemplate_Dir->getByName($sDirName, FALSE);

				if (is_null($oTemplate_Dir))
				{
					$oTemplate_Dir = Core_Entity::factory('Template_Dir');
					$oTemplate_Dir
						->parent_id($iParent_Id)
						->name($sDirName)
						->site_id($oSite->id)
						->save();
				}

				$iParent_Id = $oTemplate_Dir->id;
			}
		}

		$oTemplate = Core_Entity::factory('Template');
		$oTemplate
			->name($aTemplate['name'] . $sSitePostfix)
			->template_id($aTemplate['parent_template_id'] > 0
				? $aAssosiatedTemplates[$aTemplate['parent_template_id']]
				: 0
			)
			->template_dir_id($iParent_Id)
			->site_id($oSite->id)
			->save();

		$oTemplate->saveTemplateFile($Install_Controller->loadFile($tmpDir . "tmp/templates/template" . $iTemplateId . "/template.htm", $aReplace));
		$oTemplate->saveTemplateCssFile($Install_Controller->loadFile($tmpDir . "tmp/templates/template" . $iTemplateId . "/style.css", $aReplace));

		$iTemplate_Id = $oTemplate->id;

		$aAssosiatedTemplates[$iTemplateId] = $iTemplate_Id;
	}
}

//Documents
foreach ($aDoci18n[$sLng] as $iDocId => $aDocument)
{
	$oDocument = Core_Entity::factory('Document')->getByName($aDocument['name'] . $sSitePostfix, FALSE);

	if (is_null($oDocument))
	{
		$aExplodeDir = explode('/', $aDocument['dirName']);
		array_unshift($aExplodeDir, $sSitePostfix);

		$iParent_Id = 0;
		foreach ($aExplodeDir as $sDirName)
		{
			if($sDirName != '')
			{
				$oDocument_Dir = Core_Entity::factory('Document_Dir');
				$oDocument_Dir
					->queryBuilder()
					->where('document_dirs.parent_id', '=', $iParent_Id);

				$oDocument_Dir = $oDocument_Dir->getByName($sDirName, FALSE);

				if (is_null($oDocument_Dir))
				{
					$oDocument_Dir = Core_Entity::factory('Document_Dir');
					$oDocument_Dir
						->parent_id($iParent_Id)
						->site_id($oSite->id)
						->name($sDirName)
						->save();
				}

				$iParent_Id = $oDocument_Dir->id;
			}
		}

		$oDocument = Core_Entity::factory('Document');
		$oDocument
			->name($aDocument['name'] . $sSitePostfix)
			->document_dir_id($iParent_Id)
			->site_id($oSite->id)
			->save();

		$iDocument_Id = $oDocument->id;

		$aAssosiatedDocuments[$iDocId] = $iDocument_Id;

		if (isset($aDocument['versions']))
		{
			foreach ($aDocument['versions'] as $iDocumentVersionId => $aDocumentVersion)
			{
				$oDocument_Version = Core_Entity::factory('Document_Version');
				$oDocument_Version
					->document_id($iDocument_Id)
					->datetime($aDocumentVersion['datetime'])
					->current($aDocumentVersion['current'])
					->template_id($aAssosiatedTemplates[$aDocumentVersion['template_id']])
					->save();

				$oDocument_Version->saveFile($Install_Controller->loadFile($tmpDir . "tmp/hostcmsfiles/documents/documents" . $iDocumentVersionId . ".html", $aReplace));
			}
		}
	}
}

//Structures
foreach ($aStructurei18n[$sLng] as $iStructureId => $aStructure)
{
	$oStructure = Core_Entity::factory('Structure');
	$oStructure
		->queryBuilder()
		->where('structures.site_id', '=', $oSite->id);

	$oStructure = $oStructure->getByName($aStructure['name'], FALSE);

	if (is_null($oStructure))
	{
		$oStructure = Core_Entity::factory('Structure');

		$oStructure->name = $aStructure['name'];
		$oStructure->indexing = $aStructure['indexing'];
		$oStructure->type = $aStructureTypes[$aStructure['type']];
		$oStructure->active = $aStructure['active'];
		$oStructure->path = $aStructure['path'] != ''
			? $aStructure['path']
			: Core_Str::transliteration($aStructure['name']);

		$oStructure
			->structure_menu_id($aAssosiatedStructureMenus[$aStructure['structure_menu_id']])
			->template_id($aAssosiatedTemplates[$aStructure['template_id']])
			->site_id($oSite->id)
			->document_id(isset($aStructure['document_id']) ? $aAssosiatedDocuments[$aStructure['document_id']] : 0)
			->lib_id(isset($aStructure['lib_id']) ? $aAssosiatedLibs[$aStructure['lib_id']] : 0)
			->parent_id($aStructure['parent_id'] > 0 ? $aAssosiatedStructures[$aStructure['parent_id']] : 0)
			->show($aStructure['show'])
			->sorting($aStructure['sorting'])
			->seo_title($aStructure['seo_title'])
			->seo_description($aStructure['seo_description'])
			->seo_keywords($aStructure['seo_keywords'])
			->save();

		$aAssosiatedStructures[$iStructureId] = $oStructure->id;

		// .dat для узла структуры
		if (isset($aStructure['lib_id']) && $aStructure['lib_id'] > 0)
		{
			$file = $tmpDir . "tmp/hostcmsfiles/lib/lib_" . $aStructure['lib_id'] . "/lib_values_" . $iStructureId . ".dat";

			if (file_exists($file))
			{
				$values = unserialize($Install_Controller->loadFile($file));

				$oLib = Core_Entity::factory('Lib', $aAssosiatedLibs[$aStructure['lib_id']]);
				$oLib->saveDatFile($values, $oStructure->id);

				// Подмена значений в .dat для нового сайта
				$aTmpDat = $oLib->getDat($oStructure->id);

				if(is_array($aTmpDat))
				{
					foreach ($aTmpDat as $key => $value)
					{
						switch ($key)
						{
							case 'informationsystemId':
								$aTmpDat[$key] = $aAssosiatedInformationsystems[$value];
							break;
							case 'shopId':
								$aTmpDat[$key] = $aAssosiatedShops[$value];
							break;
							case 'formId':
								if (Core::moduleIsActive('form'))
								{
									$aTmpDat[$key] = $aAssosiatedForms[$value];
								}
							break;
							case 'helpdeskId':
								if (Core::moduleIsActive('helpdesk'))
								{
									$aTmpDat[$key] = $aAssosiatedHelpdesks[$value];
								}
							break;
							case 'forum_id':
								if (Core::moduleIsActive('forum'))
								{
									$aTmpDat[$key] = $aAssosiatedForums[$value];
								}
							break;
							case 'pollGroupId':
								if (Core::moduleIsActive('poll'))
								{
									$aTmpDat[$key] = $aAssosiatedPollGroups[$value];
								}
							break;
							default:
								if (strpos($key, 'Xsl') !== FALSE)
								{
									$aTmpDat[$key] = $aAssosiatedXslNames[$value];
								}
							break;
						}
					}

					$oLib->saveDatFile($aTmpDat, $oStructure->id);
				}
			}
		}
	}
}

// Подмена ID узлов структуры
if (Core::moduleIsActive('poll'))
{
	$aTmpPollGroups = $oSite->Poll_Groups->findAll(FALSE);
	foreach ($aTmpPollGroups as $oPollGroup)
	{
		$oPollGroup->structure_id = $aAssosiatedStructures[$oPollGroup->structure_id];
		$oPollGroup->save();
	}
}

$aTmpInformationsystems = $oSite->Informationsystems->findAll(FALSE);
foreach ($aTmpInformationsystems as $oInformationsystem)
{
	$oInformationsystem->structure_id = $aAssosiatedStructures[$oInformationsystem->structure_id];
	$oInformationsystem->save();
}

$aTmpShops = $oSite->Shops->findAll(FALSE);
foreach ($aTmpShops as $oShop)
{
	$oShop->structure_id = $aAssosiatedStructures[$oShop->structure_id];
	$oShop->save();
}

if (Core::moduleIsActive('forum'))
{
	$aTmpForums = $oSite->Forums->findAll(FALSE);
	foreach ($aTmpForums as $oForum)
	{
		$oForum->structure_id = $aAssosiatedStructures[$oForum->structure_id];
		$oForum->save();
	}
}

if (Core::moduleIsActive('helpdesk'))
{
	$aTmpHelpdesks = $oSite->Helpdesks->findAll(FALSE);
	foreach ($aTmpHelpdesks as $oHelpdesk)
	{
		$oHelpdesk->structure_id = $aAssosiatedStructures[$oHelpdesk->structure_id];
		$oHelpdesk->save();
	}
}

//echo "OK";