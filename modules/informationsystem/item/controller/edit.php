<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Information systems.
 *
 * @package HostCMS 6\Informationsystem
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Informationsystem_Item_Controller_Edit extends Admin_Form_Action_Controller_Type_Edit
{
	/**
	 * Set object
	 * @param object $object object
	 * @return self
	 */
	public function setObject($object)
	{
		$modelName = $object->getModelName();

		$informationsystem_id = Core_Array::getGet('informationsystem_id');
		$informationsystem_group_id = Core_Array::getGet('informationsystem_group_id');

		switch ($modelName)
		{
			case 'informationsystem_item':
				$this
					->addSkipColumn('shortcut_id')
					->addSkipColumn('image_large')
					->addSkipColumn('image_small')
					->addSkipColumn('image_large_width')
					->addSkipColumn('image_large_height')
					->addSkipColumn('image_small_width')
					->addSkipColumn('image_small_height');

				if (!$object->id)
				{
					$object->informationsystem_id = $informationsystem_id;
					$object->informationsystem_group_id = $informationsystem_group_id;
				}
			break;
			case 'informationsystem_group':
				$this
					->addSkipColumn('image_large')
					->addSkipColumn('image_small')
					->addSkipColumn('top_parent_id')
					->addSkipColumn('subgroups_count')
					->addSkipColumn('subgroups_total_count')
					->addSkipColumn('items_count')
					->addSkipColumn('items_total_count')
					->addSkipColumn('sns_type_id');

				// Значения директории для добавляемого объекта
				if (!$object->id)
				{
					$object->informationsystem_id = $informationsystem_id;
					$object->parent_id = $informationsystem_group_id;
				}
			break;
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

		$object = $this->_object;

		$modelName = $object->getModelName();

		$oInformationsystem = is_null($object->id)
			? Core_Entity::factory('Informationsystem', $object->informationsystem_id)
			: $object->Informationsystem;

		$oMainTab = $this->getTab('main');
		$oAdditionalTab = $this->getTab('additional');

		switch ($modelName)
		{
			case 'informationsystem_item':

				if ($object->shortcut_id != 0)
				{
					$object = $object->Informationsystem_Item;
				}

				$title = $object->id
					? Core::_('Informationsystem_Item.information_items_edit_form_title')
					: Core::_('Informationsystem_Item.information_items_add_form_title');

				$template_id = $this->_object->Informationsystem->Structure->template_id
					? $this->_object->Informationsystem->Structure->template_id
					: 0;

				$oPropertyTab = Admin_Form_Entity::factory('Tab')
					->caption(Core::_('Informationsystem_Item.tab_4'))
					->name('Property');

				$this->addTabBefore($oPropertyTab, $oAdditionalTab);

				// Properties
				Property_Controller_Tab::factory($this->_Admin_Form_Controller)
					->setObject($this->_object)
					->setDatasetId($this->getDatasetId())
					->linkedObject(Core_Entity::factory('Informationsystem_Item_Property_List', $oInformationsystem->id))
					->setTab($oPropertyTab)
					->template_id($template_id)
					->fillTab();

				$oMainTab
					->add($oMainRow1 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow2 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow3 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow4 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow5 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow6 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow7 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow8 = Admin_Form_Entity::factory('Div')->class('row'));

				$oAdditionalTab->delete($this->getField('informationsystem_group_id'));

				$oMainTab->delete($this->getField('name'));

				$oName = Admin_Form_Entity::factory('Input')
					->name('name')
					->value($this->_object->name)
					->caption(Core::_('Informationsystem_Item.name'))
					->class('form-control input-lg');

				$oMainRow1->add($oName);

				$oSelect_Group = Admin_Form_Entity::factory('Select')
					->name('informationsystem_group_id')
					->caption(Core::_('Informationsystem_Item.informationsystem_group_id'))
					->options(
						array(' … ') + self::fillInformationsystemGroup($object->informationsystem_id, 0)
					)
					->value($this->_object->informationsystem_group_id)
					->filter(TRUE);

				$oMainRow2->add($oSelect_Group);

				$this->getField('datetime')
					->divAttr(array('class' => 'form-group col-lg-4 col-md-4 col-sm-4'));
				$this->getField('start_datetime')
					->divAttr(array('class' => 'form-group col-lg-4 col-md-4 col-sm-4'));
				$this->getField('end_datetime')
					->divAttr(array('class' => 'form-group col-lg-4 col-md-4 col-sm-4'));

				$this->_object->start_datetime == '0000-00-00 00:00:00'
					&& $this->getField('start_datetime')->value('');

				$this->_object->end_datetime == '0000-00-00 00:00:00'
					&& $this->getField('end_datetime')->value('');

				$oMainTab
					->move($this->getField('datetime'), $oMainRow3)
					->move($this->getField('start_datetime'), $oMainRow3)
					->move($this->getField('end_datetime'), $oMainRow3);

				$this->getField('active')
					->divAttr(array('class' => 'form-group col-lg-4 col-md-4 col-sm-4 col-xs-6'));
				$this->getField('indexing')
					->divAttr(array('class' => 'form-group col-lg-4 col-md-4 col-sm-4 col-xs-6'));

				$oMainTab->move($this->getField('active'), $oMainRow4);
				$oMainTab->move($this->getField('indexing'), $oMainRow4);

				$this->getField('sorting')
					->divAttr(array('class' => 'form-group col-lg-3 col-md-3 col-sm-3 col-xs-6'));
				$this->getField('ip')
					->divAttr(array('class' => 'form-group col-lg-3 col-md-3 col-sm-3 col-xs-6'));
				$this->getField('showed')
					->divAttr(array('class' => 'form-group col-lg-3 col-md-3 col-sm-3 col-xs-6'));

				$oMainTab
					->move($this->getField('sorting'), $oMainRow5)
					->move($this->getField('ip'), $oMainRow5)
					->move($this->getField('showed'), $oMainRow5);

				$oAdditionalTab->delete($this->getField('siteuser_id'));

				$oSiteuser = Admin_Form_Entity::factory('Input')
					->value($this->_object->siteuser_id)
					->caption(Core::_('Informationsystem_Group.siteuser_id'))
					->name('siteuser_id')
					->divAttr(array('class' => 'form-group col-lg-3 col-md-3 col-sm-3 col-xs-6'));

				$oMainRow5->add($oSiteuser);

				// Добавляем новое поле типа файл
				$oImageField = Admin_Form_Entity::factory('File');

				$oLargeFilePath = is_file($this->_object->getLargeFilePath())
					? $this->_object->getLargeFileHref()
					: '';

				$oSmallFilePath = is_file($this->_object->getSmallFilePath())
					? $this->_object->getSmallFileHref()
					: '';

				$sFormPath = $this->_Admin_Form_Controller->getPath();
				$windowId = $this->_Admin_Form_Controller->getWindowId();

				$oImageField
					//->caption(Core::_('Informationsystem_Group.image_large'))
					->name("image")
					->id("image")
					->largeImage(array(
							// image_big_max_width - значение максимальной ширины большого изображения;
							'max_width' => $oInformationsystem->image_large_max_width,

							// image_big_max_height - значение максимальной высоты большого изображения;
							'max_height' => $oInformationsystem->image_large_max_height,

							// big_image_path - адрес большого загруженного изображения
							'path' => $oLargeFilePath,

							// show_big_image_params - параметр, определяющий отображать ли настройки большого изображения
							'show_params' => TRUE,

							// watermark_position_x - значение поля ввода с подписью "По оси X"
							'watermark_position_x' => $oInformationsystem->watermark_default_position_x,

							// watermark_position_y - значение поля ввода с подписью "По оси Y"
							'watermark_position_y' => $oInformationsystem->watermark_default_position_y,

							// large_image_watermark_checked - вид ображения checkbox'а с подписью "Наложить водяной знак на большое изображение" (1 -  отображать выбранным (по умолчанию), 0 - невыбранным);
							'place_watermark_checkbox_checked' => $oInformationsystem->watermark_default_use_large_image,

							// onclick_delete_big_image - значение onclick для удаления большой картинки
							'delete_onclick' => "$.adminLoad({path: '{$sFormPath}', additionalParams: 'hostcms[checked][{$this->_datasetId}][{$this->_object->id}]=1', action: 'deleteLargeImage', windowId: '{$windowId}'}); return false",

							'caption' => Core::_('Informationsystem_Item.image_large'),

							// used_big_image_preserve_aspect_ratio_checked -  вид ображения checkbox'а с подписью "Сохранять пропорции изображения" (1 -  отображать выбранным (по умолчанию), 0 - невыбранным);
							'preserve_aspect_ratio_checkbox_checked' => $oInformationsystem->preserve_aspect_ratio
						)
					)
					->smallImage(array(			// image_small_max_width - значение максимальной ширины малого изображения;
							'max_width' => $oInformationsystem->image_small_max_width,

							// image_small_max_height - значение максимальной высоты малого изображения;
							'max_height' => $oInformationsystem->image_small_max_height,

							// small_image_path - адрес малого загруженного изображения
							'path' => $oSmallFilePath,

							// make_small_image_from_big_checked - вид ображения checkbox'а с подписью "Создать малое изображение из большого" выбранным (1 -  отображать выбранным (по умолчанию), 0 - невыбранным);
							'create_small_image_from_large_checked' => $this->_object->image_small == '',

							// small_image_watermark_checked - вид ображения checkbox'а с подписью "Наложить водяной знак на малое изображение" (1 -  отображать выбранным (по умолчанию), 0 - невыбранным);
							'place_watermark_checkbox_checked' => $oInformationsystem->watermark_default_use_small_image,

							// onclick_delete_small_image - значение onclick для удаления малой картинки
							'delete_onclick' => "$.adminLoad({path: '{$sFormPath}', additionalParams: 'hostcms[checked][{$this->_datasetId}][{$this->_object->id}]=1', action: 'deleteSmallImage', windowId: '{$windowId}'}); return false",

							// load_small_image_caption - заголовок поля загрузки малого изображения
							'caption' => Core::_('Informationsystem_Item.image_small'),

							//'name' => 'small_' . $this->largeImage['name'],
							//'id' => 'small_' . $this->largeImage['id'],

							'show_params' => TRUE,

							'preserve_aspect_ratio_checkbox_checked' => $oInformationsystem->preserve_aspect_ratio_small
						)
					);

				$oMainRow6->add($oImageField);

				$this->getField('path')
					->format(array('maxlen' => array('value' => 255)));

				$oMainTab->move($this->getField('path'), $oMainRow7);

				if (Core::moduleIsActive('maillist'))
				{
					$oMaillist_Controller_Edit = new Maillist_Controller_Edit($this->_Admin_Form_Action);

					$oSelect_Maillist = Admin_Form_Entity::factory('Select');

					$oSelect_Maillist->options(array(Core::_('Informationsystem_Item.maillist_default_value'))
						+ $oMaillist_Controller_Edit->fillMaillist()
					)
					->name('maillist_id')
					->value(0)
					->caption(Core::_('Informationsystem_Item.maillist'))
					->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6'));

					$oMainRow8->add($oSelect_Maillist);
				}

				if (Core::moduleIsActive('siteuser'))
				{
					$oSiteuser_Controller_Edit = new Siteuser_Controller_Edit($this->_Admin_Form_Action);
					$aSiteuser_Groups = $oSiteuser_Controller_Edit->fillSiteuserGroups($this->_object->Informationsystem->site_id);
				}
				else
				{
					$aSiteuser_Groups = array();
				}

				// Список групп пользователей
				$oSelect_SiteuserGroups = Admin_Form_Entity::factory('Select')
					->options(
						array(
							0 => Core::_('Informationsystem.information_all'),
							-1 => Core::_('Informationsystem_Group.information_parent')
						) + $aSiteuser_Groups
					)
					->name('siteuser_group_id')
					->value($this->_object->siteuser_group_id)
					->caption(Core::_('Informationsystem_Item.siteuser_group_id'))
					->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6'));

				$oMainRow8->add($oSelect_SiteuserGroups);

				$oAdditionalTab = $this->getTab('additional');
				$oAdditionalTab->delete($this->getField('siteuser_group_id'));

				$this->getField('informationsystem_id')->divAttr(array('style' => 'display: none'));

				// Description
				$oInformationsystemTabDescription = Admin_Form_Entity::factory('Tab')
					->caption(Core::_('Informationsystem_Item.tab_1'))
					->name('Description');
				$this->addTabAfter($oInformationsystemTabDescription, $oMainTab);

				$oInformationsystemTabDescription
					->add($oDescriptionRow1 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oDescriptionRow2 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oDescriptionRow3 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oDescriptionRow4 = Admin_Form_Entity::factory('Div')->class('row'));

				$this->getField('description')
					->wysiwyg(TRUE)
					->rows(7)
					->template_id($template_id);

				$oMainTab->move($this->getField('description'), $oDescriptionRow1);

				if (Core::moduleIsActive('typograph'))
				{
					$this->getField('description')->value(
						Typograph_Controller::instance()->eraseOpticalAlignment($this->getField('description')->value)
					);

					$oUseTypograph = Admin_Form_Entity::factory('Checkbox');
					$oUseTypograph
						->name("use_typograph_description")
						->caption(Core::_('Informationsystem_Item.exec_typograph_description'))
						->value($oInformationsystem->typograph_default_items)
						->divAttr(array('class' => 'form-group col-lg-4 col-md-5 col-sm-5'));

					$oUseTrailingPunctuation = Admin_Form_Entity::factory('Checkbox');
					$oUseTrailingPunctuation
						->name("use_trailing_punctuation_description")
						->caption(Core::_('Informationsystem_Item.use_trailing_punctuation'))
						->value($oInformationsystem->typograph_default_items)
						->divAttr(array('class' => 'form-group col-lg-4 col-md-5 col-sm-5'));

					$oDescriptionRow2
						->add($oUseTypograph)
						->add($oUseTrailingPunctuation);
				}

				// Text
				$this->getField('text')
					->wysiwyg(TRUE)
					->rows(15)
					->template_id($template_id);

				$oMainTab->move($this->getField('text'), $oDescriptionRow3);

				//$oMainTab->move($this->getField('text'), $oInformationsystemTabDescription);
				if (Core::moduleIsActive('typograph'))
				{
					$this->getField('text')->value(
						Typograph_Controller::instance()->eraseOpticalAlignment($this->getField('text')->value)
					);

					$oUseTypograph = Admin_Form_Entity::factory('Checkbox');
					$oUseTypograph
						->name("use_typograph_text")
						->caption(Core::_('Informationsystem_Item.exec_typograph_for_text'))
						->value($oInformationsystem->typograph_default_items)
						->divAttr(array('class' => 'form-group col-lg-4 col-md-5 col-sm-5'));

					$oUseTrailingPunctuation = Admin_Form_Entity::factory('Checkbox');
					$oUseTrailingPunctuation
						->name("use_trailing_punctuation_text")
						->caption(Core::_('Informationsystem_Item.use_trailing_punctuation_for_text'))
						->value($oInformationsystem->typograph_default_items)
						->divAttr(array('class' => 'form-group col-lg-4 col-md-5 col-sm-5'));

					$oDescriptionRow4
						->add($oUseTypograph)
						->add($oUseTrailingPunctuation);
				}

				// SEO
				$oInformationsystemTabSeo = Admin_Form_Entity::factory('Tab')
					->caption(Core::_('Informationsystem_Item.tab_2'))
					->name('Seo');

				$this->addTabAfter($oInformationsystemTabSeo, $oInformationsystemTabDescription);

				$oInformationsystemTabSeo
					->add($oSeoRow1 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oSeoRow2 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oSeoRow3 = Admin_Form_Entity::factory('Div')->class('row'));

				$oMainTab
					->move($this->getField('seo_title'), $oSeoRow1)
					->move($this->getField('seo_description'), $oSeoRow2)
					->move($this->getField('seo_keywords'), $oSeoRow3);

				if (Core::moduleIsActive('tag'))
				{
					$oTagsTab = Admin_Form_Entity::factory('Tab')
						->caption(Core::_('Informationsystem_Item.tab_3'))
						->name('Tags');
					$this->addTabAfter($oTagsTab, $oInformationsystemTabSeo);

					$html = '<label class="tags_label" for="form-field-tags">' . Core::_('Informationsystem_Item.tags') . '</label>
						<div class="item_div">
							<input type="text" name="tags" id="form-field-tags" value="' . implode(", ", $this->_object->Tags->findAll()) . '" placeholder="' . Core::_('Informationsystem_Item.type_tag') . '" />
						</div>
						<script type="text/javascript">
							jQuery(function($){
								//we could just set the data-provide="tag" of the element inside HTML, but IE8 fails!
								var tag_input = $(\'#form-field-tags\');
								if(! ( /msie\s*(8|7|6)/.test(navigator.userAgent.toLowerCase())) )
								{
									tag_input.tag( { placeholder:tag_input.attr(\'placeholder\') } );
								}
								else {
									//display a textarea for old IE, because it doesnt support this plugin or another one I tried!
									tag_input.after(\'<textarea id="\'+ tag_input.attr(\'id\')+\'" name="\'+tag_input.attr(\'name\')+\'" rows=\"3\">\'+tag_input.val()+\'</textarea>\').remove();
									//$(\'#form-field-tags\').autosize({append: "n"});
								}

							})
						</script>
							';
					$oTagsTab->add(Admin_Form_Entity::factory('Code')->html($html));
				}

			break;
			case 'informationsystem_group':
			default:

				$template_id = $this->_object->Informationsystem->Structure->template_id
					? $this->_object->Informationsystem->Structure->template_id
					: 0;

				$oPropertyTab = Admin_Form_Entity::factory('Tab')
					->caption(Core::_('Informationsystem_Group.information_groups_form_tab_properties'))
					->name('Property');

				$this->addTabBefore($oPropertyTab, $oAdditionalTab);

				// Properties
				Property_Controller_Tab::factory($this->_Admin_Form_Controller)
					->setObject($this->_object)
					->setDatasetId($this->getDatasetId())
					->linkedObject(Core_Entity::factory('Informationsystem_Group_Property_List', $oInformationsystem->id))
					->setTab($oPropertyTab)
					->template_id($template_id)
					->fillTab();

				$title = $this->_object->id
						? Core::_('Informationsystem_Group.information_groups_edit_form_title')
						: Core::_('Informationsystem_Group.information_groups_add_form_title');

				$oMainTab
					->add($oMainRow1 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow2 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow3 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow4 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow5 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow6 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oMainRow7 = Admin_Form_Entity::factory('Div')->class('row'));

				// Name
				$oMainTab
					->move($this->getField('name'), $oMainRow1);

				// parent_id
				$oAdditionalTab->delete($this->getField('parent_id'));

				$oSelect_Group = Admin_Form_Entity::factory('Select')
					->name('parent_id')
					->caption(Core::_('Informationsystem_Group.parent_id'))
					->options(
						array(' … ') + self::fillInformationsystemGroup($object->informationsystem_id, 0, array($this->_object->id))
					)
					->value($this->_object->parent_id)
					->filter(TRUE);

				$oMainRow2->add($oSelect_Group);

				// Description
				$oMainTab->move($this->getField('description'), $oMainRow3);

				$this->getField('description')
					->wysiwyg(TRUE)
					->template_id($template_id);

				if (Core::moduleIsActive('typograph'))
				{
					$this->getField('description')->value(
						Typograph_Controller::instance()->eraseOpticalAlignment($this->getField('description')->value)
					);

					$oUseTypograph = Admin_Form_Entity::factory('Checkbox');
					$oUseTypograph
						->name("use_typograph_description")
						->caption(Core::_('Informationsystem_Item.exec_typograph_description'))
						->value($oInformationsystem->typograph_default_items)
						->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6'));

					$oUseTrailingPunctuation = Admin_Form_Entity::factory('Checkbox');
					$oUseTrailingPunctuation
						->name("use_trailing_punctuation_description")
						->caption(Core::_('Informationsystem_Item.use_trailing_punctuation'))
						->value($oInformationsystem->typograph_default_items)
						->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6'));

					$oMainRow3
						->add($oUseTypograph)
						->add($oUseTrailingPunctuation);
				}

				// Добавляем новое поле типа файл
				$oImageField = Admin_Form_Entity::factory('File');

				$oLargeFilePath = is_file($this->_object->getLargeFilePath())
					? $this->_object->getLargeFileHref()
					: '';

				$oSmallFilePath = is_file($this->_object->getSmallFilePath())
					? $this->_object->getSmallFileHref()
					: '';

				$sFormPath = $this->_Admin_Form_Controller->getPath();
				$windowId = $this->_Admin_Form_Controller->getWindowId();

				$oImageField
					//->caption(Core::_('Informationsystem_Group.image_large'))
					->style("width: 400px;")
					->name("image")
					->id("image")
					->largeImage(
					array(
						// image_big_max_width - значение максимальной ширины большого изображения;
						'max_width' => $oInformationsystem->group_image_large_max_width,

						// image_big_max_height - значение максимальной высоты большого изображения;
						'max_height' => $oInformationsystem->group_image_large_max_height,

						// big_image_path - адрес большого загруженного изображения
						'path' => $oLargeFilePath,

						// show_big_image_params - параметр, определяющий отображать ли настройки большого изображения
						'show_params' => TRUE,

						// watermark_position_x - значение поля ввода с подписью "По оси X"
						'watermark_position_x' => $oInformationsystem->watermark_default_position_x,

						// watermark_position_y - значение поля ввода с подписью "По оси Y"
						'watermark_position_y' => $oInformationsystem->watermark_default_position_y,

						// large_image_watermark_checked - вид ображения checkbox'а с подписью "Наложить водяной знак на большое изображение" (1 -  отображать выбранным (по умолчанию), 0 - невыбранным);
						'place_watermark_checkbox_checked' => $oInformationsystem->watermark_default_use_large_image,

						// onclick_delete_big_image - значение onclick для удаления большой картинки
						'delete_onclick' => "$.adminLoad({path: '{$sFormPath}', additionalParams: 'hostcms[checked][{$this->_datasetId}][{$this->_object->id}]=1', action: 'deleteLargeImage', windowId: '{$windowId}'}); return false",

						'caption' => Core::_('Informationsystem_Group.image_large'),

						// used_big_image_preserve_aspect_ratio_checked -  вид ображения checkbox'а с подписью "Сохранять пропорции изображения" (1 -  отображать выбранным (по умолчанию), 0 - невыбранным);
						'preserve_aspect_ratio_checkbox_checked' => $oInformationsystem->preserve_aspect_ratio_group
						)
					)
					->smallImage(array(
						// image_small_max_width - значение максимальной ширины малого изображения;
						'max_width' => $oInformationsystem->group_image_small_max_width,

						// image_small_max_height - значение максимальной высоты малого изображения;
						'max_height' => $oInformationsystem->group_image_small_max_height,

						// small_image_path - адрес малого загруженного изображения
						'path' => $oSmallFilePath,

						// make_small_image_from_big_checked - вид ображения checkbox'а с подписью "Создать малое изображение из большого" выбранным (1 -  отображать выбранным (по умолчанию), 0 - невыбранным);
						'create_small_image_from_large_checked' => $this->_object->image_small == '',

						// small_image_watermark_checked - вид ображения checkbox'а с подписью "Наложить водяной знак на малое изображение" (1 -  отображать выбранным (по умолчанию), 0 - невыбранным);
						'place_watermark_checkbox_checked' => $oInformationsystem->watermark_default_use_small_image,

						// onclick_delete_small_image - значение onclick для удаления малой картинки
						'delete_onclick' => "$.adminLoad({path: '{$sFormPath}', additionalParams: 'hostcms[checked][{$this->_datasetId}][{$this->_object->id}]=1', action: 'deleteSmallImage', windowId: '{$windowId}'}); return false",

						// load_small_image_caption - заголовок поля загрузки малого изображения
						'caption' => Core::_('Informationsystem_Group.image_small'),

						//'name' => 'small_' . $this->largeImage['name'],
						//'id' => 'small_' . $this->largeImage['id'],

						'show_params' => TRUE,

						'preserve_aspect_ratio_checkbox_checked' => $oInformationsystem->preserve_aspect_ratio_group_small
						)
					);

				$oMainRow4->add($oImageField);

				// Path
				$this->getField('path')
					->format(array('maxlen' => array('value' => 255)));

				$oMainTab->move($this->getField('path'), $oMainRow5);

				// Sorting
				$this->getField('sorting')
					->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6'));

				$oMainTab->move($this->getField('sorting'), $oMainRow6);

				// Siteuser
				$oAdditionalTab->delete($this->getField('siteuser_group_id'));

				if (Core::moduleIsActive('siteuser'))
				{
					$oSiteuser_Controller_Edit = new Siteuser_Controller_Edit($this->_Admin_Form_Action);
					$aSiteuser_Groups = $oSiteuser_Controller_Edit->fillSiteuserGroups($this->_object->Informationsystem->site_id);
				}
				else
				{
					$aSiteuser_Groups = array();
				}

				// Список групп пользователей
				$oSelect_SiteuserGroups = Admin_Form_Entity::factory('Select')
					->options(array(
						0 => Core::_('Informationsystem.information_all'),
						-1 => Core::_('Informationsystem_Group.information_parent')
						) + $aSiteuser_Groups
					)
					->name('siteuser_group_id')
					->value($this->_object->siteuser_group_id)
					->caption(Core::_('Informationsystem_Group.siteuser_group_id'))
					->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6'));

				$oMainRow6->add($oSelect_SiteuserGroups);

				// Active
				$this->getField('active')
					->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6 col-xs-6'));
				$this->getField('indexing')
					->divAttr(array('class' => 'form-group col-lg-6 col-md-6 col-sm-6 col-xs-6'));

				$oMainTab->move($this->getField('active'), $oMainRow7);
				$oMainTab->move($this->getField('indexing'), $oMainRow7);

				// SEO
				$oInformationsystemTabSeo = Admin_Form_Entity::factory('Tab')
					->caption(Core::_('Informationsystem_Group.information_groups_form_tab_seo'))
					->name('Seo');

				$this->addTabAfter($oInformationsystemTabSeo, $oMainTab);

				$oInformationsystemTabSeo
					->add($oSeoRow1 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oSeoRow2 = Admin_Form_Entity::factory('Div')->class('row'))
					->add($oSeoRow3 = Admin_Form_Entity::factory('Div')->class('row'));

				$oMainTab
					->move($this->getField('seo_title'), $oSeoRow1)
					->move($this->getField('seo_description'), $oSeoRow2)
					->move($this->getField('seo_keywords'), $oSeoRow3);

			break;
		}

		$this->title($title);

		return $this;
	}

	/**
	 * Information system groups tree
	 * @var array
	 */
	static protected $_aGroupTree = array();

	/**
	 * Build visual representation of group tree
	 * @param int $iInformationsystemId information system ID
	 * @param int $iInformationsystemGroupParentId parent ID
	 * @param int $aExclude exclude group ID
	 * @param int $iLevel current nesting level
	 * @return array
	 */
	static public function fillInformationsystemGroup($iInformationsystemId, $iInformationsystemGroupParentId = 0, $aExclude = array(), $iLevel = 0)
	{
		$iInformationsystemId = intval($iInformationsystemId);
		$iInformationsystemGroupParentId = intval($iInformationsystemGroupParentId);
		$iLevel = intval($iLevel);

		if ($iLevel == 0)
		{
			$aTmp = Core_QueryBuilder::select('id', 'parent_id', 'name')
				->from('informationsystem_groups')
				->where('informationsystem_id', '=', $iInformationsystemId)
				->where('deleted', '=', 0)
				->orderBy('sorting')
				->orderBy('name')
				->execute()->asAssoc()->result();

			foreach ($aTmp as $aGroup)
			{
				self::$_aGroupTree[$aGroup['parent_id']][] = $aGroup;
			}
		}

		$aReturn = array();

		if (isset(self::$_aGroupTree[$iInformationsystemGroupParentId]))
		{
			$countExclude = count($aExclude);
			foreach (self::$_aGroupTree[$iInformationsystemGroupParentId] as $childrenGroup)
			{
				if ($countExclude == 0 || !in_array($childrenGroup['id'], $aExclude))
				{
					$aReturn[$childrenGroup['id']] = str_repeat('  ', $iLevel) . $childrenGroup['name'];
					$aReturn += self::fillInformationsystemGroup($iInformationsystemId, $childrenGroup['id'], $aExclude, $iLevel + 1);
				}
			}
		}

		$iLevel == 0 && self::$_aGroupTree = array();

		return $aReturn;
	}

	/**
	 * Processing of the form. Apply object fields.
	 * @hostcms-event Informationsystem_Item_Controller_Edit.onAfterRedeclaredApplyObjectProperty
	 */
	protected function _applyObjectProperty()
	{
		parent::_applyObjectProperty();

		$informationsystem_id = Core_Array::getGet('informationsystem_id');

		$oInformationsystem = is_null($this->_object->id)
			? Core_Entity::factory('Informationsystem', $informationsystem_id)
			: $this->_object->Informationsystem;

		$modelName = $this->_object->getModelName();

		// Обработка ключевых слов группы
		if (Core::moduleIsActive('tag') && $modelName == 'informationsystem_item')
		{
			$item_tags = trim(Core_Array::getPost('tags'));

			if ($item_tags == '' && $oInformationsystem->apply_tags_automatically ||
				$oInformationsystem->apply_keywords_automatically && $this->_object->seo_keywords == '')
			{
				// Получаем хэш названия, описания и текста инфоэлемента
				$array_text = Core_Str::getHashes(Core_Array::getPost('name') . Core_Array::getPost('description') . ' ' . Core_Array::getPost('text', ''), array('hash_function' => 'crc32'));
				$array_text = array_unique($array_text);

				// Получаем список меток
				$aTags = Core_Entity::factory('Tag')->findAll();

				$coeff_intersect = array ();

				foreach($aTags as $oTag)
				{
					// Получаем хэш тэга
					$array_tags = Core_Str::getHashes($oTag->name, array('hash_function' => 'crc32'));

					// Получаем коэффициент схожести текста элемента с тэгом
					$array_tags = array_unique($array_tags);

					// Текст метки меньше текста инфоэлемента, т.к. должна входить метка в текст инфоэлемента, а не наоборот
					if (count($array_text) >= count($array_tags))
					{
						// Расчитываем пересечение
						$intersect = count(array_intersect($array_text, $array_tags));

						$coefficient = count($array_tags) != 0
							? $intersect / count($array_tags)
							: 0;

						// Найдено полное вхождение
						if ($coefficient == 1 && !in_array($oTag->id, $coeff_intersect))
						{
							$coeff_intersect[] = $oTag->id;
						}
					}
				}
			}

			// Автоматическое применение ключевых слов
			if ($oInformationsystem->apply_keywords_automatically && $this->_object->seo_keywords == '')
			{
				// Найдено соответствие с тэгами
				if (count($coeff_intersect))
				{
					$aTmp = array();
					foreach ($coeff_intersect as $tag_id)
					{
						$oTag = Core_Entity::factory('Tag', $tag_id);
						$aTmp[] = $oTag->name;
					}

					$this->_object->seo_keywords = implode(', ', $aTmp);
				}
			}

			if ($item_tags == '' && $oInformationsystem->apply_tags_automatically && count($coeff_intersect))
			{
				// Удаляем связь с метками
				$this->_object->Tag_Informationsystem_Items->deleteAll();

				foreach ($coeff_intersect as $tag_id)
				{
					$oTag = Core_Entity::factory('Tag', $tag_id);
					$this->_object->add($oTag);
				}
			}
			else
			{
				$this->_object->applyTags($item_tags);
			}
		}

		switch ($modelName)
		{
			case 'informationsystem_item':
				// Проверяем подключен ли модуль типографики.
				if (Core::moduleIsActive('typograph'))
				{
					// Проверяем, нужно ли применять типографику к описанию информационного элемента.
					if (Core_Array::getPost('use_typograph_description', 0))
					{
						$this->_object->description = Typograph_Controller::instance()->process($this->_object->description, Core_Array::getPost('use_trailing_punctuation_description', 0));
					}

					// Проверяем, нужно ли применять типографику к информационного элемента тексту.
					if (Core_Array::getPost('use_typograph_text', 0))
					{
						// Создаем объект типографа и типографируем текст.


						$this->_object->text = Typograph_Controller::instance()->process($this->_object->text, Core_Array::getPost('use_trailing_punctuation_text', 0));
					}
				}

				if ($this->_object->start_datetime == '')
				{
					$this->_object->start_datetime = '0000-00-00 00:00:00';
				}

				if ($this->_object->end_datetime == '')
				{
					$this->_object->end_datetime = '0000-00-00 00:00:00';
				}

				// Properties
				Property_Controller_Tab::factory($this->_Admin_Form_Controller)
					->setObject($this->_object)
					->linkedObject(Core_Entity::factory('Informationsystem_Item_Property_List', $oInformationsystem->id))
					->applyObjectProperty();

				break;
			case 'informationsystem_group':
			default:
				// Проверяем подключен ли модуль типографики.
				if (Core::moduleIsActive('typograph'))
				{
					// Проверяем, нужно ли применять типографику к описанию информационной группы.
					if (Core_Array::getPost('use_typograph', 0))
					{
						$this->_object->description = Typograph_Controller::instance()->process($this->_object->description, Core_Array::getPost('use_trailing_punctuation', 0));
					}
				}

				// Properties
				Property_Controller_Tab::factory($this->_Admin_Form_Controller)
					->setObject($this->_object)
					->linkedObject(Core_Entity::factory('Informationsystem_Group_Property_List', $oInformationsystem->id))
					->applyObjectProperty();
		}

		// Clear tagged cache
		$this->_object->clearCache();

		$param = array();

		$large_image = '';
		$small_image = '';

		$aCore_Config = Core::$mainConfig;

		$create_small_image_from_large = Core_Array::getPost('create_small_image_from_large_small_image');

		$bLargeImageIsCorrect =
			// Поле файла большого изображения существует
			!is_null($aFileData = Core_Array::getFiles('image', NULL))
			// и передан файл
			&& intval($aFileData['size']) > 0;

		if ($bLargeImageIsCorrect)
		{
			// Проверка на допустимый тип файла
			if (Core_File::isValidExtension($aFileData['name'], $aCore_Config['availableExtension']))
			{
				// Удаление файла большого изображения
				if ($this->_object->image_large)
				{
					$this->_object->deleteLargeImage();
				}

				$file_name = $aFileData['name'];

				// Не преобразовываем название загружаемого файла
				if (!$oInformationsystem->change_filename)
				{
					$large_image = $file_name;
				}
				else
				{
					// Определяем расширение файла
					$ext = Core_File::getExtension($aFileData['name']);

					$large_image =
						($modelName == 'informationsystem_item' ? 'information_items_' : 'information_groups_') .
						$this->_object->id . '.' . $ext;
				}
			}
			else
			{
				$this->addMessage(	Core_Message::get(
					Core::_('Core.extension_does_not_allow', Core_File::getExtension($aFileData['name'])),
						'error'
					)
				);
			}
		}

		$aSmallFileData = Core_Array::getFiles('small_image', NULL);
		$bSmallImageIsCorrect =
			// Поле файла малого изображения существует
			!is_null($aSmallFileData)
			&& $aSmallFileData['size'];

		// Задано малое изображение и при этом не задано создание малого изображения
		// из большого или задано создание малого изображения из большого и
		// при этом не задано большое изображение.

		if ($bSmallImageIsCorrect || $create_small_image_from_large && $bLargeImageIsCorrect)
		{
			// Удаление файла малого изображения
			if ($this->_object->image_small)
			{
				$this->_object->deleteSmallImage();
			}

			// Явно указано малое изображение
			if ($bSmallImageIsCorrect
				&& Core_File::isValidExtension($aSmallFileData['name'], $aCore_Config['availableExtension']))
			{
				// Для инфогруппы ранее задано изображение
				if ($this->_object->image_large != '')
				{
					// Существует ли большое изображение
					$param['large_image_isset'] = true;
					$create_large_image = false;
				}
				else // Для информационной группы ранее не задано большое изображение
				{
					$create_large_image = empty($large_image);
				}

				$file_name = $aSmallFileData['name'];

				// Не преобразовываем название загружаемого файла
				if (!$oInformationsystem->change_filename)
				{
					if ($create_large_image)
					{
						$large_image = $file_name;
						$small_image = 'small_' . $large_image;
					}
					else
					{
						$small_image = $file_name;
					}
				}
				else
				{
					// Определяем расширение файла
					$ext = Core_File::getExtension($file_name);
					//$small_image = 'small_information_groups_' . $this->_object->id . '.' . $ext;

					$small_image =
						($modelName == 'informationsystem_item' ? 'small_information_items_' : 'small_information_groups_') .
						$this->_object->id . '.' . $ext;

				}
			}
			elseif ($create_small_image_from_large && $bLargeImageIsCorrect)
			{
				$small_image = 'small_' . $large_image;
			}
			// Тип загружаемого файла является недопустимым для загрузки файла
			else
			{
				$this->addMessage(	Core_Message::get(		Core::_('Core.extension_does_not_allow', Core_File::getExtension($aSmallFileData['name'])),
						'error'
					)
				);
			}
		}

		if ($bLargeImageIsCorrect || $bSmallImageIsCorrect)
		{
			if ($bLargeImageIsCorrect)
			{
				// Путь к файлу-источнику большого изображения;
				$param['large_image_source'] = $aFileData['tmp_name'];
				// Оригинальное имя файла большого изображения
				$param['large_image_name'] = $aFileData['name'];
			}

			if ($bSmallImageIsCorrect)
			{
				// Путь к файлу-источнику малого изображения;
				$param['small_image_source'] = $aSmallFileData['tmp_name'];
				// Оригинальное имя файла малого изображения
				$param['small_image_name'] = $aSmallFileData['name'];
			}

			if ($modelName == 'informationsystem_group')
			{
				// Путь к создаваемому файлу большого изображения;
				$param['large_image_target'] = !empty($large_image)
					? $this->_object->getGroupPath() . $large_image
					: '';

				// Путь к создаваемому файлу малого изображения;
				$param['small_image_target'] = !empty($small_image)
					? $this->_object->getGroupPath() . $small_image
					: '' ;
			}
			else
			{
				// Путь к создаваемому файлу большого изображения;
				$param['large_image_target'] = !empty($large_image)
					? $this->_object->getItemPath() . $large_image
					: '';

				// Путь к создаваемому файлу малого изображения;
				$param['small_image_target'] = !empty($small_image)
					? $this->_object->getItemPath() . $small_image
					: '' ;
			}

			// Использовать большое изображение для создания малого
			$param['create_small_image_from_large'] = !is_null(Core_Array::getPost('create_small_image_from_large_small_image'));

			// Значение максимальной ширины большого изображения
			$param['large_image_max_width'] = Core_Array::getPost('large_max_width_image', 0);

			// Значение максимальной высоты большого изображения
			$param['large_image_max_height'] = Core_Array::getPost('large_max_height_image', 0);

			// Значение максимальной ширины малого изображения;
			$param['small_image_max_width'] = Core_Array::getPost('small_max_width_small_image');

			// Значение максимальной высоты малого изображения;
			$param['small_image_max_height'] = Core_Array::getPost('small_max_height_small_image');

			// Путь к файлу с "водяным знаком"
			$param['watermark_file_path'] = $oInformationsystem->getWatermarkFilePath();

			// Позиция "водяного знака" по оси X
			$param['watermark_position_x'] = Core_Array::getPost('watermark_position_x_image');

			// Позиция "водяного знака" по оси Y
			$param['watermark_position_y'] = Core_Array::getPost('watermark_position_y_image');

			// Наложить "водяной знак" на большое изображение (true - наложить (по умолчанию), false - не наложить);
			$param['large_image_watermark'] = !is_null(Core_Array::getPost('large_place_watermark_checkbox_image'));

			// Наложить "водяной знак" на малое изображение (true - наложить (по умолчанию), false - не наложить);
			$param['small_image_watermark'] = !is_null(Core_Array::getPost('small_place_watermark_checkbox_small_image'));

			// Сохранять пропорции изображения для большого изображения
			$param['large_image_preserve_aspect_ratio'] = !is_null(Core_Array::getPost('large_preserve_aspect_ratio_image'));

			// Сохранять пропорции изображения для малого изображения
			$param['small_image_preserve_aspect_ratio'] = !is_null(Core_Array::getPost('small_preserve_aspect_ratio_small_image'));

			$this->_object->createDir();

			$result = Core_File::adminUpload($param);

			if ($result['large_image'])
			{
				$this->_object->image_large = $large_image;

				if ($modelName == 'informationsystem_item')
				{
					$this->_object->setLargeImageSizes();
				}
			}

			if ($result['small_image'])
			{
				$this->_object->image_small = $small_image;

				if ($modelName == 'informationsystem_item')
				{
					$this->_object->setSmallImageSizes();
				}
			}

			//$this->_object->save();
		}

		$this->_object->save();

		if (Core::moduleIsActive('search') && $this->_object->indexing && $this->_object->active)
		{
			Search_Controller::indexingSearchPages(array($this->_object->indexing()));
		}

		if (Core::moduleIsActive('maillist') && Core_Array::getPost('maillist_id'))
		{
			$oMaillist = Core_Entity::factory('Maillist', Core_Array::getPost('maillist_id'));
			$oMaillist_Fascicle = Core_Entity::factory('Maillist_Fascicle');

			$oMaillist_Fascicle->subject = $this->_object->name;
			$oMaillist_Fascicle->html = str_replace("%TEXT", $this->_object->text, $oMaillist->template);
			$oMaillist_Fascicle->createTextFromHtml();
			$oMaillist_Fascicle->datetime = Core_Date::timestamp2sql(time());
			$oMaillist_Fascicle->sent_datetime = '0000-00-00 00:00:00';
			$oMaillist_Fascicle->changed = 0;

			$oMaillist_Fascicle->save();
			$oMaillist->add($oMaillist_Fascicle);
		}

		Core_Event::notify(get_class($this) . '.onAfterRedeclaredApplyObjectProperty', $this, array($this->_Admin_Form_Controller));
	}

	/**
	 * Executes the business logic.
	 * @param mixed $operation Operation name
	 * @return self
	 */
	public function execute($operation = NULL)
	{
		if (!is_null($operation) && $operation != '')
		{
			//$id = Core_Array::getPost('id');
			$informationsystem_id = Core_Array::getPost('informationsystem_id');
			$path = Core_Array::getPost('path');

			if ($path == '')
			{
				$this->_object->name = Core_Array::getPost('name');
				$this->_object->path = Core_Array::getPost('path');
				// id еще не определен, поэтому makePath() не может работать корректно
				//$this->_object->makePath();
				$path = $this->_object->path;

				$this->addSkipColumn('path');
			}

			$modelName = $this->_object->getModelName();

			switch ($modelName)
			{
				case 'informationsystem_item':
					$informationsystem_group_id = Core_Array::getPost('informationsystem_group_id');

					$oSameInformationsystemItem = Core_Entity::factory('Informationsystem', $informationsystem_id)->Informationsystem_Items->getByGroupIdAndPath($informationsystem_group_id, $path);

					if (!is_null($oSameInformationsystemItem) && $oSameInformationsystemItem->id != Core_Array::getPost('id'))
					{
						$this->addMessage(Core_Message::get(Core::_('Informationsystem_Item.error_information_group_URL_item'), 'error')
						);
						return TRUE;
					}

					$oSameInformationsystemGroup = Core_Entity::factory('Informationsystem', $informationsystem_id)->Informationsystem_Groups->getByParentIdAndPath($informationsystem_group_id, $path);

					if (!is_null($oSameInformationsystemGroup))
					{
						$this->addMessage(Core_Message::get(Core::_('Informationsystem_Item.error_information_group_URL_item_URL') , 'error')
						);
						return TRUE;
					}
				break;
				case 'informationsystem_group':
					$parent_id = Core_Array::getPost('parent_id');

					$oSameInformationsystemGroup = Core_Entity::factory('Informationsystem', $informationsystem_id)->Informationsystem_Groups->getByParentIdAndPath($parent_id, $path);

					if (!is_null($oSameInformationsystemGroup) && $oSameInformationsystemGroup->id != Core_Array::getPost('id'))
					{
						$this->addMessage(
							Core_Message::get(Core::_('Informationsystem_Group.error_URL_information_group'), 'error')
						);
						return TRUE;
					}

					$oSameInformationsystemItem = Core_Entity::factory('Informationsystem', $informationsystem_id)->Informationsystem_Items->getByGroupIdAndPath($parent_id, $path);

					if (!is_null($oSameInformationsystemItem))
					{
						$this->addMessage(
							Core_Message::get(Core::_('Informationsystem_Group.error_information_group_URL_add_edit_URL'), 'error')
						);
						return TRUE;
					}
				break;
			}
		}

		return parent::execute($operation);
	}
}