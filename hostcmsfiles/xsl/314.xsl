<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>
	
	<!-- СписокУслугНаГлавной -->
	
	<xsl:template match="/">
		<div class="info-block">
			<div class="row">
				<xsl:apply-templates select="/informationsystem/informationsystem_item" />
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="informationsystem_item">
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<div class="pad">
				<div class="service">
					<div class="badge bg-color{position()}">
						<xsl:choose>
							<xsl:when test="property_value[tag_name='badge']/value != ''">
								<i class="fa {property_value[tag_name='badge']/value}"></i>
							</xsl:when>
							<xsl:otherwise>
								<i class="fa fa-star-o"></i>
							</xsl:otherwise>
						</xsl:choose>
					</div>
					
					<div class="caption">
						<p class="title">
							<a href="{url}" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="informationsystem_item"><xsl:value-of disable-output-escaping="yes" select="name"/></a>
						</p>
						<div hostcms:id="{@id}" hostcms:field="description" hostcms:entity="informationsystem_item"><xsl:value-of disable-output-escaping="yes" select="description"/></div>
					</div>
				</div>
			</div>
		</div>
	</xsl:template>
</xsl:stylesheet>