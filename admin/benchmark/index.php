<?php
/**
 * Benchmark.
 *
 * @package HostCMS
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
require_once('../../bootstrap.php');

Core_Auth::authorization($sModule = 'benchmark');

// Код формы
$iAdmin_Form_Id = 196;
$sAdminFormAction = '/admin/benchmark/index.php';

$oAdmin_Form = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id);

// Контроллер формы
$oAdmin_Form_Controller = Admin_Form_Controller::create($oAdmin_Form);
$oAdmin_Form_Controller
	->module(Core_Module::factory($sModule))
	->setUp()
	->path($sAdminFormAction)
	->title(Core::_('Benchmark.title'))
	->pageTitle(Core::_('Benchmark.title'));

// Меню формы
$oAdmin_Form_Entity_Menus = Admin_Form_Entity::factory('Menus');

// Элементы меню
$oAdmin_Form_Entity_Menus->add(
	Admin_Form_Entity::factory('Menu')
		->name(Core::_('Benchmark.menu'))
		->icon('fa fa-trophy')
		->add(
			Admin_Form_Entity::factory('Menu')
				->name(Core::_('Benchmark.menu_rate'))
				->icon('fa fa-rocket')
				->img('/admin/images/ip_add.gif')
				->href(
					$oAdmin_Form_Controller->getAdminActionLoadHref($oAdmin_Form_Controller->getPath(), 'check', NULL, 0, 0)
				)
				->onclick(
					$oAdmin_Form_Controller->getAdminActionLoadAjax($oAdmin_Form_Controller->getPath(), 'check', NULL, 0, 0)
				)
		)
		->add(
			Admin_Form_Entity::factory('Menu')
				->name(Core::_('Benchmark.menu_site_speed'))
				->icon('fa fa-rocket')
				->img('/admin/images/ip_add.gif')
				->href(
					$oAdmin_Form_Controller->getAdminLoadHref('/admin/benchmark/url/index.php', NULL, NULL, '')
				)
				->onclick(
					$oAdmin_Form_Controller->getAdminLoadAjax('/admin/benchmark/url/index.php', NULL, NULL, '')
				)
		)
);

// Добавляем все меню контроллеру
$oAdmin_Form_Controller->addEntity($oAdmin_Form_Entity_Menus);

// Элементы строки навигации
$oAdmin_Form_Entity_Breadcrumbs = Admin_Form_Entity::factory('Breadcrumbs');

// Элементы строки навигации
$oAdmin_Form_Entity_Breadcrumbs->add(
	Admin_Form_Entity::factory('Breadcrumb')
		->name(Core::_('Benchmark.title'))
		->href(
			$oAdmin_Form_Controller->getAdminLoadHref($oAdmin_Form_Controller->getPath(), NULL, NULL, '')
		)
		->onclick(
			$oAdmin_Form_Controller->getAdminLoadAjax($oAdmin_Form_Controller->getPath(), NULL, NULL, '')
	)
);

// Добавляем все хлебные крошки контроллеру
$oAdmin_Form_Controller->addEntity($oAdmin_Form_Entity_Breadcrumbs);

// Действие редактирования
$oAdmin_Form_Action = Core_Entity::factory('Admin_Form', $iAdmin_Form_Id)
	->Admin_Form_Actions
	->getByName('check');

if ($oAdmin_Form_Action && $oAdmin_Form_Controller->getAction() == 'check')
{
	$oBenchmark_Controller_Check = Admin_Form_Action_Controller::factory('Benchmark_Controller_Check', $oAdmin_Form_Action);

	// Добавляем типовой контроллер редактирования контроллеру формы
	$oAdmin_Form_Controller->addAction($oBenchmark_Controller_Check);
}

// Источник данных 1
$oAdmin_Form_Dataset = new Admin_Form_Dataset_Entity(
	Core_Entity::factory('Benchmark')
);

// Ограничение по сайту
$oAdmin_Form_Dataset->addCondition(
	array('where' =>
		array('site_id', '=', CURRENT_SITE)
	)
);

// Добавляем источник данных контроллеру формы
$oAdmin_Form_Controller->addDataset(
	$oAdmin_Form_Dataset
);

$oBenchmark = Core_Entity::factory('Benchmark');
$oBenchmark
	->queryBuilder()
	->where('benchmarks.site_id', '=', CURRENT_SITE)
	->limit(1)
	->clearOrderBy()
	->orderBy('benchmarks.id', 'DESC');

$aBenchmarks = $oBenchmark->findAll(FALSE);

if(count($aBenchmarks))
{
	$oBenchmark = $aBenchmarks[0];

	$iBenchmark = $oBenchmark->getBenchmark(); //Общая оценка производительности сайта

	$aColors = array('gray', 'danger', 'orange', 'warning', 'success');
	$sColor = $aColors[ceil($iBenchmark / 25)];

	ob_start();
	?>
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="databox radius-bordered databox-shadowed databox-graded databox-vertical">
				<div class="databox-top no-padding ">
					<div class="databox-row">
						<div class="databox-cell cell-12 text-align-center bg-<?php echo $sColor?>">
							<span class="databox-number benchmark-databox-number"><?php echo $iBenchmark?> / 100</span>
							<span class="databox-text"><?php echo Core::_('Benchmark.menu')?></span>
						</div>
					</div>
				</div>
				<div class="databox-bottom">
					<span class="databox-text"><?php echo Core::_('Benchmark.benchmark')?></span>
					<div class="progress progress-sm">
						<div class="progress-bar progress-bar-<?php echo $sColor?>" role="progressbar" aria-valuenow="<?php echo $iBenchmark?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $iBenchmark?>%">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="databox radius-bordered databox-shadowed databox-vertical">
				<div class="databox-top <?php echo $oBenchmark->mysql_write < $oBenchmark->etalon_mysql_write ? 'bg-orange' : 'bg-palegreen'?> no-padding">
					<div class="databox-row row-2"></div>
					<div class="databox-row row-10">
						<div class="databox-sparkline benchmark-databox-sparkline">
							<span><?php echo Core::_('Benchmark.bd_write')?></span>
						</div>
					</div>
				</div>
				<div class="databox-bottom no-padding bg-white">
					<div class="databox-row">
						<div class="databox-cell cell-6 text-align-center bordered-right bordered-platinum">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo $oBenchmark->mysql_write?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.server')?></span>
						</div>
						<div class="databox-cell cell-6 text-align-center">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo $oBenchmark->etalon_mysql_write?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.etalon')?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="databox radius-bordered databox-shadowed databox-vertical">
				<div class="databox-top <?php echo $oBenchmark->mysql_read < $oBenchmark->etalon_mysql_read ? 'bg-orange' : 'bg-palegreen'?> no-padding">
					<div class="databox-row row-2"></div>
					<div class="databox-row row-10">
						<div class="databox-sparkline benchmark-databox-sparkline">
							<span><?php echo Core::_('Benchmark.bd_read')?></span>
						</div>
					</div>
				</div>
				<div class="databox-bottom no-padding bg-white">
					<div class="databox-row">
						<div class="databox-cell cell-6 text-align-center bordered-right bordered-platinum">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo $oBenchmark->mysql_read?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.server')?></span>
						</div>
						<div class="databox-cell cell-6 text-align-center">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo $oBenchmark->etalon_mysql_read?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.etalon')?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="databox radius-bordered databox-shadowed databox-vertical">
				<div class="databox-top <?php echo $oBenchmark->mysql_update < $oBenchmark->etalon_mysql_update ? 'bg-orange' : 'bg-palegreen'?> no-padding">
					<div class="databox-row row-2"></div>
					<div class="databox-row row-10">
						<div class="databox-sparkline benchmark-databox-sparkline">
								<span><?php echo Core::_('Benchmark.bd_change')?></span>
						</div>
					</div>
				</div>
				<div class="databox-bottom no-padding bg-white">
					<div class="databox-row">
						<div class="databox-cell cell-6 text-align-center bordered-right bordered-platinum">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo $oBenchmark->mysql_update?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.server')?></span>
						</div>
						<div class="databox-cell cell-6 text-align-center">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo $oBenchmark->etalon_mysql_update?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.etalon')?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="databox radius-bordered databox-shadowed databox-vertical">
				<div class="databox-top <?php echo $oBenchmark->filesystem < $oBenchmark->etalon_filesystem ? 'bg-orange' : 'bg-palegreen'?> no-padding">
					<div class="databox-row row-2"></div>
					<div class="databox-row row-10">
						<div class="databox-sparkline benchmark-databox-sparkline">
							<span><?php echo Core::_('Benchmark.filesystem')?></span>
						</div>
					</div>
				</div>
				<div class="databox-bottom no-padding bg-white">
					<div class="databox-row">
						<div class="databox-cell cell-6 text-align-center bordered-right bordered-platinum">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo $oBenchmark->filesystem?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.server')?></span>
						</div>
						<div class="databox-cell cell-6 text-align-center">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo $oBenchmark->etalon_filesystem?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.etalon')?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="databox radius-bordered databox-shadowed databox-vertical">
				<div class="databox-top <?php echo $oBenchmark->cpu_math < $oBenchmark->etalon_cpu_math ? 'bg-orange' : 'bg-palegreen'?> no-padding">
					<div class="databox-row row-2"></div>
					<div class="databox-row row-10">
						<div class="databox-sparkline benchmark-databox-sparkline">
							<span><?php echo Core::_('Benchmark.cpu_math')?></span>
						</div>
					</div>
				</div>
				<div class="databox-bottom no-padding bg-white">
					<div class="databox-row">
						<div class="databox-cell cell-6 text-align-center bordered-right bordered-platinum">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo $oBenchmark->cpu_math?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.server')?></span>
						</div>
						<div class="databox-cell cell-6 text-align-center">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo $oBenchmark->etalon_cpu_math?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.etalon')?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="databox radius-bordered databox-shadowed databox-vertical">
				<div class="databox-top <?php echo $oBenchmark->cpu_string < $oBenchmark->etalon_cpu_string ? 'bg-orange' : 'bg-palegreen'?> no-padding">
					<div class="databox-row row-2"></div>
					<div class="databox-row row-10">
						<div class="databox-sparkline benchmark-databox-sparkline">
							<span><?php echo Core::_('Benchmark.cpu_string')?></span>
						</div>
					</div>
				</div>
				<div class="databox-bottom no-padding bg-white">
					<div class="databox-row">
						<div class="databox-cell cell-6 text-align-center bordered-right bordered-platinum">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo $oBenchmark->cpu_string?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.server')?></span>
						</div>
						<div class="databox-cell cell-6 text-align-center">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo $oBenchmark->etalon_cpu_string?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.etalon')?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="databox radius-bordered databox-shadowed databox-vertical">
				<div class="databox-top <?php echo $oBenchmark->network < $oBenchmark->etalon_network ? 'bg-orange' : 'bg-palegreen'?> no-padding">
					<div class="databox-row row-2"></div>
					<div class="databox-row row-10">
						<div class="databox-sparkline benchmark-databox-sparkline">
							<span><?php echo Core::_('Benchmark.download_speed')?></span>
						</div>
					</div>
				</div>
				<div class="databox-bottom no-padding bg-white">
					<div class="databox-row">
						<div class="databox-cell cell-6 text-align-center bordered-right bordered-platinum">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo Core::_('Benchmark.mbps', $oBenchmark->network)?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.server')?></span>
						</div>
						<div class="databox-cell cell-6 text-align-center">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo Core::_('Benchmark.mbps', $oBenchmark->etalon_network)?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.etalon')?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div class="databox radius-bordered databox-shadowed databox-vertical">
				<div class="databox-top <?php echo $oBenchmark->mail > $oBenchmark->etalon_mail ? 'bg-orange' : 'bg-palegreen'?> no-padding">
					<div class="databox-row row-2"></div>
					<div class="databox-row row-10">
						<div class="databox-sparkline benchmark-databox-sparkline">
							<span><?php echo Core::_('Benchmark.email')?></span>
						</div>
					</div>
				</div>
				<div class="databox-bottom no-padding bg-white">
					<div class="databox-row">
						<div class="databox-cell cell-6 text-align-center bordered-right bordered-platinum">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo Core::_('Benchmark.email_val',$oBenchmark->mail)?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.server')?></span>
						</div>
						<div class="databox-cell cell-6 text-align-center">
							<span class="databox-number lightcarbon benchmark-databox"><?php echo Core::_('Benchmark.email_val',$oBenchmark->etalon_mail)?></span>
							<span class="databox-text sonic-silver no-margin"><?php echo Core::_('Benchmark.etalon')?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	$oAdmin_Form_Controller->addEntity(
		Admin_Form_Entity::factory('Code')
			->html(ob_get_clean())
	);
}

// Показ формы
$oAdmin_Form_Controller->execute();
