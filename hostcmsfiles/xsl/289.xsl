<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>

	<xsl:decimal-format name="my" decimal-separator="," grouping-separator=" "/>

	<xsl:template match="/">
		<xsl:apply-templates select="/shop"/>
	</xsl:template>

	<xsl:variable name="n" select="number(3)"/>

	<xsl:template match="/shop">

		<!-- Получаем ID родительской группы и записываем в переменную $group -->
		<xsl:variable name="group" select="group"/>

		<xsl:choose>
			<xsl:when test="$group = 0">

				<div class="page-title category-title">
					<h1>
						<i class="fa fa-bookmark-o"></i><xsl:value-of disable-output-escaping="yes" select="name"/>
					</h1>
				</div>

				<!-- Описание выводится при отсутствии фильтрации по тэгам -->
				<xsl:if test="count(tag) = 0 and page = 0 and description != ''">
					<div hostcms:id="{@id}" hostcms:field="description" hostcms:entity="shop" hostcms:type="wysiwyg"><xsl:value-of disable-output-escaping="yes" select="description"/></div>
				</xsl:if>
			</xsl:when>
			<xsl:otherwise>
				<xsl:variable name="currentGroup" select=".//shop_group[@id=$group]" />

				<!-- Путь к группе -->
				<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
					<xsl:apply-templates select="$currentGroup" mode="breadCrumbs"/>
				</div>

				<!-- Название группы -->
				<div class="page-title category-title">
				<h1><i class="fa fa-folder-open-o"></i><xsl:value-of disable-output-escaping="yes" select="$currentGroup/name"/></h1>
				</div>

				<!-- Описание группы -->
				<xsl:value-of disable-output-escaping="yes" select="$currentGroup/description"/>

			</xsl:otherwise>
		</xsl:choose>

		<!-- Обработка выбранных тэгов -->
		<xsl:if test="count(tag)">
		<p class="h2">Метка — <strong><xsl:value-of select="tag/name"/></strong>.</p>
			<xsl:if test="tag/description != ''">
				<p><xsl:value-of select="tag/description" disable-output-escaping="yes" /></p>
			</xsl:if>
		</xsl:if>

		<xsl:variable name="count">1</xsl:variable>

		<!-- Отображение подгрупп данной группы, только если подгруппы есть и не идет фильтра по меткам -->
		<xsl:if test="count(tag) = 0 and count(shop_producer) = 0 and count(//shop_group[parent_id=$group]) &gt; 0">
			<div class="group_list">
				<xsl:apply-templates select=".//shop_group[parent_id=$group][position() mod $n = 1]"/>
			</div>
		</xsl:if>

		<xsl:if test="count(shop_item) &gt; 0 or /shop/filter = 1">
			<!-- дополнение пути для action, если выбрана метка -->
		<xsl:variable name="form_tag_url"><xsl:if test="count(tag) = 1">tag/<xsl:value-of select="tag/urlencode"/>/</xsl:if></xsl:variable>

			<xsl:variable name="path"><xsl:choose>
				<xsl:when test="/shop//shop_group[@id=$group]/node()"><xsl:value-of select="/shop//shop_group[@id=$group]/url"/></xsl:when>
				<xsl:otherwise><xsl:value-of select="/shop/url"/><xsl:if test="/shop/shop_producer/node()">producer-<xsl:value-of select="/shop/shop_producer/@id" />/</xsl:if></xsl:otherwise>
			</xsl:choose></xsl:variable>

			<form method="get" action="{$path}{$form_tag_url}">
				<xsl:if test="1=0">
					<div class="shop_filter">
						<div class="sorting">
							<select name="sorting" onchange="$(this).parents('form:first').submit()">
								<option>Сортировать</option>
								<option value="1">
								<xsl:if test="/shop/sorting = 1"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
									По цене (сначала дешевые)
								</option>
								<option value="2">
								<xsl:if test="/shop/sorting = 2"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
									По цене (сначала дорогие)
								</option>
								<option value="3">
								<xsl:if test="/shop/sorting = 3"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
									По названию
								</option>
							</select>
						</div>

						<div class="priceFilter">
							<xsl:text>Цена от: </xsl:text>
							<input name="price_from" size="5" type="text">
								<xsl:if test="/shop/price_from != 0">
									<xsl:attribute name="value"><xsl:value-of select="/shop/price_from"/></xsl:attribute>
								</xsl:if>
							</input>

							<xsl:text>до: </xsl:text>
							<input name="price_to" size="5" type="text">
								<xsl:if test="/shop/price_to != 0">
									<xsl:attribute name="value"><xsl:value-of select="/shop/price_to"/></xsl:attribute>
								</xsl:if>
							</input>
						</div>

						<!-- Фильтр по дополнительным свойствам товара: -->
						<xsl:if test="count(shop_item_properties//property[filter != 0 and (type = 0 or type = 1 or type = 3 or type = 7 or type = 11)])">
							<span class="table_row"></span>
							<xsl:apply-templates select="shop_item_properties//property[filter != 0 and (type = 0 or type = 1 or type = 3 or type = 7 or type = 11)]" mode="propertyList"/>
						</xsl:if>

						<input name="filter" class="button" value="Применить" type="submit"/>
					</div>
				</xsl:if>
				<!-- Таблица с элементами для сравнения -->
				<xsl:if test="count(/shop/compare_items/compare_item) &gt; 0">
					<table cellpadding="5px" cellspacing="0" border="0">
						<tr>
							<td>
								<input type="checkbox" onclick="SelectAllItemsByPrefix(this.checked, 'del_compare_id_')" />
							</td>
							<td>
								<b>Сравниваемые элементы</b>
							</td>
						</tr>
						<xsl:apply-templates select="compare_items/compare_item"/>
					</table>
				</xsl:if>

				<!-- Выводим товары магазина -->
				<div class="row products-grid">
					<xsl:apply-templates select="shop_item" />
				</div>

				<a href="{/shop/url}compare_items/">
					<div class="actions">
						<xsl:if test="count(/shop/comparing/shop_item) = 0">
							<xsl:attribute name="style">display: none</xsl:attribute>
						</xsl:if>

						<button class="button btn-cart" title="Compare" type="button">
							<i class="fa fa-dashboard bg-color5">
								<b></b>
							</i>
							<span class="bg-color2">
								<span>Сравнить товары</span>
							</span>
						</button>
					</div>
				</a>

				<xsl:if test="total &gt; 0 and limit &gt; 0">

					<xsl:variable name="count_pages" select="ceiling(total div limit)"/>

					<xsl:variable name="visible_pages" select="5"/>

					<xsl:variable name="real_visible_pages"><xsl:choose>
							<xsl:when test="$count_pages &lt; $visible_pages"><xsl:value-of select="$count_pages"/></xsl:when>
							<xsl:otherwise><xsl:value-of select="$visible_pages"/></xsl:otherwise>
					</xsl:choose></xsl:variable>

					<!-- Считаем количество выводимых ссылок перед текущим элементом -->
					<xsl:variable name="pre_count_page"><xsl:choose>
							<xsl:when test="page - (floor($real_visible_pages div 2)) &lt; 0">
								<xsl:value-of select="page"/>
							</xsl:when>
							<xsl:when test="($count_pages - page - 1) &lt; floor($real_visible_pages div 2)">
								<xsl:value-of select="$real_visible_pages - ($count_pages - page - 1) - 1"/>
							</xsl:when>
							<xsl:otherwise>
								<xsl:choose>
									<xsl:when test="round($real_visible_pages div 2) = $real_visible_pages div 2">
										<xsl:value-of select="floor($real_visible_pages div 2) - 1"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:value-of select="floor($real_visible_pages div 2)"/>
									</xsl:otherwise>
								</xsl:choose>
							</xsl:otherwise>
					</xsl:choose></xsl:variable>

					<!-- Считаем количество выводимых ссылок после текущего элемента -->
					<xsl:variable name="post_count_page"><xsl:choose>
							<xsl:when test="0 &gt; page - (floor($real_visible_pages div 2) - 1)">
								<xsl:value-of select="$real_visible_pages - page - 1"/>
							</xsl:when>
							<xsl:when test="($count_pages - page - 1) &lt; floor($real_visible_pages div 2)">
								<xsl:value-of select="$real_visible_pages - $pre_count_page - 1"/>
							</xsl:when>
							<xsl:otherwise>
								<xsl:value-of select="$real_visible_pages - $pre_count_page - 1"/>
							</xsl:otherwise>
					</xsl:choose></xsl:variable>

					<xsl:variable name="i"><xsl:choose>
							<xsl:when test="page + 1 = $count_pages"><xsl:value-of select="page - $real_visible_pages + 1"/></xsl:when>
							<xsl:when test="page - $pre_count_page &gt; 0"><xsl:value-of select="page - $pre_count_page"/></xsl:when>
							<xsl:otherwise>0</xsl:otherwise>
					</xsl:choose></xsl:variable>

					<nav>
						<ul class="pagination">
							<xsl:call-template name="for">
								<xsl:with-param name="limit" select="limit"/>
								<xsl:with-param name="page" select="page"/>
								<xsl:with-param name="items_count" select="total"/>
								<xsl:with-param name="i" select="$i"/>
								<xsl:with-param name="post_count_page" select="$post_count_page"/>
								<xsl:with-param name="pre_count_page" select="$pre_count_page"/>
								<xsl:with-param name="visible_pages" select="$real_visible_pages"/>
							</xsl:call-template>
						</ul>
					</nav>
				</xsl:if>

				<!-- Передаем фильтр -->
	<xsl:variable name="filter"><xsl:if test="/shop/filter/node()">&amp;filter=1&amp;sorting=<xsl:value-of select="/shop/sorting"/>&amp;price_from=<xsl:value-of select="/shop/price_from"/>&amp;price_to=<xsl:value-of select="/shop/price_to"/><xsl:for-each select="/shop/*"><xsl:if test="starts-with(name(), 'property_')">&amp;<xsl:value-of select="name()"/>[]=<xsl:value-of select="."/></xsl:if></xsl:for-each></xsl:if></xsl:variable>

				<span class="on-page">Показать по:
				<a href="{$path}?on_page=20{$filter}">20</a><xsl:text> </xsl:text>
				<a href="{$path}?on_page=50{$filter}">50</a><xsl:text> </xsl:text>
					<a href="{$path}?on_page=100{$filter}">100</a>
				</span>

				<div style="clear: both"></div>
			</form>
		</xsl:if>

		<!-- Есть избранные товары -->
		<xsl:if test="favorite/shop_item">
			<div class="page-title category-title">
			<h1><i class="fa fa-heart-o"></i>Избранные товары</h1>
			</div>
			<!-- Выводим товары магазина -->
			<div class="row products-grid">
				<xsl:apply-templates select="favorite/shop_item" />
			</div>
		</xsl:if>

		<!-- Есть просмотренные товары -->
		<xsl:if test="viewed/shop_item">
			<div class="page-title category-title">
			<h1><i class="fa fa-eye"></i>Просмотренные товары</h1>
			</div>
			<!-- Выводим товары магазина -->
			<div class="row products-grid">
				<xsl:apply-templates select="viewed/shop_item[position() &lt; 4]" />
			</div>
		</xsl:if>
	</xsl:template>

	<!-- Шаблон для товара -->
	<xsl:template match="shop_item">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 item">
			<div class="grid_wrap">
				<xsl:if test="discount != 0">
					<div class="ribbon-wrapper">
						<div class="ribbon bg-color2">HOT</div>
					</div>
				</xsl:if>

				<div class="product-image">
					<a href="{url}" title="{name}">
						<xsl:choose>
							<xsl:when test="image_small != ''">
								<img src="{dir}{image_small}" alt="{name}" />
							</xsl:when>
							<xsl:otherwise>
								<i class="fa fa-camera"></i>
							</xsl:otherwise>
						</xsl:choose>
					</a>
				</div>

				<div class="price-box">
					<span id="product-price-12-new" class="regular-price">
						<span class="price">
						<xsl:value-of select="format-number(price, '### ##0,00', 'my')"/><xsl:text> </xsl:text><xsl:value-of disable-output-escaping="yes" select="currency"/><xsl:text> </xsl:text>
						</span>
						<xsl:if test="discount != 0">
							<br/>
							<span class="old-price">
							<xsl:value-of select="format-number(price+discount, '### ##0,00', 'my')"/><xsl:text> </xsl:text><xsl:value-of disable-output-escaping="yes" select="currency"/><xsl:text> </xsl:text>
							</span>
						</xsl:if>
					</span>
					
					<!-- Бонусы для товара -->
					<xsl:if test="count(shop_bonuses/shop_bonus)">
						<div class="product-bonuses">
							+<xsl:value-of select="shop_bonuses/total" /> бонусов
						</div>
					</xsl:if>					
				</div>

				<div class="product-shop">
					<h3 class="product-name">
						<a href="{url}" title="{name}">
							<xsl:value-of disable-output-escaping="yes" select="name"/>
						</a>
					</h3>

					<div class="actions">
						<button class="button btn-cart" onclick="return $.bootstrapAddIntoCart('{/shop/url}cart/', {@id}, 1)" title="Add to Cart" type="button">
							<i class="fa fa-shopping-cart bg-color5">
								<b></b>
							</i>
							<span class="bg-color3">
								<span>В корзину</span>
							</span>
						</button>

						<xsl:variable name="shop_item_id" select="@id" />
						<ul class="add-to-links">
							<li>
								<xsl:if test="/shop/favorite/shop_item[@id = $shop_item_id]/node()">
									<xsl:attribute name="class">link-wishlist-current</xsl:attribute>
								</xsl:if>
							<a class="link-wishlist" onclick="return $.addFavorite('{/shop/url}', {@id}, this)"><i class="fa fa-heart-o"></i>Избранное</a>
							</li>
							<li>
								<xsl:if test="/shop/comparing/shop_item[@id = $shop_item_id]/node()">
									<xsl:attribute name="class">link-compare-current</xsl:attribute>
								</xsl:if>
								<span class="separator">|</span>
							<a class="link-compare" onclick="return $.addCompare('{/shop/url}', {@id}, this)"><i class="fa fa-bar-chart"></i>Сравнить</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<!-- <xsl:if test="position() mod 3 = 0 and position() != last()">
			<xsl:text disable-output-escaping="yes">
				&lt;/div&gt;
				&lt;div class="products-grid row"&gt;
			</xsl:text>
		</xsl:if>-->
	</xsl:template>

	<!-- Шаблон для групп товара -->
	<xsl:template match="shop_group">
		<div class="row">
			<xsl:for-each select=". | following-sibling::shop_group[position() &lt; $n]">
				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-align-center">
					<xsl:if test="image_small != ''">
						<a href="{url}">
							<div>
								<img src="{dir}{image_small}" class="item-image"/>
							</div>
						</a>
					</xsl:if>
			<a href="{url}" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="shop_group"><xsl:value-of disable-output-escaping="yes" select="name"/></a><xsl:text> </xsl:text><span class="shop_count"><xsl:value-of select="items_total_count"/></span>
				</div>
			</xsl:for-each>
		</div>
	</xsl:template>

	<!-- Шаблон выводит рекурсивно ссылки на группы магазина -->
	<xsl:template match="shop_group" mode="breadCrumbs">
		<xsl:param name="parent_id" select="parent_id"/>

		<!-- Получаем ID родительской группы и записываем в переменную $group -->
		<xsl:param name="group" select="/shop/shop_group"/>

		<xsl:apply-templates select="//shop_group[@id=$parent_id]" mode="breadCrumbs"/>

		<xsl:if test="parent_id=0">
			<span typeof="v:Breadcrumb">
				<a title="{/shop/name}" href="{/shop/url}" hostcms:id="{/shop/@id}" hostcms:field="name" hostcms:entity="shop" class="root" property="v:title" rel="v:url">
					<xsl:value-of select="/shop/name"/>
				</a>
			</span>
		</xsl:if>

		<i class="fa fa-angle-right"></i>

		<span typeof="v:Breadcrumb">
			<a title="{name}" href="{url}" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="shop_group" property="v:title" rel="v:url">
				<xsl:value-of disable-output-escaping="yes" select="name"/>
			</a>
		</span>
	</xsl:template>

	<!-- Шаблон для списка товаров для сравнения -->
	<xsl:template match="compare_items/compare_item">
		<xsl:variable name="var_compare_id" select="."/>
		<tr>
			<td>
				<input type="checkbox" name="del_compare_id_{compare_item_id}" id="id_del_compare_id_{compare_item_id}"/>
			</td>
			<td>
				<a href="{/shop/url}{compare_item_url}{compare_url}/">
					<xsl:value-of disable-output-escaping="yes" select="compare_name"/>
				</a>
			</td>
		</tr>
	</xsl:template>

	<!-- Шаблон для фильтра по дополнительным свойствам -->
	<xsl:template match="property" mode="propertyList">
		<xsl:variable name="nodename">property_<xsl:value-of select="@id"/></xsl:variable>
		<xsl:variable name="nodename_from">property_<xsl:value-of select="@id"/>_from</xsl:variable>
		<xsl:variable name="nodename_to">property_<xsl:value-of select="@id"/>_to</xsl:variable>

		<div class="filterField">

			<xsl:if test="filter != 5">
			<legend><xsl:value-of disable-output-escaping="yes" select="name"/><xsl:text> </xsl:text></legend>
			</xsl:if>

			<xsl:choose>
				<!-- Отображаем поле ввода -->
				<xsl:when test="filter = 1">
					<br/>
					<input type="text" name="property_{@id}">
						<xsl:if test="/shop/*[name()=$nodename] != ''">
							<xsl:attribute name="value"><xsl:value-of select="/shop/*[name()=$nodename]"/></xsl:attribute>
						</xsl:if>
					</input>
				</xsl:when>
				<!-- Отображаем список -->
				<xsl:when test="filter = 2">
					<br/>
					<select name="property_{@id}">
						<option value="0">...</option>
						<xsl:apply-templates select="list/list_item"/>
					</select>
				</xsl:when>
				<!-- Отображаем переключатели -->
				<xsl:when test="filter = 3">
					<br/>
					<div class="propertyInput">
						<input type="radio" name="property_{@id}" value="0" id="id_prop_radio_{@id}_0"></input>
						<label for="id_prop_radio_{@id}_0">Любой вариант</label>
						<xsl:apply-templates select="list/list_item"/>
					</div>
				</xsl:when>
				<!-- Отображаем флажки -->
				<xsl:when test="filter = 4">
					<div class="propertyInput">
						<xsl:apply-templates select="list/list_item"/>
					</div>
				</xsl:when>
				<!-- Отображаем флажок -->
				<xsl:when test="filter = 5">
					<input type="checkbox" name="property_{@id}" id="property_{@id}" style="padding-top:4px">
						<xsl:if test="/shop/*[name()=$nodename] != ''">
							<xsl:attribute name="checked"><xsl:value-of select="/shop/*[name()=$nodename]"/></xsl:attribute>
						</xsl:if>
					</input>
					<label for="property_{@id}">
						<xsl:value-of disable-output-escaping="yes" select="name"/><xsl:text> </xsl:text>
					</label>
				</xsl:when>
				<!-- Отображение полей "от и до" -->
				<xsl:when test="filter = 6">
					<br/>
					от: <input type="text" name="property_{@id}_from" size="2" value="{/shop/*[name()=$nodename_from]}"/> до: <input type="text" name="property_{@id}_to" size="2" value="{/shop/*[name()=$nodename_to]}"/>
				</xsl:when>
				<!-- Отображаем список с множественным выбором-->
				<xsl:when test="filter = 7">
					<br/>
					<select name="property_{@id}[]" multiple="multiple">
						<xsl:apply-templates select="list/list_item"/>
					</select>
				</xsl:when>
			</xsl:choose>
		</div>
	</xsl:template>

	<xsl:template match="list/list_item">
		<xsl:if test="../../filter = 2">
			<!-- Отображаем список -->
			<xsl:variable name="nodename">property_<xsl:value-of select="../../@id"/></xsl:variable>
			<option value="{@id}">
			<xsl:if test="/shop/*[name()=$nodename] = @id"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
				<xsl:value-of disable-output-escaping="yes" select="value"/>
			</option>
		</xsl:if>
		<xsl:if test="../../filter = 3">
			<!-- Отображаем переключатели -->
			<xsl:variable name="nodename">property_<xsl:value-of select="../../@id"/></xsl:variable>
			<br/>
			<input type="radio" name="property_{../../@id}" value="{@id}" id="id_property_{../../@id}_{@id}">
				<xsl:if test="/shop/*[name()=$nodename] = @id">
					<xsl:attribute name="checked">checked</xsl:attribute>
				</xsl:if>
			</input>
			<label for="id_property_{../../@id}_{@id}">
				<xsl:value-of disable-output-escaping="yes" select="value"/>
			</label>
		</xsl:if>
		<xsl:if test="../../filter = 4">
			<!-- Отображаем флажки -->
			<xsl:variable name="nodename">property_<xsl:value-of select="../../@id"/></xsl:variable>
			<br/>
			<input type="checkbox" value="{@id}" name="property_{../../@id}[]" id="property_{../../@id}_{@id}">
				<xsl:if test="/shop/*[name()=$nodename] = @id">
					<xsl:attribute name="checked">checked</xsl:attribute>
				</xsl:if>
				<label for="property_{../../@id}_{@id}">
					<xsl:value-of disable-output-escaping="yes" select="value"/>
				</label>
			</input>
		</xsl:if>
		<xsl:if test="../../filter = 7">
			<!-- Отображаем список -->
			<xsl:variable name="nodename">property_<xsl:value-of select="../../@id"/></xsl:variable>
			<option value="{@id}">
				<xsl:if test="/shop/*[name()=$nodename] = @id">
					<xsl:attribute name="selected">
					</xsl:attribute>
				</xsl:if>
				<xsl:value-of disable-output-escaping="yes" select="value"/>
			</option>
		</xsl:if>
	</xsl:template>

	<!-- Метки для товаров -->
	<xsl:template match="tag">
		<a href="{/shop/url}tag/{urlencode}/" class="tag">
			<xsl:value-of select="tag_name"/>
		</a>
	<xsl:if test="position() != last()"><xsl:text>, </xsl:text></xsl:if>
	</xsl:template>

	<!-- Цикл для вывода строк ссылок -->
	<xsl:template name="for">

		<xsl:param name="limit"/>
		<xsl:param name="page"/>
		<xsl:param name="pre_count_page"/>
		<xsl:param name="post_count_page"/>
		<xsl:param name="i" select="0"/>
		<xsl:param name="items_count"/>
		<xsl:param name="visible_pages"/>

		<xsl:variable name="n" select="ceiling($items_count div $limit)"/>

		<xsl:variable name="start_page"><xsl:choose>
				<xsl:when test="$page + 1 = $n"><xsl:value-of select="$page - $visible_pages + 1"/></xsl:when>
				<xsl:when test="$page - $pre_count_page &gt; 0"><xsl:value-of select="$page - $pre_count_page"/></xsl:when>
				<xsl:otherwise>0</xsl:otherwise>
		</xsl:choose></xsl:variable>

		<xsl:if test="$i = $start_page and $page != 0">
			<li>
			<span aria-hidden="true"><i class="fa fa-angle-double-left"></i></span>
			</li>
		</xsl:if>

		<xsl:if test="$i = ($page + $post_count_page + 1) and $n != ($page+1)">
			<li>
			<span aria-hidden="true"><i class="fa fa-angle-double-right"></i></span>
			</li>
		</xsl:if>

		<!-- Передаем фильтр -->
<xsl:variable name="filter"><xsl:if test="/shop/filter/node()">?filter=1&amp;sorting=<xsl:value-of select="/shop/sorting"/>&amp;price_from=<xsl:value-of select="/shop/price_from"/>&amp;price_to=<xsl:value-of select="/shop/price_to"/><xsl:for-each select="/shop/*"><xsl:if test="starts-with(name(), 'property_')">&amp;<xsl:value-of select="name()"/>[]=<xsl:value-of select="."/></xsl:if></xsl:for-each></xsl:if></xsl:variable>

<xsl:variable name="on_page"><xsl:if test="/shop/on_page/node() and /shop/on_page > 0"><xsl:choose><xsl:when test="/shop/filter/node()">&amp;</xsl:when><xsl:otherwise>?</xsl:otherwise></xsl:choose>on_page=<xsl:value-of select="/shop/on_page"/></xsl:if></xsl:variable>

		<xsl:if test="$items_count &gt; $limit and ($page + $post_count_page + 1) &gt; $i">
			<!-- Заносим в переменную $group идентификатор текущей группы -->
			<xsl:variable name="group" select="/shop/group"/>

			<!-- Путь для тэга -->
		<xsl:variable name="tag_path"><xsl:if test="count(/shop/tag) != 0">tag/<xsl:value-of select="/shop/tag/urlencode"/>/</xsl:if></xsl:variable>

			<!-- Путь для сравнения товара -->
		<xsl:variable name="shop_producer_path"><xsl:if test="count(/shop/shop_producer)">producer-<xsl:value-of select="/shop/shop_producer/@id"/>/</xsl:if></xsl:variable>

			<!-- Определяем группу для формирования адреса ссылки -->
<xsl:variable name="group_link"><xsl:choose><xsl:when test="$group != 0"><xsl:value-of select="/shop//shop_group[@id=$group]/url"/></xsl:when><xsl:otherwise><xsl:value-of select="/shop/url"/></xsl:otherwise></xsl:choose></xsl:variable>

			<!-- Определяем адрес ссылки -->
		<xsl:variable name="number_link"><xsl:if test="$i != 0">page-<xsl:value-of select="$i + 1"/>/</xsl:if></xsl:variable>

			<!-- Выводим ссылку на первую страницу -->
			<xsl:if test="$page - $pre_count_page &gt; 0 and $i = $start_page">
				<li>
					<a href="{$group_link}{$tag_path}{$shop_producer_path}{$filter}{$on_page}" class="page_link" style="text-decoration: none;">←</a>
				</li>
			</xsl:if>

			<!-- Ставим ссылку на страницу-->
			<xsl:if test="$i != $page">
				<xsl:if test="($page - $pre_count_page) &lt;= $i and $i &lt; $n">
					<!-- Выводим ссылки на видимые страницы -->
					<li>
						<a href="{$group_link}{$number_link}{$tag_path}{$shop_producer_path}{$filter}{$on_page}" class="page_link">
							<xsl:value-of select="$i + 1"/>
						</a>
					</li>
				</xsl:if>

				<!-- Выводим ссылку на последнюю страницу -->
				<xsl:if test="$i+1 &gt;= ($page + $post_count_page + 1) and $n &gt; ($page + 1 + $post_count_page)">
					<!-- Выводим ссылку на последнюю страницу -->
					<li>
						<a href="{$group_link}page-{$n}/{$tag_path}{$shop_producer_path}{$filter}{$on_page}" class="page_link" style="text-decoration: none;">→</a>
					</li>
				</xsl:if>
			</xsl:if>

			<!-- Ссылка на предыдущую страницу для Ctrl + влево -->
<xsl:if test="$page != 0 and $i = $page"><xsl:variable name="prev_number_link"><xsl:if test="$page &gt; 1">page-<xsl:value-of select="$i"/>/</xsl:if></xsl:variable><a href="{$group_link}{$prev_number_link}{$tag_path}{$shop_producer_path}{$filter}{$on_page}" id="id_prev"></a></xsl:if>

			<!-- Ссылка на следующую страницу для Ctrl + вправо -->
			<xsl:if test="($n - 1) > $page and $i = $page">
				<a href="{$group_link}page-{$page+2}/{$tag_path}{$shop_producer_path}{$filter}{$on_page}" id="id_next"></a>
			</xsl:if>

			<!-- Не ставим ссылку на страницу-->
			<xsl:if test="$i = $page">
				<span class="current">
					<xsl:value-of select="$i+1"/>
				</span>
			</xsl:if>

			<!-- Рекурсивный вызов шаблона. НЕОБХОДИМО ПЕРЕДАВАТЬ ВСЕ НЕОБХОДИМЫЕ ПАРАМЕТРЫ! -->
			<xsl:call-template name="for">
				<xsl:with-param name="i" select="$i + 1"/>
				<xsl:with-param name="limit" select="$limit"/>
				<xsl:with-param name="page" select="$page"/>
				<xsl:with-param name="items_count" select="$items_count"/>
				<xsl:with-param name="pre_count_page" select="$pre_count_page"/>
				<xsl:with-param name="post_count_page" select="$post_count_page"/>
				<xsl:with-param name="visible_pages" select="$visible_pages"/>
			</xsl:call-template>
		</xsl:if>
	</xsl:template>

	<!-- Шаблон для фильтра производителей -->
	<xsl:template match="producers/shop_producer">
		<!-- Заносим в переменную $group идентификатор текущей группы -->
		<xsl:variable name="group" select="/shop/group"/>

		<!-- Определяем группу для формирования адреса ссылки -->
<xsl:variable name="group_link"><xsl:choose><xsl:when test="$group != 0"><xsl:value-of select="/shop//shop_group[@id=$group]/url"/></xsl:when><xsl:otherwise><xsl:value-of select="/shop/url"/></xsl:otherwise></xsl:choose></xsl:variable>

		<a href="{$group_link}?producer_id={@id}"><xsl:value-of disable-output-escaping="yes" select="name"/></a>
	</xsl:template>
</xsl:stylesheet>