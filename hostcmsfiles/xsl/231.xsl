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
			<div id="barousel_prevnextnav" class="barousel">
				<xsl:apply-templates select="informationsystem_item"/>
				<div class="barousel_nav">
				</div>
			</div>
		</xsl:if>
	</xsl:template>
	
	<!-- Шаблон вывода информационного элемента -->
	<xsl:template match="informationsystem_item">
		<div class="barousel_image">
			<!-- Изображение для информационного элемента (если есть) -->
			<xsl:if test="image_small!=''">
				<div style="background: url({dir}{image_small}) no-repeat 50% 50%; height: 400px; width: 1000px;">
				</div>
				
			</xsl:if>
		</div>
		<div class="barousel_content">
			<div>
				
			</div>
		</div>
	</xsl:template>
</xsl:stylesheet>