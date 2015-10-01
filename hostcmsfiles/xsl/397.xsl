<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>

	<!-- МагазинКаталогТоваровНаГлавнойСпецПред -->

	<xsl:decimal-format name="my" decimal-separator="," grouping-separator=" "/>

	<xsl:template match="/">
		<xsl:apply-templates select="/shop"/>
	</xsl:template>

	<xsl:template match="/shop">
		<!-- Есть товары -->
		<xsl:if test="shop_item">
			<div class="page-title category-title">
			<h1><i class="fa fa-gift"></i>Горячие предложения</h1>
			</div>

			<div class="row products-grid">
				<!-- Выводим товары магазина -->
				<xsl:apply-templates select="shop_item" />
			</div>
		</xsl:if>
	</xsl:template>

	<!-- Шаблон для товара -->
	<xsl:template match="shop_item">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 item">
			<div class="grid_wrap">
				<div class="ribbon-wrapper">
					<div class="ribbon bg-color2">HOT</div>
				</div>

				<div class="product-image">
					<a href="{url}" title="{name}">
						<img src="{dir}{image_small}" alt="{name}" />
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
						<a href="{url}" title="{name}" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="shop_item">
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
	</xsl:template>
</xsl:stylesheet>