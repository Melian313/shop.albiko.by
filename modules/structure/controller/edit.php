<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Structure.
 *
 * @package HostCMS 6\Structure
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Structure_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		$this
			->addSkipColumn('data_template_id');

		if (!$object->id)
		{
			$object->parent_id = intval(Core_Array::getGet('parent_id'));
		}

		return parent::setObject($object);
	}

	/**
	 * Prepare backend item's edit form
	 *
	 * @return self
	 */
	protected function _prepareForm()
	{
		parent::_prepareForm();

		$oMainTab = $this->getTab('main');
		$oAdditionalTab = $this->getTab('additional');

		$oSeoTab = Admin_Form_Entity::factory('Tab')
			->caption(Core::_('Structure.seo_tab'))
			->name('Seo');

		$oSitemapTab = Admin_Form_Entity::factory('Tab')
			->caption(Core::_('Structure.sitemap_tab'))
			->name('Sitemap');

		$oPropertyTab = Admin_Form_Entity::factory('Tab')
			->caption(Core::_('Structure.additional_params_tab'))
			->name('Property');

		$this
			->addTabAfter($oSeoTab, $oMainTab)
			->addTabAfter($oSitemapTab, $oSeoTab)
			->addTabAfter($oPropertyTab, $oSitemapTab);

		// ---------------------------------------------------------------------
		$oMainTab
			->add($oMainRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow2 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow3 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow4 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow13 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow5 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow6 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow7 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow8 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow9 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow10 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oMainRow11 = Admin_Form_Entity::factory('Div')->id('lib_properties'))
			->add($oMainRow12 = Admin_Form_Entity::factory('Div')->class('row'))
			;

		// -!- Row --
		$this->getField('name')
			->divAttr(array('class' => 'form-group col-lg-12 col-md-12 col-sm-12 col-xs-12'));
		$oMainTab
			->move($this->getField('name'), $oMainRow1);

		// -!- Row --
		// Выбор родительского раздела
		$oSelect_Parent_Id = Admin_Form_Entity::factory('Select')
			->options(
				array(' … ') + $this->fillStructureList($this->_object->site_id, 0, $this->_object->id)
			)
			->name('parent_id')
			->value($this->_object->parent_id)
			->caption(Core::_('Structure.parent_id'))
			->divAttr(array('style' => 'float: left'))
			->divAttr(array('class' => 'form-group col-sm-4 col-md-4 col-lg-4'));

		// Выбор меню
		$aMenu = $this->_fillMenuList($this->_object->site_id);

		$oSelect_Menu_Id = Admin_Form_Entity::factory('Select')
			->options(
				count($aMenu) ? $aMenu : array(' … ')
			)
			->name('structure_menu_id')
			->value($this->_object->structure_menu_id)
			->caption(Core::_('Structure.structure_menu_id'))
			->divAttr(array('class' => 'form-group col-sm-4 col-md-4 col-lg-4'));

		$this->getField('show')
			->divAttr(array('class' => 'form-group col-sm-4 col-md-4 col-lg-4 checkbox-margin-top'));

		$oAdditionalTab
			->delete($this->getField('parent_id'))
			->delete($this->getField('structure_menu_id'));

		$oMainRow2
			->add($oSelect_Parent_Id)
			->add($oSelect_Menu_Id);
		$oMainTab->move($this->getField('show'), $oMainRow2);

		// -!- Row --
		// Группа доступа
		$oAdditionalTab
			->delete($this->getField('siteuser_group_id'));

		if (Core::moduleIsActive('siteuser'))
		{
			$oSiteuser_Controller_Edit = new Siteuser_Controller_Edit($this->_Admin_Form_Action);
			$aSiteuser_Groups = $oSiteuser_Controller_Edit->fillSiteuserGroups($this->_object->site_id);
		}
		else
		{
			$aSiteuser_Groups = array();
		}

		$oSelect_SiteUserGroup = Admin_Form_Entity::factory('Select')
			->name('siteuser_group_id')
			->caption(Core::_('Structure.siteuser_group_id'))
			->options(
				array(
					// Все
					0 => Core::_('Structure.all'),
					// Как у родителя
					-1 => Core::_('Structure.like_parent')
				) + $aSiteuser_Groups
			)
			->value($this->_object->siteuser_group_id)
			->divAttr(array('class' => 'form-group col-xs-12 col-sm-4 col-md-4 col-lg-4'));

		$this->getField('sorting')
			->divAttr(array('class' => 'form-group col-xs-12 col-sm-4 col-md-4 col-lg-4'));

		$oMainTab
			->move($this->getField('path'), $oMainRow3)
			->move($this->getField('sorting'), $oMainRow3);
		$oMainRow3->add($oSelect_SiteUserGroup);

		// -!- Row --

		// Structure type
		$oMainTab->delete($this->getField('type'));

		$windowId = $this->_Admin_Form_Controller->getWindowId();

		$iLibId = 0;
		$iLibDirId = 0;

		$oRadio_Type = Admin_Form_Entity::factory('Radiogroup')
			->name('type')
			->id('structureType' . time())
			->caption(Core::_('Structure.type'))
			->value($this->_object->type)
			->divAttr(array('id' => 'structure_types', 'class' => 'form-group col-lg-12 col-md-12 col-sm-12 col-xs-12'))
			->radio(
				array(
					0 => Core::_('Structure.static_page'),
					2 => Core::_('Structure.typical_dynamic_page'),
					1 => Core::_('Structure.dynamic_page')
				)
			)
			->buttonset(TRUE)
			->ico(
				array(
					0 => 'fa-file-o',
					2 => 'fa-list-ul',
					1 => 'fa-file-text-o',
				)
			)
			->onclick("SetViewStructure('{$windowId}', this.value, '{$this->_object->id}', '{$iLibDirId}', '{$iLibId}');");

		// Статичный документ
		$oAdditionalTab->delete($this->getField('document_id'));

		$oMainTab
			->delete($this->getField('url'))
			->add($this->getField('url'));

		$this->getField('url')
			->divAttr(array('id' => 'url', 'class' => 'form-group col-lg-6'))
			// clear standart url pattern
			->format(array('lib' => array()));

		// Checkboxes
		$this->getField('active')
			->divAttr(array('class' => 'form-group col-sm-4 col-md-4 col-lg-4'));
		$this->getField('indexing')
			->divAttr(array('class' => 'form-group col-sm-4 col-md-4 col-lg-4'));
		$this->getField('https')
			->divAttr(array('class' => 'form-group col-sm-4 col-md-4 col-lg-4'));

		$oMainTab
			->move($this->getField('active'), $oMainRow4)
			->move($this->getField('indexing'), $oMainRow4)
			->move($this->getField('https'), $oMainRow4);

		$oMainRow5->add($oRadio_Type);

		$oDocument = Core_Entity::factory('Document', $this->_object->document_id);

		// Контроллер редактирования документа
		$Document_Controller_Edit = new Document_Controller_Edit($this->_Admin_Form_Action);

		$Select_DocumentDir = Admin_Form_Entity::factory('Select')
			->name('document_dir_id')
			->id('document_dir_id')
			->caption(Core::_('Structure.document_dir_id'))
			->divAttr(array('id' => 'document_dir', 'class' => 'form-group col-lg-6'))
			->options(
				array(' … ') + $Document_Controller_Edit->fillDocumentDir($this->_object->site_id)
			)
			->value($oDocument->document_dir_id) //
			->onchange("$.ajaxRequest({path: '/admin/structure/index.php', context: 'document_id', callBack: $.loadSelectOptionsCallback, action: 'loadDocumentList', additionalParams: 'document_dir_id=' + this.value,windowId: '{$windowId}'}); return false");

		$oMainRow6->add($Select_DocumentDir);

		$aDocumentForDir = array(' … ');
		// intval() because $oDocument->document_dir_id may be NULL
		$aDocuments = Core_Entity::factory('Document_Dir', intval($oDocument->document_dir_id))
			->Documents->getBySiteId($this->_object->site_id);

		foreach ($aDocuments as $oTmpDocument)
		{
			$aDocumentForDir[$oTmpDocument->id] = $oTmpDocument->name;
		}

		$Select_Document = Admin_Form_Entity::factory('Select')
			->name('document_id')
			->id('document_id')
			->caption(Core::_('Structure.document_id'))
			->divAttr(array('id' => 'document', 'class' => 'form-group col-lg-6'))
			->options($aDocumentForDir)
			->value($this->_object->document_id);

		$Select_Document
			->add(
				Admin_Form_Entity::factory('A')
					->href('/admin/document/index.php?document_dir_id=' . intval($oDocument->document_dir_id) . '&hostcms[checked][1][' . $this->_object->document_id  . ']=1&hostcms[action]=edit')
					->class('input-group-addon bg-blue bordered-blue')
					->value('<i class="fa fa-pencil"></i>')
					->onclick("return !($.openWindow && $.openWindow({width: '80%', path: '/admin/document/index.php', additionalParams: 'document_dir_id=' + $('#{$windowId} #document_dir_id').val() + '&hostcms[checked][1][' + $('#{$windowId} #document_id').val() + ']=1&hostcms[action]=edit', dialogClass: 'hostcms6'}));")
			);

		$oMainRow7->add($Select_Document);

		// -!- Row --
		// Выбор макета
		$Template_Controller_Edit = new Template_Controller_Edit($this->_Admin_Form_Action);

		$aTemplateOptions = $Template_Controller_Edit->fillTemplateList($this->_object->site_id);

		$oSelect_Template_Id = Admin_Form_Entity::factory('Select')
			->options(
				count($aTemplateOptions) ? $aTemplateOptions : array(' … ')
			)
			->name('template_id')
			->value($this->_object->template_id)
			->caption(Core::_('Structure.template_id'))
			->divAttr(array('class' => 'form-group col-lg-6', 'id' => 'template_id'));

		$oMainRow13->add($oSelect_Template_Id);

		$oAdditionalTab->delete($this->getField('template_id'));

		$this->getField('path')
			->divAttr(array('class' => 'form-group col-xs-12 col-sm-4 col-md-4 col-lg-4'));

		// -!- Row --
		$oMainTab
			->move($this->getField('url'), $oMainRow8);

		// Типовая динамическая страница
		$oAdditionalTab->delete($this->getField('lib_id'));

		$Lib_Controller_Edit = new Lib_Controller_Edit($this->_Admin_Form_Action);

		$oLib = Core_Entity::factory('Lib', $this->_object->lib_id);

		$Select_LibDir = Admin_Form_Entity::factory('Select')
			->name('lib_dir_id')
			->caption(Core::_('Structure.lib_dir_id'))
			->divAttr(array('id' => 'lib_dir', 'class' => 'form-group col-lg-6'))
			->options(
				array(' … ') + $Lib_Controller_Edit->fillLibDir(0)
			)
			->value($oLib->lib_dir_id) //
			->onchange("$.ajaxRequest({path: '/admin/structure/index.php',context: 'lib_id', callBack: $.loadSelectOptionsCallback, action: 'loadLibList',additionalParams: 'lib_dir_id=' + this.value,windowId: '{$windowId}'}); return false");

		$aLibForDir = array(' … ');
		$aLibs = Core_Entity::factory('Lib_Dir', intval($oLib->lib_dir_id)) // Может быть NULL
			->Libs->findAll();

		foreach ($aLibs as $oTmpLib)
		{
			$aLibForDir[$oTmpLib->id] = '[' . $oTmpLib->id . '] ' . $oTmpLib->name;
		}
		$objectId = intval($this->_object->id);
		$Select_Lib = Admin_Form_Entity::factory('Select')
			->name('lib_id')
			->id('lib_id')
			->caption(Core::_('Structure.lib_id'))
			->divAttr(array('id' => 'lib', 'class' => 'form-group col-lg-6'))
			->options($aLibForDir)
			->value($this->_object->lib_id)
			->onchange("$.ajaxRequest({path: '/admin/structure/index.php',context: 'lib_properties', callBack: $.loadDivContentAjaxCallback, objectId: {$objectId}, action: 'loadLibProperties',additionalParams: 'lib_id=' + this.value,windowId: '{$windowId}'}); return false")
			;

		$Div_Lib_Properies = Admin_Form_Entity::factory('Code');

		ob_start();
		// DIV для св-в типовой дин. страницы
		// Для выбранного стандартно
		$Core_Html_Entity_Div = Core::factory('Core_Html_Entity_Script')
			->type("text/javascript")
			->value("$('#{$windowId} #lib_id').change();")
			->execute();

		$Div_Lib_Properies
			->html(ob_get_clean());

		$oMainRow9->add($Select_LibDir);
		$oMainRow10->add($Select_Lib);
		$oMainRow11->add($Div_Lib_Properies);

		// Динамическая страница
		$oTextarea_Structure_Source = Admin_Form_Entity::factory('Textarea');

		$oTmpOptions = $oTextarea_Structure_Source->syntaxHighlighterOptions;
		$oTmpOptions['mode'] = 'application/x-httpd-php';

		$oTextarea_Structure_Source
			->name('structure_source')
			->divAttr(array('id' => 'structure_source', 'class' => 'form-group col-sm-12 col-md-12 col-lg-12'))
			->caption(Core::_('Structure.structure_source'))
			->value($this->_object->getStructureFile())
			->syntaxHighlighter(defined('SYNTAX_HIGHLIGHTING') ? SYNTAX_HIGHLIGHTING : TRUE)
			->syntaxHighlighterOptions($oTmpOptions)
			->rows(30);

		$oTextarea_StructureConfig_Source = Admin_Form_Entity::factory('Textarea')
			->name('structure_config_source')
			->divAttr(array('id' => 'structure_config_source', 'class' => 'form-group col-sm-12 col-md-12 col-lg-12'))
			->caption(Core::_('Structure.structure_config_source'))
			->value($this->_object->getStructureConfigFile())
			->syntaxHighlighter(defined('SYNTAX_HIGHLIGHTING') ? SYNTAX_HIGHLIGHTING : TRUE)
			->syntaxHighlighterOptions($oTmpOptions)
			->rows(30);

		$oMainRow12
			->add($oTextarea_Structure_Source)
			->add($oTextarea_StructureConfig_Source);

		// -- SEO
		$oSeoTab
			->add($oSeoRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oSeoRow2 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oSeoRow3 = Admin_Form_Entity::factory('Div')->class('row'));

		$oMainTab
			->move($this->getField('seo_title')->rows(2), $oSeoRow1)
			->move($this->getField('seo_description')->rows(2), $oSeoRow2)
			->move($this->getField('seo_keywords')->rows(2), $oSeoRow3);

		// -- Sitemap
		$oSelect_changefreq = Admin_Form_Entity::factory('Select')
			->options(
				array(
					0 => Core::_('Structure.sitemap_refrashrate_always'),
					1 => Core::_('Structure.sitemap_refrashrate_hourly'),
					2 => Core::_('Structure.sitemap_refrashrate_daily'),
					3 => Core::_('Structure.sitemap_refrashrate_weekly'),
					4 => Core::_('Structure.sitemap_refrashrate_monthly'),
					5 => Core::_('Structure.sitemap_refrashrate_yearly'),
					6 => Core::_('Structure.sitemap_refrashrate_never')
				)
			)
			->name('changefreq')
			->value($this->_object->changefreq)
			->caption(Core::_('Structure.changefreq'))
			->divAttr(array('class' => 'form-group col-xs-4 col-sm-4 col-md-4 col-lg-4'));

		$oSelect_priority = Admin_Form_Entity::factory('Select')
			->options(
				array(
					'0' => Core::_('Structure.sitemap_priority_small'),
					'0.1' => Core::_('Structure.sitemap_priority_0_1'),
					'0.2' => Core::_('Structure.sitemap_priority_0_2'),
					'0.3' => Core::_('Structure.sitemap_priority_0_3'),
					'0.4' => Core::_('Structure.sitemap_priority_0_4'),
					'0.5' => Core::_('Structure.sitemap_priority_normal'),
					'0.6' => Core::_('Structure.sitemap_priority_0_6'),
					'0.7' => Core::_('Structure.sitemap_priority_0_7'),
					'0.8' => Core::_('Structure.sitemap_priority_0_8'),
					'0.9' => Core::_('Structure.sitemap_priority_0_9'),
					'1' => Core::_('Structure.sitemap_priority_above_normal')
				)
			)
			->name('priority')
			->value($this->_object->priority)
			->caption(Core::_('Structure.priority'))
			->divAttr(array('class' => 'form-group col-xs-4 col-sm-4 col-md-4 col-lg-4'));

		$oSitemapTab
			->add($oSitemapRow1 = Admin_Form_Entity::factory('Div')->class('row'))
			->add($oSitemapRow2 = Admin_Form_Entity::factory('Div')->class('row'));

		$oMainTab
			->delete($this->getField('changefreq'))
			->delete($this->getField('priority'));

		$oSitemapRow1->add($oSelect_changefreq);
		$oSitemapRow2->add($oSelect_priority);

		// ---- Дополнительные свойства
		Property_Controller_Tab::factory($this->_Admin_Form_Controller)
			->setObject($this->_object)
			->setDatasetId($this->getDatasetId())
			->linkedObject(Core_Entity::factory('Structure_Property_List', CURRENT_SITE))
			->setTab($oPropertyTab)
			->template_id($this->_object->template_id)
			->fillTab();

		$this->title($this->_object->id
			? Core::_('Structure.edit_title')
			: Core::_('Structure.add_title'));

		ob_start();
		Core::factory('Core_Html_Entity_Script')
			->type("text/javascript")
			->value("SetViewStructure('{$windowId}', '{$this->_object->type}', '{$this->_object->id}', '{$iLibDirId}', '{$iLibId}')")
			->execute();

		$oMainTab->add(
			Admin_Form_Entity::factory('Code')->html(ob_get_clean())
		);

		return $this;
	}

	/**
	 * Processing of the form. Apply object fields.
	 * @hostcms-event Structure_Controller_Edit.onAfterRedeclaredApplyObjectProperty
	 */
	protected function _applyObjectProperty()
	{
		parent::_applyObjectProperty();

		// Path transliteration
		/*if ($this->_object->path == '')
		{
			$this->_object->path = Core_Str::transliteration($this->_object->name);
			$this->_object->save();
		}*/

		$this->_object->saveStructureFile(Core_Array::getPost('structure_source'));
		$this->_object->saveStructureConfigFile(Core_Array::getPost('structure_config_source'));

		// Lib properies
		if ($this->_object->type == 2 && $this->_object->lib_id)
		{
			$LA = array();

			$oLib = $this->_object->Lib;
			$aLib_Properties = $oLib->Lib_Properties->findAll();

			foreach ($aLib_Properties as $oLib_Property)
			{
				$propertyName = 'lib_property_id_' . $oLib_Property->id;

				$propertyValue = Core_Array::getPost($propertyName);

				switch ($oLib_Property->type)
				{
					case 1: // Флажок
					$propertyValue = !is_null($propertyValue);
					break;

					case 2: // XSL шаблон
					$propertyValue = Core_Entity::factory('Xsl', $propertyValue)->name;
					break;

					case 0: // Поле ввода
					case 3: // Список
					case 4: // SQL-запрос
					default:

					$propertyValue = strval($propertyValue);
					break;
				}

				$LA[$oLib_Property->varible_name] = $propertyValue;
			}

			// Сохраняем настройки
			if (count($LA) > 0)
			{
				try
				{
					$oLib->saveDatFile($LA, $this->_object->id);
				}
				catch (Exception $e)
				{
					Core_Message::show($e->getMessage(), 'error');
					Core_Message::show(Core::_('Structure.save_lib_file_error'), 'error');
				}
			}
		}

		// ---- Дополнительные свойства
		Property_Controller_Tab::factory($this->_Admin_Form_Controller)
			->setObject($this->_object)
			->linkedObject(Core_Entity::factory('Structure_Property_List', CURRENT_SITE))
			->applyObjectProperty();
		// ----

		if (Core::moduleIsActive('search'))
		{
			Search_Controller::indexingSearchPages(array(
				$this->_object->indexing()
			));
		}

		$this->_object->clearCache();

		Core_Event::notify(get_class($this) . '.onAfterRedeclaredApplyObjectProperty', $this, array($this->_Admin_Form_Controller));
	}

	/**
	 * Create visual tree of the directories
	 * @param int $iSiteId site ID
	 * @param int $iParentId parent directory ID
	 * @param boolean $bExclude exclude group ID
	 * @param int $iLevel current nesting level
	 * @return array
	 */
	public function fillStructureList($iSiteId, $iParentId = 0, $bExclude = FALSE, $iLevel = 0)
	{
		$iSiteId = intval($iSiteId);
		$iParentId = intval($iParentId);
		$iLevel = intval($iLevel);

		$oStructure = Core_Entity::factory('Structure', $iParentId);

		$aReturn = array();

		// Дочерние разделы
		$aChildren = $oStructure->Structures->getBySiteId($iSiteId);

		if (count($aChildren))
		{
			foreach ($aChildren as $oStructure)
			{
				if ($bExclude != $oStructure->id)
				{
					$aReturn[$oStructure->id] = str_repeat('  ', $iLevel) . $oStructure->name;
					$aReturn += $this->fillStructureList($iSiteId, $oStructure->id, $bExclude, $iLevel + 1);
				}
			}
		}

		return $aReturn;
	}

	/**
	 * Fill menus list
	 * @param int $iSiteId site ID
	 * @return array
	 */
	protected function _fillMenuList($iSiteId)
	{
		$iSiteId = intval($iSiteId);

		$aReturn = array();
		$aChildren = Core_Entity::factory('Structure_Menu')->getBySiteId($iSiteId);

		if (count($aChildren))
		{
			foreach ($aChildren as $oMenu)
			{
				$aReturn[$oMenu->id] = $oMenu->name;
			}
		}

		return $aReturn;
	}
}