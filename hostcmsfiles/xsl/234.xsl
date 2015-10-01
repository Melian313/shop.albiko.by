<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>
	
	<!-- СписокНовостейНаГлавной -->
	
	<xsl:template match="/">
		<xsl:apply-templates select="/informationsystem"/>
	</xsl:template>
	
	<xsl:template match="/informationsystem">
		
		<!-- Отображение записи информационной системы -->
		<xsl:if test="informationsystem_item">
			<ul id="vertical-ticker">
				<div id="gallery">
					<xsl:apply-templates select="informationsystem_item"/>
				</div>
			</ul>
		</xsl:if>
	</xsl:template>
	
	<!-- Шаблон вывода информационного элемента -->
	<xsl:template match="informationsystem_item">
		<li style="margin:0 !important; padding:30px 0 0 0 !important;">
			<span style="display: block;" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="informationsystem_item">
				<xsl:value-of disable-output-escaping="yes" select="name"/>
			</span>
			<xsl:if test="image_small !=''">
				<a href="{dir}{image_large}" target="_blank" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="informationsystem_item">
					<img src="{dir}{image_small}" />
				</a>
			</xsl:if>
		</li>
	</xsl:template>
</xsl:stylesheet>