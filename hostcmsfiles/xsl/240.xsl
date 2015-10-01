<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">

	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>

	<!-- СписокЭлементовИнфосистемы -->

	<xsl:template match="/">
		<xsl:apply-templates select="/informationsystem"/>
	</xsl:template>

	<xsl:variable name="n" select="number(3)"/>

	<xsl:template match="/informationsystem">

		<!-- Получаем ID родительской группы и записываем в переменную $group -->
		<xsl:variable name="group" select="group"/>

		<!-- Если в находимся корне - выводим название информационной системы -->
		<xsl:choose>
			<xsl:when test="$group = 0">
				<div class="page-title category-title">
					<h1 hostcms:id="{@id}" hostcms:field="name" hostcms:entity="informationsystem">
						<i class="fa fa-newspaper-o"></i><xsl:value-of disable-output-escaping="yes" select="name"/>
					</h1>
				</div>

				<!-- Описание выводится при отсутствии фильтрации по тэгам -->
				<xsl:if test="count(tag) = 0 and page = 0 and description != ''">
					<div hostcms:id="{@id}" hostcms:field="description" hostcms:entity="informationsystem" hostcms:type="wysiwyg"><xsl:value-of disable-output-escaping="yes" select="description"/></div>
				</xsl:if>
			</xsl:when>
			<xsl:otherwise>
				<!-- Путь к группе -->
				<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
					<xsl:apply-templates select=".//informationsystem_group[@id=$group]" mode="breadCrumbs"/>
				</div>

				<div class="page-title category-title" hostcms:id="{$group}" hostcms:field="name" hostcms:entity="informationsystem_group">
					<h1><i class="fa fa-folder-open-o"></i><xsl:value-of disable-output-escaping="yes" select=".//informationsystem_group[@id=$group]/name"/></h1>
				</div>

				<!-- Описание выводим только на первой странице -->
				<xsl:if test="page = 0 and .//informationsystem_group[@id=$group]/description != ''">
					<div hostcms:id="{$group}" hostcms:field="description" hostcms:entity="informationsystem_group" hostcms:type="wysiwyg"><xsl:value-of disable-output-escaping="yes" select=".//informationsystem_group[@id=$group]/description"/></div>
				</xsl:if>
			</xsl:otherwise>
		</xsl:choose>

		<!-- Обработка выбранных тэгов -->
		<xsl:if test="count(tag)">
		<h1 class="item_title">Метка — <xsl:value-of select="tag/name" disable-output-escaping="yes" /></h1>
			<xsl:if test="tag/description != ''">
				<p class="item-description"><xsl:value-of select="tag/description" disable-output-escaping="yes" /></p>
			</xsl:if>
		</xsl:if>

		<!-- Отображение подгрупп данной группы, только если подгруппы есть и не идет фильтра по меткам -->
		<xsl:if test="count(tag) = 0 and count(.//informationsystem_group[parent_id=$group]) &gt; 0">
			<div class="group_list">
				<xsl:apply-templates select=".//informationsystem_group[parent_id=$group][position() mod $n = 1]" mode="groups"/>
			</div>
		</xsl:if>

		<!-- Отображение записи информационной системы -->
		<xsl:if test="informationsystem_item">
			<dl class="news_list">
				<div class="row">
					<xsl:apply-templates select="informationsystem_item"/>
				</div>
			</dl>
		</xsl:if>

		<!-- Строка ссылок на другие страницы информационной системы -->
		<xsl:if test="ОтображатьСсылкиНаСледующиеСтраницы=1">
			<div>
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
			</div>
		</xsl:if>

		<xsl:if test="count(informationsystem_group_properties) and group != 0">
			<div style="margin: 10px 0px;">
				<h2>Атрибуты группы инфоэлементов</h2>

				<xsl:if test="count(informationsystem_group[@id = //group]/property[parent_id = 0])">
					<table border="0">
						<xsl:apply-templates select="informationsystem_group[@id = //group]/property[parent_id = 0]"/>
					</table>
				</xsl:if>

				<xsl:apply-templates select="informationsystem_group_properties"/>
			</div>
		</xsl:if>

		<div style="clear: both"></div>

	</xsl:template>

	<!-- Вывод строки со значением свойства -->
	<xsl:template match="property">
		<tr>
			<td style="padding: 5px" bgcolor="#eeeeee">
				<b><xsl:value-of select="name"/></b>
			</td>
			<td style="padding: 5px" bgcolor="#eeeeee">
				<xsl:choose>
					<xsl:when test="type = 1">
						<a href="{file_path}">Скачать файл</a>
					</xsl:when>
					<xsl:when test="type = 7">
						<xsl:choose>
							<xsl:when test="value = 1">
								<input type="checkbox" checked="" disabled="" />
							</xsl:when>
							<xsl:otherwise>
								<input type="checkbox" disabled="" />
							</xsl:otherwise>
						</xsl:choose>
					</xsl:when>
					<xsl:otherwise>
						<xsl:value-of disable-output-escaping="yes" select="value"/>
					</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</xsl:template>

	<!-- Шаблон выводит рекурсивно ссылки на группы инф. элемента -->
	<xsl:template match="informationsystem_group" mode="breadCrumbs">
		<xsl:variable name="parent_id" select="parent_id"/>

		<xsl:apply-templates select="//informationsystem_group[@id=$parent_id]" mode="breadCrumbs"/>

		<xsl:if test="parent_id=0">
			<span typeof="v:Breadcrumb">
				<a title="{/informationsystem/name}" href="{/informationsystem/url}" hostcms:id="{/informationsystem/@id}" hostcms:field="name" hostcms:entity="informationsystem" class="root" property="v:title" rel="v:url">
					<xsl:value-of disable-output-escaping="yes" select="/informationsystem/name"/>
				</a>
			</span>
		</xsl:if>

		<i class="fa fa-angle-right"></i>

		<span typeof="v:Breadcrumb">
			<a title="{name}" href="{url}" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="informationsystem_group" property="v:title" rel="v:url">
				<xsl:value-of disable-output-escaping="yes" select="name"/>
			</a>
		</span>

	</xsl:template>

	<!-- Шаблон выводит группы свойств для группы инфосистемы -->
	<xsl:template match="informationsystem_group_properties">

	<p><b><xsl:value-of select="information_propertys_groups_dir_name"/></b></p>

		<xsl:variable name="dir_id" select="@id"/>

		<xsl:if test="count(//informationsystem_group[@id = //group]/property[parent_id = $dir_id])">
			<table border="0">
				<xsl:apply-templates select="//informationsystem_group[@id = //group]/property[parent_id = $dir_id]"/>
			</table>
		</xsl:if>

		<xsl:if test="count(informationsystem_group_properties)">
			<blockquote>
				<xsl:apply-templates select="informationsystem_group_properties"/>
			</blockquote>
		</xsl:if>
	</xsl:template>

	<!-- Шаблон выводит ссылки подгруппы информационного элемента -->
	<xsl:template match="informationsystem_group" mode="groups">
		<div class="row">
			<xsl:for-each select=". | following-sibling::informationsystem_group[position() &lt; $n]">
				<div class="col-xs-4 col-sm-4 col-md-2 col-lg-4 text-align-center">
					<xsl:if test="image_small != ''">
						<a href="{url}">
							<div>
								<img src="{dir}{image_small}" class="item-image"/>
							</div>
						</a>
					</xsl:if>
					<a href="{url}"><xsl:value-of disable-output-escaping="yes" select="name"/></a><xsl:text> </xsl:text><span class="shop_count"><xsl:value-of select="items_total_count"/></span>
				</div>
			</xsl:for-each>
		</div>
	</xsl:template>

	<!-- Шаблон вывода информационного элемента -->
	<xsl:template match="informationsystem_item">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="thumbnail">
				<!-- Дата время -->
				<div class="box clearfix">
					<div>
						<strong>
							<xsl:value-of select="substring-before(date, '.')"/>
						</strong>
						<xsl:variable name="month_year" select="substring-after(date, '.')"/>
						<xsl:variable name="month" select="substring-before($month_year, '.')"/>
						<span>
						<xsl:choose>
							<xsl:when test="$month = 1"> января </xsl:when>
							<xsl:when test="$month = 2"> февраля </xsl:when>
							<xsl:when test="$month = 3"> марта </xsl:when>
							<xsl:when test="$month = 4"> апреля </xsl:when>
							<xsl:when test="$month = 5"> мая </xsl:when>
							<xsl:when test="$month = 6"> июня </xsl:when>
							<xsl:when test="$month = 7"> июля </xsl:when>
							<xsl:when test="$month = 8"> августа </xsl:when>
							<xsl:when test="$month = 9"> сентября </xsl:when>
							<xsl:when test="$month = 10"> октября </xsl:when>
							<xsl:when test="$month = 11"> ноября </xsl:when>
							<xsl:otherwise> декабря </xsl:otherwise>
						</xsl:choose>
						</span>
						<!-- <span><xsl:value-of select="substring-after($month_year, '.')"/><xsl:text> г.</xsl:text></span>-->
					</div>

					<hr/>
					<a href="{url}" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="informationsystem_item">
						<xsl:value-of disable-output-escaping="yes" select="name"/>
					</a>
				</div>

				<div class="caption">
					<p><xsl:value-of disable-output-escaping="yes" select="description"/></p>

					<xsl:if test="count(tag) &gt; 0 or count(comment) &gt; 0 or count(siteuser) &gt; 0">
						<p class="item-comment is-list">
							<xsl:if test="count(tag) &gt; 0">
								<span><i class="fa fa-tags"></i><xsl:apply-templates select="tag"/></span>
							</xsl:if>

							<!-- Комментарий добавил авторизированный пользователь -->
							<xsl:if test="count(siteuser) &gt; 0">
							<span><i class="fa fa-user"></i><a href="/users/info/{siteuser/login}/"><xsl:value-of select="siteuser/login"/></a></span>
							</xsl:if>

							<xsl:if test="count(comment) &gt; 0">
								<span><i class="fa fa-comments"></i><a href="{url}#comments"><xsl:value-of select="comments_count"/><xsl:text> </xsl:text><xsl:call-template name="declension"> <xsl:with-param name="number" select="comments_count"/></xsl:call-template></a></span>
							</xsl:if>
						</p>
					</xsl:if>
				</div>
			</div>
		</div>
	</xsl:template>

	<!-- /// Метки для информационного элемента /// -->
	<xsl:template match="tag">
		<a href="{/informationsystem/url}tag/{urlencode}/" class="tag">
			<xsl:value-of select="name"/>
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

		<xsl:if test="$items_count &gt; $limit and ($page + $post_count_page + 1) &gt; $i">
			<!-- Заносим в переменную $group идентификатор текущей группы -->
			<xsl:variable name="group" select="/informationsystem/group"/>

			<!-- Путь для тэга -->
			<xsl:variable name="tag_path">
				<xsl:choose>
					<!-- Если не нулевой уровень -->
					<xsl:when test="count(/informationsystem/tag) != 0">tag/<xsl:value-of select="/informationsystem/tag/urlencode"/>/</xsl:when>
					<!-- Иначе если нулевой уровень - просто ссылка на страницу со списком элементов -->
					<xsl:otherwise></xsl:otherwise>
				</xsl:choose>
			</xsl:variable>

			<!-- Определяем группу для формирования адреса ссылки -->
			<xsl:variable name="group_link">
				<xsl:choose>
					<!-- Если группа не корневая (!=0) -->
					<xsl:when test="$group != 0">
						<xsl:value-of select="/informationsystem//informationsystem_group[@id=$group]/url"/>
					</xsl:when>
					<!-- Иначе если нулевой уровень - просто ссылка на страницу со списком элементов -->
					  <xsl:otherwise><xsl:value-of select="/informationsystem/url"/></xsl:otherwise>
				</xsl:choose>
			</xsl:variable>

			<!-- Определяем адрес ссылки -->
			<xsl:variable name="number_link">
				<xsl:choose>
					<!-- Если не нулевой уровень -->
					<xsl:when test="$i != 0">page-<xsl:value-of select="$i + 1"/>/</xsl:when>
					<!-- Иначе если нулевой уровень - просто ссылка на страницу со списком элементов -->
					<xsl:otherwise></xsl:otherwise>
				</xsl:choose>
			</xsl:variable>

			<!-- Выводим ссылку на первую страницу -->
			<xsl:if test="$page - $pre_count_page &gt; 0 and $i = $start_page">
				<li>
					<a href="{$group_link}{$tag_path}" class="page_link" style="text-decoration: none;">←</a>
				</li>
			</xsl:if>

			<!-- Ставим ссылку на страницу-->
			<xsl:if test="$i != $page">
				<xsl:if test="($page - $pre_count_page) &lt;= $i and $i &lt; $n">
					<li>
						<!-- Выводим ссылки на видимые страницы -->
						<a href="{$group_link}{$number_link}{$tag_path}" class="page_link">
							<xsl:value-of select="$i + 1"/>
						</a>
					</li>
				</xsl:if>

				<!-- Выводим ссылку на последнюю страницу -->
				<xsl:if test="$i+1 &gt;= ($page + $post_count_page + 1) and $n &gt; ($page + 1 + $post_count_page)">
					<li>
						<!-- Выводим ссылку на последнюю страницу -->
						<a href="{$group_link}page-{$n}/{$tag_path}" class="page_link" style="text-decoration: none;">→</a>
					</li>
				</xsl:if>
			</xsl:if>

			<!-- Ссылка на предыдущую страницу для Ctrl + влево -->
			<xsl:if test="$page != 0 and $i = $page">
				<xsl:variable name="prev_number_link">
					<xsl:choose>
						<!-- Если не нулевой уровень -->
						<xsl:when test="$page &gt; 1">page-<xsl:value-of select="$i"/>/</xsl:when>
						<!-- Иначе если нулевой уровень - просто ссылка на страницу со списком элементов -->
						<xsl:otherwise></xsl:otherwise>
					</xsl:choose>
				</xsl:variable>

				<a href="{$group_link}{$prev_number_link}{$tag_path}" id="id_prev"></a>
			</xsl:if>

			<!-- Ссылка на следующую страницу для Ctrl + вправо -->
			<xsl:if test="($n - 1) > $page and $i = $page">
				<a href="{$group_link}page-{$page+2}/{$tag_path}" id="id_next"></a>
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

	<!-- Склонение после числительных -->
	<xsl:template name="declension">

		<xsl:param name="number" select="number"/>

		<!-- Именительный падеж -->
		<xsl:variable name="nominative">
			<xsl:text>комментарий</xsl:text>
		</xsl:variable>

		<!-- Родительный падеж, единственное число -->
		<xsl:variable name="genitive_singular">
			<xsl:text>комментария</xsl:text>
		</xsl:variable>


		<xsl:variable name="genitive_plural">
			<xsl:text>комментариев</xsl:text>
		</xsl:variable>

		<xsl:variable name="last_digit">
			<xsl:value-of select="$number mod 10"/>
		</xsl:variable>

		<xsl:variable name="last_two_digits">
			<xsl:value-of select="$number mod 100"/>
		</xsl:variable>

		<xsl:choose>
			<xsl:when test="$last_digit = 1 and $last_two_digits != 11">
				<xsl:value-of select="$nominative"/>
			</xsl:when>
			<xsl:when test="$last_digit = 2 and $last_two_digits != 12
				or $last_digit = 3 and $last_two_digits != 13
				or $last_digit = 4 and $last_two_digits != 14">
				<xsl:value-of select="$genitive_singular"/>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="$genitive_plural"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
</xsl:stylesheet>