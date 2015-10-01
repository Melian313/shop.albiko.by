<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>
	
	<!-- МагазинПоследнийЗаказ -->
	
	<xsl:decimal-format name="my" decimal-separator="," grouping-separator=" "/>
	
	<xsl:template match="/shop">
		<div class="block block-side-nav first">
			<div class="block-title">
				<strong>
				<i class="fa fa-star-o"></i><span>Последний заказ</span>
				</strong>
			</div>
			
			<!-- <div class="lastOrder">
				<p class="h2">Заказ от <xsl:value-of disable-output-escaping="yes" select="substring(shop_order/payment_datetime, 1, 10)"/> г. </p>
			</div>-->
			<div class="block-content">
				<xsl:apply-templates select="shop_item[position() &lt; 4]"/>
			</div>
		</div>
	</xsl:template>
	
	<xsl:template match="shop_item">
		
		<div class="spec_item">
			<div class="product-image">
				<a href="{url}" title="{name}">
					<xsl:choose>
						<xsl:when test="image_small != ''">
							<img src="{dir}{image_small}" alt="{name}" />
						</xsl:when>
						<xsl:otherwise>
							<img src="/images/no-image.png" />
						</xsl:otherwise>
					</xsl:choose>
				</a>
			</div>
			
			<div class="product-shop">
				<h3 class="product-name">
					<a href="{url}" title="{name}" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="shop_item">
						<xsl:value-of disable-output-escaping="yes" select="name"/>
					</a>
				</h3>
			</div>
			
			<div class="price-box">
				<p class="special-price">
					<span id="product-price-48-widget-catalogsale-72f28d8dcc63e0c34c78bf26a2a57df9" class="price">
					<xsl:value-of select="format-number(price, '### ##0,00', 'my')"/><xsl:text> </xsl:text><xsl:value-of disable-output-escaping="yes" select="currency"/><xsl:text> </xsl:text>
					</span>
				</p>
			</div>
		</div>
	</xsl:template>
</xsl:stylesheet>