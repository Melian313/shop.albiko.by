<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>
	
	<!-- МагазинТовар -->
	
	<xsl:decimal-format name="my" decimal-separator="," grouping-separator=" "/>
	
	<xsl:template match="/shop">
		<div class="row">
			<xsl:apply-templates select="shop_item"/>
		</div>
		
		<!-- Просмотренные товары -->
		<xsl:if test="viewed/shop_item">
			<div class="page-title category-title">
			<h1><i class="fa fa-eye"></i>Просмотренные товары</h1>
			</div>
			<ul class="products-grid">
				<!-- Выводим товары магазина -->
				<div class="row">
					<xsl:apply-templates select="viewed/shop_item[position() &lt; 4]" mode="view"/>
				</div>
			</ul>
		</xsl:if>
	</xsl:template>
	
	<xsl:template match="shop_item">
		<!-- Получаем ID родительской группы и записываем в переменную $group -->
		<xsl:variable name="group" select="/shop/group"/>
		
		<!-- Путь к группе -->
		<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">
			
			<xsl:if test="$group = 0">
				<span typeof="v:Breadcrumb">
					<a title="{/shop/name}" href="{/shop/url}" hostcms:id="{/shop/@id}" hostcms:field="name" hostcms:entity="shop" property="v:title" rel="v:url">
						<xsl:value-of disable-output-escaping="yes" select="/shop/name"/>
					</a>
				</span>
			</xsl:if>
			
			<xsl:apply-templates select="/shop//shop_group[@id=$group]" mode="breadCrumbs"/>
			
			<!-- Если модификация, выводим в пути родительский товар -->
			<xsl:if test="shop_item/node()">
				<i class="fa fa-angle-right"></i>
				
				<span typeof="v:Breadcrumb">
					<a title="{shop_item/name}" href="{shop_item/url}" property="v:title" rel="v:url">
						<xsl:value-of disable-output-escaping="yes" select="shop_item/name"/>
					</a>
				</span>
			</xsl:if>
			
			<i class="fa fa-angle-right"></i>
			
			<span typeof="v:Breadcrumb">
				<a href="{url}" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="shop_item" property="v:title" rel="v:url"><xsl:value-of disable-output-escaping="yes" select="name"/></a>
			</span>
		</div>
		
		<!-- <div class="page-title category-title"> -->
			<h1 class="item_title">
				<xsl:value-of disable-output-escaping="yes" select="name"/>
			</h1>
			<!-- </div> -->
		
		<!-- Выводим сообщение -->
		<xsl:if test="/shop/message/node()">
			<xsl:value-of disable-output-escaping="yes" select="/shop/message"/>
		</xsl:if>
		
		<div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">
			<!-- Изображение для товара, если есть -->
			<xsl:if test="image_small != ''">
				<img id="zoom" src="{dir}{image_small}" data-zoom-image="{dir}{image_large}"/>
				
				<div id="gallery" style="margin-top:30px;">
					<a href="#" data-image="{dir}{image_small}" data-zoom-image="{dir}{image_large}">
						<img id="zoom" src="{dir}{image_small}" height="100"/>
					</a>
					
					<xsl:for-each select="property_value[tag_name='img'][file !='']">
						<a href="#" data-image="{../dir}{file_small}" data-zoom-image="{../dir}{file}">
							<img id="zoom" src="{../dir}{file_small}" height="100"/>
						</a>
					</xsl:for-each>
				</div>
			</xsl:if>
		</div>
		
		<div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">
			<!-- Описание товара -->
			<xsl:if test="description != ''">
				<div class="item-description" hostcms:id="{@id}" hostcms:field="text" hostcms:entity="shop_item" hostcms:type="wysiwyg"><xsl:value-of disable-output-escaping="yes" select="description"/></div>
			</xsl:if>
			
			<hr/>
			
			<!-- Цена товара -->
			<xsl:if test="price != 0">
				<div class="item-price">
				<xsl:value-of select="format-number(price, '### ##0,00', 'my')"/><xsl:text> </xsl:text><xsl:value-of select="currency"/><xsl:text> </xsl:text>
					
					<!-- Если цена со скидкой - выводим ее -->
					<xsl:if test="discount != 0">
						<span class="item-old-price">
							<xsl:value-of select="format-number(price + discount, '### ##0', 'my')"/><xsl:text> </xsl:text><xsl:value-of select="currency" />
					</span><xsl:text> </xsl:text>
					</xsl:if>
				</div>
			</xsl:if>
			
			<!-- Cкидки -->
			<xsl:if test="count(shop_discount)">
				<xsl:apply-templates select="shop_discount"/>
			</xsl:if>
			
			<div class="actions">
				<button class="button btn-cart" onclick="return $.bootstrapAddIntoCart('{/shop/url}cart/', {@id}, 1)" title="Add to Cart" type="button">
					<i class="fa fa-shopping-cart bg-color5">
						<b></b>
					</i>
					<span class="bg-color3">
						<span>В корзину</span>
					</span>
				</button>
			</div>
			
			<hr/>
			
			<!-- Бонусы для товара -->
			<xsl:if test="count(shop_bonuses/shop_bonus)">
				<div class="shop_property product-bonuses">
					+<xsl:value-of select="shop_bonuses/total" /> бонусов
				</div>
			</xsl:if>
			
			<xsl:if test="marking != ''">
			<div class="shop_property">Артикул: <span hostcms:id="{@id}" hostcms:field="marking" hostcms:entity="shop_item"><xsl:value-of disable-output-escaping="yes" select="marking"/></span></div>
			</xsl:if>
			
			<xsl:if test="shop_producer/node()">
			<div class="shop_property">Производитель: <span><xsl:value-of disable-output-escaping="yes" select="shop_producer/name"/></span></div>
			</xsl:if>
			
			<!-- Если указан вес товара -->
			<xsl:if test="weight != 0">
	<div class="shop_property">Вес товара: <span hostcms:id="{@id}" hostcms:field="weight" hostcms:entity="shop_item"><xsl:value-of select="weight"/></span><xsl:text> </xsl:text><span><xsl:value-of select="/shop/shop_measure/name"/></span></div>
			</xsl:if>
			
			<!-- Размеры товара -->
			<xsl:if test="length != 0 or width != 0 or height != 0">
				<div class="shop_property">Размеры: <span><xsl:value-of select="length" /><xsl:text> </xsl:text><xsl:value-of select="/shop/size_measure/name" />
						<xsl:text> × </xsl:text>
						<xsl:value-of select="width" /><xsl:text> </xsl:text><xsl:value-of select="/shop/size_measure/name" />
						<xsl:text> × </xsl:text>
				<xsl:value-of select="height" /><xsl:text> </xsl:text><xsl:value-of select="/shop/size_measure/name" /></span></div>
			</xsl:if>
			
			<!-- Количество на складе для не электронного товара -->
			<xsl:if test="rest &gt; 0 and type != 1">
<div class="shop_property">В наличии: <span><xsl:value-of disable-output-escaping="yes" select="rest - reserved"/><xsl:text> </xsl:text><xsl:value-of disable-output-escaping="yes" select="shop_measure/name"/></span><xsl:if test="reserved &gt; 0"> (зарезервировано: <span><xsl:value-of disable-output-escaping="yes" select="reserved"/><xsl:text> </xsl:text><xsl:value-of disable-output-escaping="yes" select="shop_measure/name"/></span>)</xsl:if></div>
			</xsl:if>
			
			<!-- Если электронный товар, выведим доступное количество -->
			<xsl:if test="type = 1">
				<div class="shop_property">
					<xsl:choose>
						<xsl:when test="digitals = 0">
							Электронный товар закончился.
						</xsl:when>
						<xsl:when test="digitals = -1">
							Электронный товар доступен для заказа.
						</xsl:when>
						<xsl:otherwise>
					На складе осталось: <span><xsl:value-of select="digitals" /><xsl:text> </xsl:text><xsl:value-of select="shop_measure/name" /></span>
						</xsl:otherwise>
					</xsl:choose>
				</div>
			</xsl:if>
			
			<hr/>
			
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a class="item-wishlist" onclick="return $.addFavorite('{/shop/url}', {@id}, this)"><i class="fa fa-heart-o"></i>Избранное</a>
				<a class="item-compare" onclick="return $.addCompare('{/shop/url}', {@id}, this)"><i class="fa fa-bar-chart"></i>Сравнить</a>
				</div>
			</div>
			
			<hr/>
			
			<div class="shop_property item-float-left">
				<i class="fa fa-eye"></i>
				<xsl:value-of disable-output-escaping="yes" select="showed"/>
			</div>
			
			<xsl:if test="siteuser_id != 0">
				<div class="shop_property item-float-left">
					<i class="fa fa-user"></i>
					<a href="/users/info/{siteuser/login}/"><xsl:value-of disable-output-escaping="yes" select="siteuser/login"/></a>
				</div>
			</xsl:if>
			
			<div class="shop_property item-float-left">
				<i class="fa fa-calendar"></i>
				<xsl:value-of disable-output-escaping="yes" select="date"/><xsl:text> г.</xsl:text>
			</div>
			
			<div class="shop_property item-float-left">
				<xsl:if test="rate/node()">
					<span id="shop_item_id_{@id}" class="thumbs">
						<xsl:choose>
							<xsl:when test="/shop/siteuser_id > 0">
								<xsl:choose>
									<xsl:when test="vote/value = 1">
										<xsl:attribute name="class">thumbs up</xsl:attribute>
									</xsl:when>
									<xsl:when test="vote/value = -1">
										<xsl:attribute name="class">thumbs down</xsl:attribute>
									</xsl:when>
								</xsl:choose>
								<span id="shop_item_likes_{@id}"><xsl:value-of select="rate/@likes" /></span>
								<span class="inner_thumbs">
								<a onclick="return $.sendVote({@id}, 1, 'shop_item')" href="{/shop/url}?id={@id}&amp;vote=1&amp;entity_type=shop_item" alt="Нравится"><i class="fa fa-thumbs-o-up"></i></a>
									<span class="rate" id="shop_item_rate_{@id}"><xsl:value-of select="rate" /></span>
								<a onclick="return $.sendVote({@id}, 0, 'shop_item')" href="{/shop/url}?id={@id}&amp;vote=0&amp;entity_type=shop_item" alt="Не нравится"><i class="fa fa-thumbs-o-down"></i></a>
								</span>
								<span id="shop_item_dislikes_{@id}"><xsl:value-of select="rate/@dislikes" /></span>
							</xsl:when>
							<xsl:otherwise>
								<xsl:attribute name="class">thumbs inactive</xsl:attribute>
								<span id="shop_item_likes_{@id}"><xsl:value-of select="rate/@likes" /></span>
								<span class="inner_thumbs">
								<a alt="Нравится"><i class="fa fa-thumbs-o-up"></i></a>
									<span class="rate" id="shop_item_rate_{@id}"><xsl:value-of select="rate" /></span>
								<a alt="Не нравится"><i class="fa fa-thumbs-o-down"></i></a>
								</span>
								<span id="shop_item_dislikes_{@id}"><xsl:value-of select="rate/@dislikes" /></span>
							</xsl:otherwise>
						</xsl:choose>
					</span>
				</xsl:if>
			</div>
			
			<div class="rating">
				<!-- Средняя оценка элемента -->
				<xsl:if test="comments_average_grade/node() and comments_average_grade != 0">
					<span><xsl:call-template name="show_average_grade">
							<xsl:with-param name="grade" select="comments_average_grade"/>
						<xsl:with-param name="const_grade" select="5"/></xsl:call-template></span>
				</xsl:if>
				<xsl:if test="comments_average_grade/node() and comments_average_grade = 0">
					<div style="clear:both"></div>
				</xsl:if>
			</div>
			
			<hr/>
		</div>
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<!-- Текст товара -->
				<xsl:if test="text != ''">
					<div class="item-text" hostcms:id="{@id}" hostcms:field="text" hostcms:entity="shop_item" hostcms:type="wysiwyg"><xsl:value-of disable-output-escaping="yes" select="text"/></div>
				</xsl:if>
			</div>
		</div>
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<!-- Тэги для информационного элемента -->
				<xsl:if test="count(tag) &gt; 0">
					<div class="item-tags">
					<i class="fa fa-tags"></i><span><xsl:apply-templates select="tag"/></span>
					</div>
				</xsl:if>
			</div>
		</div>
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<xsl:if test="count(property_value[not(file/node())][value!=''])">
					<div class="page-title category-title news_title">
					<h1><i class="fa fa-bars"></i>Атрибуты товара</h1>
					</div>
					<xsl:apply-templates select="property_value[not(file/node())]"/>
				</xsl:if>
			</div>
		</div>
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<!-- Если указано отображать комментарии -->
				<xsl:if test="/shop/show_comments/node() and /shop/show_comments = 1">
					<xsl:if test="count(comment) &gt; 0">
						<div class="page-title category-title news_title">
						<h1><i class="fa fa-comments-o"></i>Комментарии</h1>
						</div>
						<xsl:apply-templates select="comment"/>
					</xsl:if>
				</xsl:if>
				
				<!-- Если разрешено отображать формы добавления комментария
				1 - Только авторизированным
				2 - Всем
				-->
				<xsl:if test="/shop/show_add_comments/node() and ((/shop/show_add_comments = 1 and /shop/siteuser_id &gt; 0)  or /shop/show_add_comments = 2)">
					<div class="actions item-margin-left text-align-center">
						<button class="button btn-cart" type="button" title="Add Comment" onclick="$('.comment_reply').hide('slow');$('#AddComment').toggle('slow')">
							<i class="fa fa-comments bg-color5"></i>
							<span class="bg-color2">
								<span>Добавить комментарий</span>
							</span>
						</button>
					</div>
					
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div id="AddComment" class="comment_reply" style="display:none;">
								<xsl:call-template name="AddCommentForm"></xsl:call-template>
							</div>
						</div>
					</div>
				</xsl:if>
			</div>
		</div>
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<!-- Модификации -->
				<xsl:if test="count(modifications/shop_item) &gt; 0">
					<div class="page-title category-title news_title">
					<h1><i class="fa fa-bars"></i>Модификации <xsl:value-of disable-output-escaping="yes" select="name"/></h1>
					</div>
					<div class="row products-grid">
						<xsl:apply-templates select="modifications/shop_item"/>
					</div>
				</xsl:if>
				
				<xsl:if test="count(associated/shop_item) &gt; 0">
					<div class="page-title category-title news_title">
					<h1><i class="fa fa-bars"></i>Сопутствующие товары <xsl:value-of disable-output-escaping="yes" select="name"/></h1>
					</div>
					<div class="row products-grid">
						<xsl:apply-templates select="associated/shop_item"/>
					</div>
				</xsl:if>
			</div>
		</div>
	</xsl:template>
	
	<!-- Шаблон для товара просмотренные-->
	<xsl:template match="shop_item" mode="view">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 item">
			<div class="grid_wrap">
				<xsl:if test="discount != 0">
					<div class="ribbon-wrapper">
						<div class="ribbon bg-color2">HOT</div>
					</div>
				</xsl:if>
				
				<div class="product-image">
					<a href="{url}" title="{name}">
						<xsl:choose>
							<xsl:when test="image_small != ''">
								<img src="{dir}{image_small}" alt="{name}" />
							</xsl:when>
							<xsl:otherwise>
								<i class="fa fa-camera"></i>
							</xsl:otherwise>
						</xsl:choose>
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
				</div>
				
				<div class="product-shop">
					<h3 class="product-name">
						<a href="{url}" title="{name}">
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
							<a class="link-wishlist" onclick="return $.addFavorite('{/shop/url}', {@id}, this)"><i class="fa fa-heart-o"></i>Избранное</a>
							</li>
							<li>
								<span class="separator">|</span>
							<a class="link-compare" onclick="return $.addCompare('{/shop/url}', {@id}, this)"><i class="fa fa-bar-chart"></i>Сравнить</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</xsl:template>
	
	<!-- Вывод строки со значением свойства -->
	<xsl:template match="property_value">
		<xsl:if test="value/node() and value != '' or file/node() and file != ''">
			<div class="shop_property item-margin-left">
				<xsl:variable name="property_id" select="property_id" />
				<xsl:variable name="property" select="/shop/shop_item_properties//property[@id=$property_id]" />
				
				<xsl:value-of disable-output-escaping="yes" select="$property/name"/><xsl:text>: </xsl:text>
				<span><xsl:choose>
						<xsl:when test="$property/type = 2">
							<a href="{../dir}{file}" target="_blank"><xsl:value-of disable-output-escaping="yes" select="file_name"/></a>
						</xsl:when>
						<xsl:when test="$property/type = 7">
							<input type="checkbox" disabled="disabled">
								<xsl:if test="value = 1">
									<xsl:attribute name="checked">checked</xsl:attribute>
								</xsl:if>
							</input>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of disable-output-escaping="yes" select="value"/>
							<!-- Единица измерения свойства -->
							<xsl:if test="$property/shop_measure/node()">
								<xsl:text> </xsl:text><xsl:value-of select="$property/shop_measure/name"/>
							</xsl:if>
						</xsl:otherwise>
				</xsl:choose></span>
			</div>
		</xsl:if>
	</xsl:template>
	
	<!-- Метки -->
	<xsl:template match="tag">
		<a href="{/shop/url}tag/{urlencode}/">
			<xsl:value-of select="name"/>
		</a>
	<xsl:if test="position() != last()"><xsl:text>, </xsl:text></xsl:if>
	</xsl:template>
	
	<!-- Шаблон для модификаций -->
	<xsl:template match="modifications/shop_item">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 item">
			<div class="grid_wrap">
				<xsl:if test="discount != 0">
					<div class="ribbon-wrapper">
						<div class="ribbon bg-color2">HOT</div>
					</div>
				</xsl:if>
				
				<div class="product-image">
					<a href="{url}" title="{name}">
						<xsl:choose>
							<xsl:when test="image_small != ''">
								<img src="{dir}{image_small}" alt="{name}" />
							</xsl:when>
							<xsl:otherwise>
								<i class="fa fa-camera"></i>
							</xsl:otherwise>
						</xsl:choose>
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
				</div>
				
				<div class="product-shop">
					<h3 class="product-name">
						<a href="{url}" title="{name}">
							<xsl:value-of disable-output-escaping="yes" select="name"/>
						</a>
					</h3>
					
					<div class="actions">
						<xsl:variable name="shop_item_id" select="@id" />
						<ul class="add-to-links">
							<li>
							<a class="link-wishlist" onclick="return $.addFavorite('{/shop/url}', {@id}, this)"><i class="fa fa-heart-o"></i>Избранное</a>
							</li>
							<li>
								<span class="separator">|</span>
							<a class="link-compare" onclick="return $.addCompare('{/shop/url}', {@id}, this)"><i class="fa fa-bar-chart"></i>Сравнить</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</xsl:template>
	
	<!-- Шаблон для сопутствующих товаров -->
	<xsl:template match="associated/shop_item">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 item">
			<div class="grid_wrap">
				<xsl:if test="discount != 0">
					<div class="ribbon-wrapper">
						<div class="ribbon bg-color2">HOT</div>
					</div>
				</xsl:if>
				
				<div class="product-image">
					<a href="{url}" title="{name}">
						<xsl:choose>
							<xsl:when test="image_small != ''">
								<img src="{dir}{image_small}" alt="{name}" />
							</xsl:when>
							<xsl:otherwise>
								<i class="fa fa-camera"></i>
							</xsl:otherwise>
						</xsl:choose>
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
				</div>
				
				<div class="product-shop">
					<h3 class="product-name">
						<a href="{url}" title="{name}">
							<xsl:value-of disable-output-escaping="yes" select="name"/>
						</a>
					</h3>
					
					<div class="actions">
						<xsl:variable name="shop_item_id" select="@id" />
						<ul class="add-to-links">
							<li>
							<a class="link-wishlist" onclick="return $.addFavorite('{/shop/url}', {@id}, this)"><i class="fa fa-heart-o"></i>Избранное</a>
							</li>
							<li>
								<span class="separator">|</span>
							<a class="link-compare" onclick="return $.addCompare('{/shop/url}', {@id}, this)"><i class="fa fa-bar-chart"></i>Сравнить</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</xsl:template>
	
	<!-- Вывод рейтинга -->
	<xsl:template name="show_average_grade">
		<xsl:param name="grade" select="0"/>
		<xsl:param name="const_grade" select="0"/>
		
		<!-- Чтобы избежать зацикливания -->
		<xsl:variable name="current_grade" select="$grade * 1"/>
		
		<xsl:choose>
			<!-- Если число целое -->
			<xsl:when test="floor($current_grade) = $current_grade and not($const_grade &gt; ceiling($current_grade))">
				
				<xsl:if test="$current_grade - 1 &gt; 0">
					<xsl:call-template name="show_average_grade">
						<xsl:with-param name="grade" select="$current_grade - 1"/>
						<xsl:with-param name="const_grade" select="$const_grade - 1"/>
					</xsl:call-template>
				</xsl:if>
				
				<xsl:if test="$current_grade != 0">
					<img src="/images/star-full.png"/>
				</xsl:if>
			</xsl:when>
			<xsl:when test="$current_grade != 0 and not($const_grade &gt; ceiling($current_grade))">
				
				<xsl:if test="$current_grade - 0.5 &gt; 0">
					<xsl:call-template name="show_average_grade">
						
						<xsl:with-param name="grade" select="$current_grade - 0.5"/>
						<xsl:with-param name="const_grade" select="$const_grade - 1"/>
					</xsl:call-template>
				</xsl:if>
				
				<img src="/images/star-half.png"/>
			</xsl:when>
			
			<!-- Выводим серые звездочки, пока текущая позиция не дойдет то значения, увеличенного до целого -->
			<xsl:otherwise>
				<xsl:call-template name="show_average_grade">
					<xsl:with-param name="grade" select="$current_grade"/>
					<xsl:with-param name="const_grade" select="$const_grade - 1"/>
				</xsl:call-template>
				<img src="/images/star-empty.png"/>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>
	
	<!-- Шаблон для вывода звездочек (оценки) -->
	<xsl:template name="for">
		<xsl:param name="i" select="0"/>
		<xsl:param name="n"/>
		
		<input type="radio" name="shop_grade" value="{$i}" id="id_shop_grade_{$i}">
			<xsl:if test="/shop/shop_grade = $i">
				<xsl:attribute name="checked"></xsl:attribute>
			</xsl:if>
	</input><xsl:text> </xsl:text>
		<label for="id_shop_grade_{$i}">
			<xsl:call-template name="show_average_grade">
				<xsl:with-param name="grade" select="$i"/>
				<xsl:with-param name="const_grade" select="5"/>
			</xsl:call-template>
		</label>
		<br/>
		<xsl:if test="$n &gt; $i and $n &gt; 1">
			<xsl:call-template name="for">
				<xsl:with-param name="i" select="$i + 1"/>
				<xsl:with-param name="n" select="$n"/>
			</xsl:call-template>
		</xsl:if>
	</xsl:template>
	
	<!-- Отображение комментариев -->
	<xsl:template match="comment">
		<!-- Отображаем комментарий, если задан текст или тема комментария -->
		<xsl:if test="text != '' or subject != ''">
			<a name="comment{@id}"></a>
			<div class="comment" id="comment{@id}">
				<xsl:if test="subject != ''">
					<div class="subject" hostcms:id="{@id}" hostcms:field="subject" hostcms:entity="comment"><xsl:value-of select="subject"/></div>
				</xsl:if>
				
				<div hostcms:id="{@id}" hostcms:field="text" hostcms:entity="comment" hostcms:type="wysiwyg"><xsl:value-of select="text" disable-output-escaping="yes"/></div>
				
				<p class="item-comment">
					<!-- Оценка комментария -->
					<xsl:if test="grade != 0">
						<span><xsl:call-template name="show_average_grade">
								<xsl:with-param name="grade" select="grade"/>
								<xsl:with-param name="const_grade" select="5"/>
						</xsl:call-template></span>
					</xsl:if>
					
					<xsl:choose>
						<!-- Комментарий добавил авторизированный пользователь -->
						<xsl:when test="count(siteuser) &gt; 0">
					<span><i class="fa fa-user"></i><a href="/users/info/{siteuser/login}/"><xsl:value-of select="siteuser/login"/></a></span>
						</xsl:when>
						<!-- Комментарй добавил неавторизированный пользователь -->
						<xsl:otherwise>
						<span><i class="fa fa-user"></i><xsl:value-of select="author" /></span>
						</xsl:otherwise>
					</xsl:choose>
					
					<xsl:if test="rate/node()">
						<span id="comment_id_{@id}" class="thumbs">
							<xsl:choose>
								<xsl:when test="/shop/siteuser_id > 0">
									<xsl:choose>
										<xsl:when test="vote/value = 1">
											<xsl:attribute name="class">thumbs up</xsl:attribute>
										</xsl:when>
										<xsl:when test="vote/value = -1">
											<xsl:attribute name="class">thumbs down</xsl:attribute>
										</xsl:when>
									</xsl:choose>
									
									<span id="comment_likes_{@id}"><xsl:value-of select="rate/@likes" /></span>
									<span class="inner_thumbs">
									<a onclick="return $.sendVote({@id}, 1, 'comment')" href="{/shop/url}?id={@id}&amp;vote=1&amp;entity_type=comment" alt="Нравится"><i class="fa fa-thumbs-o-up"></i></a>
										<span class="rate" id="comment_rate_{@id}"><xsl:value-of select="rate" /></span>
									<a onclick="return $.sendVote({@id}, 0, 'comment')" href="{/shop/url}?id={@id}&amp;vote=0&amp;entity_type=comment" alt="Не нравится"><i class="fa fa-thumbs-o-down"></i></a>
									</span>
									<span id="comment_dislikes_{@id}"><xsl:value-of select="rate/@dislikes" /></span>
								</xsl:when>
								<xsl:otherwise>
									<xsl:attribute name="class">thumbs inactive</xsl:attribute>
									<span id="comment_likes_{@id}"><xsl:value-of select="rate/@likes" /></span>
									<span class="inner_thumbs">
									<a alt="Нравится"><i class="fa fa-thumbs-o-up"></i></a>
										<span class="rate" id="comment_rate_{@id}"><xsl:value-of select="rate" /></span>
									<a alt="Не нравится"><i class="fa fa-thumbs-o-down"></i></a>
									</span>
									<span id="comment_dislikes_{@id}"><xsl:value-of select="rate/@dislikes" /></span>
								</xsl:otherwise>
							</xsl:choose>
						</span>
					</xsl:if>
					
				<span><i class="fa fa-calendar"></i><xsl:value-of select="datetime"/></span>
					
					<xsl:if test="/shop/show_add_comments/node()
						and ((/shop/show_add_comments = 1 and /shop/siteuser_id > 0)
						or /shop/show_add_comments = 2)">
					<span class="red" onclick="$('.comment_reply').hide('slow');$('#cr_{@id}').toggle('slow')">ответить</span></xsl:if>
					
				<span class="red"><a href="{/shop/shop_item/url}#comment{@id}" title="Ссылка на комментарий">#</a></span>
				</p>
			</div>
			
			<!-- Отображаем только авторизированным пользователям -->
			<xsl:if test="/shop/show_add_comments/node() and ((/shop/show_add_comments = 1 and /shop/siteuser_id > 0) or /shop/show_add_comments = 2)">
				<div class="comment_reply" id="cr_{@id}">
					<xsl:call-template name="AddCommentForm">
						<xsl:with-param name="id" select="@id"/>
					</xsl:call-template>
				</div>
			</xsl:if>
			
			<!-- Выбираем дочерние комментарии -->
			<xsl:if test="count(comment)">
				<div class="comment_sub">
					<xsl:apply-templates select="comment"/>
				</div>
			</xsl:if>
		</xsl:if>
	</xsl:template>
	
	<!-- Шаблон вывода добавления комментария -->
	<xsl:template name="AddCommentForm">
		<xsl:param name="id" select="0"/>
		
		<!-- Заполняем форму -->
		<xsl:variable name="subject">
			<xsl:if test="/shop/comment/parent_id/node() and /shop/comment/parent_id/node() and /shop/comment/parent_id= $id">
				<xsl:value-of select="/shop/comment/subject"/>
			</xsl:if>
		</xsl:variable>
		<xsl:variable name="email">
			<xsl:if test="/shop/comment/email/node() and /shop/comment/parent_id/node() and /shop/comment/parent_id= $id">
				<xsl:value-of select="/shop/comment/email"/>
			</xsl:if>
		</xsl:variable>
		<xsl:variable name="phone">
			<xsl:if test="/shop/comment/phone/node() and /shop/comment/parent_id/node() and /shop/comment/parent_id= $id">
				<xsl:value-of select="/shop/comment/phone"/>
			</xsl:if>
		</xsl:variable>
		<xsl:variable name="text">
			<xsl:if test="/shop/comment/text/node() and /shop/comment/parent_id/node() and /shop/comment/parent_id= $id">
				<xsl:value-of select="/shop/comment/text"/>
			</xsl:if>
		</xsl:variable>
		<xsl:variable name="name">
			<xsl:if test="/shop/comment/author/node() and /shop/comment/parent_id/node() and /shop/comment/parent_id= $id">
				<xsl:value-of select="/shop/comment/author"/>
			</xsl:if>
		</xsl:variable>
		
		<div class="comment no-background comment-width">
			<!--Отображение формы добавления комментария-->
			<form action="{/shop/shop_item/url}" name="comment_form_0{$id}" method="post">
				<!-- Авторизированным не показываем -->
				<xsl:if test="/shop/siteuser_id = 0">
					
					<div class="row">
						<div class="caption">Имя</div>
						<div class="field">
							<input type="text" size="70" name="author" class="form-control" value="{$name}"/>
						</div>
					</div>
					
					<div class="row">
						<div class="caption">E-mail</div>
						<div class="field">
							<input id="email{$id}" type="text" size="70" name="email" class="form-control" value="{$email}" />
							<div id="error_email{$id}"></div>
						</div>
					</div>
					
					<div class="row">
						<div class="caption">Телефон</div>
						<div class="field">
							<input type="text" size="70" name="phone" class="form-control" value="{$phone}"/>
						</div>
					</div>
				</xsl:if>
				
				<div class="row">
					<div class="caption">Тема</div>
					<div class="field">
						<input type="text" size="70" name="subject" class="form-control" value="{$subject}"/>
					</div>
				</div>
				
				<div class="row">
					<div class="caption">Комментарий</div>
					<div class="field">
						<textarea name="text" cols="68" rows="5" class="form-control mceEditor"><xsl:value-of select="$text"/></textarea>
					</div>
				</div>
				
				<div class="row">
					<div class="caption">Оценка</div>
					<div class="field stars">
						<select name="grade">
							<option value="1">Poor</option>
							<option value="2">Fair</option>
							<option value="3">Average</option>
							<option value="4">Good</option>
							<option value="5">Excellent</option>
						</select>
					</div>
				</div>
				
				<!-- Обработка CAPTCHA -->
				<xsl:if test="/shop/captcha_id != 0 and /shop/siteuser_id = 0">
					<div class="row">
						<div class="caption"></div>
						<div class="field">
							<img id="comment_{$id}" class="captcha" src="/captcha.php?id={//captcha_id}{$id}&amp;height=30&amp;width=100" title="Контрольное число" name="captcha"/>
							
							<div class="captcha">
								<img src="/images/refresh.png" /> <span onclick="$('#comment_{$id}').updateCaptcha('{//captcha_id}{$id}', 30); return false">Показать другое число</span>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="caption">
					Контрольное число<sup><font color="red">*</font></sup>
						</div>
						<div class="field">
							<input type="hidden" name="captcha_id" value="{//captcha_id}{$id}"/>
							<input type="text" name="captcha" size="15"/>
						</div>
					</div>
				</xsl:if>
				
				<xsl:if test="$id != 0">
					<input type="hidden" name="parent_id" value="{$id}"/>
				</xsl:if>
				
				<div class="row">
					<div class="caption"></div>
					<div class="actions item-margin-left">
						<button class="button btn-cart" type="submit" name="add_comment" value="add_comment">
							<i class="fa fa-share bg-color5"></i>
							<span class="bg-color3">
								<span>Опубликовать</span>
							</span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</xsl:template>
	
	<!-- Шаблон для скидки -->
	<xsl:template match="shop_discount">
		<div class="shop_discount">
		<xsl:value-of disable-output-escaping="yes" select="name"/><xsl:text> </xsl:text><span><xsl:value-of select="percent"/>%</span>
		</div>
	</xsl:template>
	
	<!-- Шаблон выводит рекурсивно ссылки на группы магазина -->
	<xsl:template match="shop_group" mode="breadCrumbs">
		<xsl:param name="parent_id" select="parent_id"/>
		
		<!-- Получаем ID родительской группы и записываем в переменную $group -->
		<xsl:param name="group" select="/shop/shop_group"/>
		
		<xsl:apply-templates select="//shop_group[@id=$parent_id]" mode="breadCrumbs"/>
		
		<xsl:if test="parent_id=0">
			<span typeof="v:Breadcrumb">
				<a title="{/shop/name}" href="{/shop/url}" hostcms:id="{/shop/@id}" hostcms:field="name" hostcms:entity="shop" class="root" property="v:title" rel="v:url">
					<xsl:value-of select="/shop/name"/>
				</a>
			</span>
		</xsl:if>
		
		<i class="fa fa-angle-right"></i>
		
		<span typeof="v:Breadcrumb">
			<a title="{name}" href="{url}" hostcms:id="{@id}" hostcms:field="name" hostcms:entity="shop_group" property="v:title" rel="v:url">
				<xsl:value-of disable-output-escaping="yes" select="name"/>
			</a>
		</span>
		
	</xsl:template>
	
	<!-- Склонение после числительных -->
	<xsl:template name="declension">
		
		<xsl:param name="number" select="number"/>
		
		<!-- Именительный падеж -->
	<xsl:variable name="nominative"><xsl:text>просмотр</xsl:text></xsl:variable>
		
		<!-- Родительный падеж, единственное число -->
	<xsl:variable name="genitive_singular"><xsl:text>просмотра</xsl:text></xsl:variable>
		
	<xsl:variable name="genitive_plural"><xsl:text>просмотров</xsl:text></xsl:variable>
		<xsl:variable name="last_digit"><xsl:value-of select="$number mod 10"/></xsl:variable>
		<xsl:variable name="last_two_digits"><xsl:value-of select="$number mod 100"/></xsl:variable>
		
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