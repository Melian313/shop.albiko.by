<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>

	<!-- МагазинГруппыТоваровНаГлавной -->

	<xsl:template match="/">
		<xsl:apply-templates select="/shop"/>
	</xsl:template>

	<!-- Шаблон для магазина -->
	<xsl:template match="/shop">
		<div class="block block-side-nav first">
			<div class="block-title">
				<strong>
					<i class="fa fa-list"></i><span>Магазин</span>
				</strong>
			</div>
			<div class="block-content">
				<ul class="sf-menu-phone2">
					<xsl:apply-templates select="shop_group"/>
				</ul>
			</div>
		</div>
	</xsl:template>

	<!-- Шаблон для групп товара -->
	<xsl:template match="shop_group">
		<li class="level0 level-top">
			<xsl:if test="shop_group">
				<xsl:attribute name="class">level0 level-top parent</xsl:attribute>
			</xsl:if>

			<a href="{url}" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="shop_group">
				<xsl:if test="@id = /shop/current_group_id">
					<xsl:attribute name="class">current-group</xsl:attribute>
				</xsl:if>			
				<xsl:value-of disable-output-escaping="yes" select="name"/>
			</a>
			
			<xsl:if test="shop_group">
				<strong class="submenu-caret" onclick="$('#submenu_{@id}').toggle(); $(this).toggleClass('opened')"></strong>
			</xsl:if>	

			<!-- Если есть подгруппы -->
			<xsl:if test="shop_group">
				<ul id="submenu_{@id}" class="level0" style="display: none;">
					<xsl:apply-templates select="shop_group"/>
				</ul>
			</xsl:if>
		</li>
	</xsl:template>
</xsl:stylesheet>