<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>
	
	<!-- МагазинСписокПроизводителей -->
	
	<xsl:template match="/">
		<xsl:apply-templates select="shop"/>
	</xsl:template>
	
	<xsl:template match="shop">
		<div class="block block-side-nav first">
			<div class="block-title">
				<strong>
				<i class="fa fa-barcode"></i><span>Производитель</span>
				</strong>
			</div>
			<div class="block-content">
				<ul class="sf-menu-phone2">
					<xsl:apply-templates select="shop_producer"/>
				</ul>
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="shop_producer">
		<li class="level0 level-top">
			<a href="{/shop/url}producer-{@id}/" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="shop_producer"><xsl:value-of disable-output-escaping="no" select="name"/></a>
		</li>
	</xsl:template>
</xsl:stylesheet>