<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>

	<xsl:template match="/">
		<xsl:apply-templates select="/siteuser"/>
	</xsl:template>

	<xsl:template match="/siteuser">
		<h1 class="item_title">Лицевой счет</h1>
		<div class="row balance">
			<xsl:apply-templates select="shop"/>
		</div>
	</xsl:template>

	<!-- Шаблон для магазина -->
	<xsl:template match="shop">
		<div class="col-lg-12">
			<strong><xsl:value-of select="transaction_amount"/><xsl:text> </xsl:text><xsl:value-of select="shop_currency/name" disable-output-escaping="yes"/></strong><xsl:text> —  магазин "</xsl:text><xsl:value-of disable-output-escaping="yes" select="name"/><xsl:text>". </xsl:text>
			<a href="pay/{@id}/">Пополнить счет</a>, <a href="shop-{@id}/">история</a>.
		</div>
	</xsl:template>
</xsl:stylesheet>