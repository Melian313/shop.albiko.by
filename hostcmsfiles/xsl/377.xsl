<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>

	<!-- ОблакоТэговМагазин -->

	<xsl:template match="/">
		<xsl:apply-templates select="/shop"/>
	</xsl:template>

	<xsl:template match="/shop">

	</xsl:template>

	<!-- Облако из групп -->
	<xsl:template match="tag">
		<xsl:param name="min_size"/>
		<xsl:param name="coeff_size" select="$coeff_size"/>

		<!-- Нужный размер шрифта вычисляется по формуле $min_size + количество * $coeff_size -->
		<xsl:variable name="size" select="round($min_size + ((count - 1) * $coeff_size))"/>

		<xsl:variable name="color">
				<xsl:if test="$size &gt; 8"><xsl:text>#fb6e52</xsl:text></xsl:if>					
		</xsl:variable>
		
		<xsl:variable name="group_path"><xsl:if test="/shop/ПутьКГруппе/node()"><xsl:value-of select="/shop/ПутьКГруппе" /></xsl:if></xsl:variable>
		<a href="{/shop/url}{$group_path}tag/{urlencode}/" style="font-size: {$size}pt; color: {$color};" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="tag">
			<xsl:value-of select="name"/>
		</a>
		<xsl:text> </xsl:text>
	</xsl:template>
</xsl:stylesheet>