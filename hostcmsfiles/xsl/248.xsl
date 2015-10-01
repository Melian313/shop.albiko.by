<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:hostcms="http://www.hostcms.ru/"
	exclude-result-prefixes="hostcms">
	<xsl:output xmlns="http://www.w3.org/TR/xhtml1/strict" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" encoding="utf-8" indent="yes" method="html" omit-xml-declaration="no" version="1.0" media-type="text/xml"/>

	<xsl:decimal-format name="my" decimal-separator="," grouping-separator=" "/>

	<xsl:template match="/siteuser">

		<xsl:choose>
			<!-- Авторизованный пользователь -->
			<xsl:when test="@id > 0">
			<div class="container">
				<xsl:variable name="username">
					<xsl:choose>
						<xsl:when test="name != ''">
							<xsl:value-of select="name"/>
						</xsl:when>
						<xsl:otherwise>
							<xsl:value-of select="login"/>
						</xsl:otherwise>
					</xsl:choose>
				</xsl:variable>

				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
						<h1>Здравствуйте, <span class="highlight"><xsl:value-of select="$username"/></span>, добро пожаловать в личный кабинет.</h1>
					</div>
				</div>

				<!-- Личные данные -->
				<div class="row user-data margin-bottom-30">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<div class="row user-data">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<h2 class="social-title">Личные данные</h2>
								<hr/>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-3 col-lg-6 text-align-center">
										<xsl:if test="property_value[tag_name='avatar']/file !=''">
											<img src="{dir}{property_value[tag_name='avatar']/file}" />
										</xsl:if>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-9 col-lg-6">
										<div class="row text-align-right">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-20">
												<xsl:if test="name != '' and surname != ''">
													<xsl:value-of select="name"/><xsl:text> </xsl:text><xsl:value-of select="surname"/><br/>
												</xsl:if>
												<xsl:if test="email != ''">
													<xsl:value-of select="email"/><br/>
												</xsl:if>
												<xsl:if test="phone != ''">
													<xsl:value-of select="phone"/><br/>
												</xsl:if>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<a class="hostcms-button" href="/users/registration/">Изменить данные</a>
												<a class="hostcms-button hostcms-button-red" href="/users/?action=exit">Выход</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Лицевой счет -->
					<xsl:if test="account/shop/node()">
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
							<div class="row user-data">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<h2 class="social-title">Лицевой счет</h2>
									<hr/>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<xsl:apply-templates select="account/shop"/>
								</div>
							</div>
						</div>
					</xsl:if>
				</div>
				<div class="row user-data margin-bottom-30">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<!-- История заказов -->
						<div class="row margin-bottom-30">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<h2 class="social-title">История заказов</h2>
								<hr/>

								<xsl:if test="shop_order/node()">
									<xsl:apply-templates select="shop_order"/>
								</xsl:if>

								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-align-right">
										<a href="/users/order/" class="hostcms-button">Показать все заказы</a>

										<xsl:if test="not(shop_order/node())">
											<a href="/shop/" class="hostcms-button hostcms-button-red">Купить</a>
										</xsl:if>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<div class="row user-data margin-bottom-30">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<h2 class="social-title">Службы поддержки</h2>
								<hr/>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<xsl:if test="helpdesk_tickets/helpdesk_ticket/node()">
									<div class="row margin-bottom-20">
										<xsl:apply-templates select="helpdesk_tickets/helpdesk_ticket"/>
									</div>
								</xsl:if>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<a class="hostcms-button hostcms-button-red" href="/users/helpdesk/">Направить запрос</a>
							</div>
						</div>
					</div>
				</div>

				<div class="row user-data margin-bottom-30">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<xsl:if test="maillists/maillist/node()">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<h2 class="social-title">Почтовые рассылки</h2>
									<hr/>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="row margin-bottom-30">
										<xsl:apply-templates select="maillists/maillist"/>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<a class="hostcms-button hostcms-button-red" href="/users/maillist/">Настроить рассылку новостей</a>
								</div>
							</div>
						</xsl:if>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<h2 class="social-title">Личные сообщения</h2>
								<hr/>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="row margin-bottom-30">
									<xsl:apply-templates select="message_topic"/>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row user-data margin-bottom-30">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<h2 class="social-title">Мои объявления</h2>
								<hr/>
							</div>
						</div>

						<div class="row" style="vertical-align:middle">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<a class="hostcms-button hostcms-button-red" href="/users/my_advertisement/">Просмотреть объявления</a>
								<span class="user-helpdesk-count"><xsl:value-of select="siteuser_advertisement_count"/></span>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<h2 class="social-title">Партнерские программы</h2>
								<hr/>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<a class="hostcms-button hostcms-button-red" href="/users/affiliats/">Просмотреть информацию о программах</a>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- Избранные товары -->
					<xsl:if test="favorite/shop_item/node()">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="row margin-top-20">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<h2 class="social-title">Избранные товары</h2>
									<hr/>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="row user-data">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="row text-align-center">
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
													<div class="row margin-bottom-10">
														<xsl:apply-templates select="favorite/shop_item" mode="image"/>
													</div>
													<div class="row margin-bottom-30">
														<xsl:apply-templates select="favorite/shop_item" mode="name"/>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</xsl:if>
				</div>
				<div class="row">
					<!-- Просмотренные товары -->
					<xsl:if test="viewed/shop_item/node()">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<h2 class="social-title">Просмотренные товары</h2>
									<hr/>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="row user-data">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="row text-align-center">
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
													<div class="row margin-bottom-10">
														<xsl:apply-templates select="viewed/shop_item" mode="image"/>
													</div>
													<div class="row margin-bottom-30">
														<xsl:apply-templates select="viewed/shop_item" mode="name"/>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</xsl:if>
				</div>
			</div>
			</xsl:when>
			<!-- Неавторизованный пользователь -->
			<xsl:otherwise>
				<div class="row authorization">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 separator-right">
						<h1>Личный кабинет</h1>

						<!-- Выводим ошибку, если она была передана через внешний параметр -->
						<xsl:if test="error/node()">
							<div id="error">
								<xsl:value-of select="error"/>
							</div>
						</xsl:if>

						<form action="/users/" method="post" class="form-horizontal">
							<div class="form-group">
								<label for="" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Пользователь:</label>
								<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
									<input name="login" type="text" size="30" class="large form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label">Пароль:</label>
								<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
									<input name="password" type="password" size="30" class="large form-control" />
								</div>
							</div>
							<div class="form-group">
								<!-- <label for="" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"><input name="remember" type="checkbox" /></label>-->
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
								</div>								
								<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
									<label class="control-label"><input name="remember" type="checkbox" /> Запомнить меня на сайте.</label>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"></label>
								<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
									<button class="hostcms-button" type="submit" name="apply" value="apply">Войти</button>
								</div>
							</div>

							<!-- Страница редиректа после авторизации -->
							<xsl:if test="location/node()">
								<input name="location" type="hidden" value="{location}" />
							</xsl:if>
						</form>

						<p>Забыли пароль? Мы можем его <a href="/users/restore_password/">восстановить</a>.</p>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 separator-left">
						<h1>Регистрация нового пользователя</h1>

						<span class="h3">Быстрая и простая регистрация:</span>

						<ul class="account">
							<li>История заказов.</li>
							<li>Управление лицензиями.</li>
							<li>Обращения в службу поддержки.</li>
							<li>Бонусные баллы.</li>
						</ul>

						<div class="form-group">
							<label for="" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label"></label>
							<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
								<a class="hostcms-button hostcms-button-red" href="/users/registration/">Зарегистрироваться</a>
							</div>
						</div>
					</div>
				</div>

				<xsl:if test="count(site/siteuser_identity_provider[image != '' and type = 1])">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="social-authorization">
								<h1>Войти через социальную сеть</h1>
								<xsl:for-each select="site/siteuser_identity_provider[image != '' and type = 1]">
									<xsl:element name="a">
										<xsl:attribute name="href">/users/?oauth_provider=<xsl:value-of select="@id"/><xsl:if test="/siteuser/location/node()">&amp;location=<xsl:value-of select="/siteuser/location"/></xsl:if></xsl:attribute>
										<xsl:attribute name="class">social-icon</xsl:attribute>
										<img src="{dir}{image}" alt="{name}" title="{name}" />
									</xsl:element><xsl:text> </xsl:text>
								</xsl:for-each>
							</div>
						</div>
					</div>
				</xsl:if>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:template>

	<xsl:template match="shop_order">
		<div class="row user-last-order">
			<div class="col-xs-3 col-sm-1 col-md-1 col-lg-1">
				<xsl:choose>
					<xsl:when test="paid = 0 and canceled = 0">
						<i class="fa fa-exclamation-circle fa-3x" title="Не оплачен"></i>
					</xsl:when>
					<xsl:when test="paid = 1">
						<i class="fa fa-check-circle fa-3x" title="Оплачен"></i>
					</xsl:when>
					<xsl:when test="canceled = 1">
						<i class="fa fa-times-circle fa-3x" title="Отменен"></i>
					</xsl:when>
				</xsl:choose>
			</div>
			<div class="col-xs-9 col-sm-3 col-md-3 col-lg-3">
				<xsl:text>Заказ № </xsl:text><xsl:choose><xsl:when test="invoice != ''"><xsl:value-of select="invoice"/></xsl:when>
				<xsl:otherwise><xsl:value-of select="@id"/></xsl:otherwise></xsl:choose>
			</div>
			<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">
				<xsl:value-of select="date"/>
			</div>
			<div class="col-xs-5 col-sm-3 col-md-3 col-lg-3">
				<xsl:value-of select="sum"/>
			</div>
			<div class="col-xs-4 col-sm-2 col-md-3 col-lg-3">
				<a class="hostcms-button hostcms-button-red" href="#" data-toggle="modal" data-target="#modal_{@id}">Подробнее</a>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="modal_{@id}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
						<h2 class="modal-title social-title" id="myModalLabel"><xsl:text>Заказ № </xsl:text><xsl:choose><xsl:when test="invoice != ''"><xsl:value-of select="invoice"/></xsl:when><xsl:otherwise><xsl:value-of select="@id"/></xsl:otherwise></xsl:choose><xsl:text> от </xsl:text><xsl:value-of select="date"/><xsl:text> г.</xsl:text> </h2>
					</div>

					<div class="modal-body">
						<xsl:if test="shop_order_status/node()">
							Статус:&#xA0;<b><xsl:value-of select="shop_order_status/name"/></b><xsl:if test="status_datetime != '0000-00-00 00:00:00'">, <xsl:value-of select="status_datetime"/></xsl:if>.
						</xsl:if>

						<xsl:if test="phone != ''">
							<br />Телефон:&#xA0;<strong><xsl:value-of select="phone"/></strong>
						</xsl:if>

						<xsl:if test="shop_delivery/node()">
							<br />Доставка:&#xA0;<strong><xsl:value-of select="shop_delivery/name"/></strong>
						</xsl:if>

						<xsl:if test="delivery_information != ''">
							<br />Информация об отправлении:&#xA0;<strong><xsl:value-of select="delivery_information"/></strong>
						</xsl:if>

						<div class="row user-order-modal margin-bottom-5">
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
							Название
							</div>
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
							Цена
							</div>
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
							Количество
							</div>
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
							Сумма
							</div>
						</div>

						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<xsl:apply-templates select="shop_order_item" mode="shop_order"/>
							</div>
						</div>

						<div class="row margin-top-10">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<b>Итого:</b><xsl:text> </xsl:text><xsl:value-of select="sum"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</xsl:template>

	<xsl:template match="shop_order_item" mode="shop_order">
		<div class="row margin-bottom-5">
			<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<xsl:value-of select="name"/>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
				<xsl:value-of select="format-number(price,'### ##0,00', 'my')"/>
				<!-- Показывать ли валюту -->
				<xsl:if test="../shop_currency/name != ''"><xsl:text> </xsl:text><xsl:value-of select="../shop_currency/name" disable-output-escaping="yes"/></xsl:if>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
				<xsl:value-of select="quantity"/>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
				<xsl:value-of select="format-number(price * quantity,'### ##0,00', 'my')"/><xsl:text> </xsl:text><xsl:value-of select="../shop_currency/name" disable-output-escaping="yes"/>
			</div>
		</div>
	</xsl:template>

	<xsl:template match="shop_item" mode="image">
		<div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
			<img class="user-img" src="{dir}{image_small}"/>
		</div>
	</xsl:template>

	<xsl:template match="shop_item" mode="name">
		<div class="col-xs-6 col-sm-6 col-md-2 col-lg-2">
			<a href="{url}"><xsl:value-of select="name"/></a>
		</div>
	</xsl:template>

	<xsl:template match="maillist">
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 padding-top-5">
			<xsl:value-of select="name"/>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
			<select name="type_{@id}" id="type_{@id}" class="form-control">
				<xsl:if test="maillist_siteuser/node()">
					<xsl:attribute name="disabled">disabled</xsl:attribute>
				</xsl:if>

				<option value="0">
					<xsl:if test="maillist_siteuser/node() and maillist_siteuser/type = 0">
						<xsl:attribute name="selected">selected</xsl:attribute>
					</xsl:if>
					Текст
				</option>
				<option value="1">
					<xsl:if test="maillist_siteuser/node() and maillist_siteuser/type = 1">
						<xsl:attribute name="selected">selected</xsl:attribute>
					</xsl:if>
					HTML
				</option>
			</select>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-align-right margin-top-5">
			<xsl:choose>
				<xsl:when test="maillist_siteuser/node()">
					<span class="hostcms-button">Подписан</span>
				</xsl:when>
				<xsl:otherwise>
					<span id="subscribed_{@id}" class="hostcms-button hidden">Подписан</span>

					<a id="subscribe_{@id}" class="hostcms-button hostcms-button-red" onclick="$.subscribeMaillist('maillist/', '{@id}', $('#type_{@id}').val())">Подписаться</a>
				</xsl:otherwise>
			</xsl:choose>
		</div>
	</xsl:template>

	<xsl:template match="message_topic">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-20">
			<a class="hostcms-button" href="/users/my_messages/"><xsl:value-of select="subject"/></a>
			<span class="user-helpdesk-count"><xsl:value-of select="count_recipient_unread"/></span>
		</div>
	</xsl:template>

	<xsl:template match="helpdesk_tickets/helpdesk_ticket">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 margin-bottom-10">
			<a href="/users/helpdesk/ticket-{@id}/">Запрос № <xsl:value-of select="number"/></a>
			<span class="user-helpdesk-count"><xsl:value-of select="messages_count - processed_messages_count"/></span>
		</div>
	</xsl:template>

	<xsl:template match="shop">
		<div class="row margin-bottom-30">
			<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
				<a href="{url}"><xsl:value-of select="name"/></a>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
				<xsl:value-of select="transaction_amount"/><xsl:text> </xsl:text><xsl:value-of select="shop_currency/name"/>
			</div>
			<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-align-right">
				<a class="hostcms-button hostcms-button-red" href="/users/account/pay/{@id}/">Пополнить</a>
				<a class="hostcms-button" href="/users/account/shop-{@id}/">История</a>
			</div>
		</div>
	</xsl:template>
</xsl:stylesheet>