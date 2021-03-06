<?php

defined('HOSTCMS') || exit('HostCMS: access denied.');

/**
 * Online shop.
 *
 * @package HostCMS 6\Shop
 * @version 6.x
 * @author Hostmake LLC
 * @copyright © 2005-2015 ООО "Хостмэйк" (Hostmake LLC), http://www.hostcms.ru
 */
class Shop_Order_Model extends Core_Entity
{
	/**
	 * Backend property
	 * @var string
	 */
	public $order_status_name = NULL;

	/**
	 * Values of all properties of item
	 * @var array
	 */
	protected $_propertyValues = NULL;

	/**
	 * Backend property
	 * @var int
	 */
	public $order_items = 1;

	/**
	 * Column consist item's name
	 * @var string
	 */
	protected $_nameColumn = 'invoice';

	/**
	 * One-to-many or many-to-many relations
	 * @var array
	 */
	protected $_hasMany = array(
		'shop_order_item' => array(),
		'shop_item_reserved' => array(),
		'shop_siteuser_transaction' => array()
	);

	/**
	 * List of preloaded values
	 * @var array
	 */
	protected $_preloadValues = array(
		'shop_country_location_city_area_id' => 0,
		'shop_country_location_city_id' => 0,
		'shop_country_location_id' => 0,
		'shop_country_id' => 0,
		'unloaded' => 0
	);

	/**
	 * Belongs to relations
	 * @var array
	 */
	protected $_belongsTo = array(
		'shop' => array(),
		'shop_country_location' => array(),
		'shop_country' => array(),
		'shop_country_location_city' => array(),
		'shop_country_location_city_area' => array(),
		'shop_delivery' => array(),
		'shop_delivery_condition' => array(),
		'siteuser' => array(),
		'shop_currency' => array(),
		'shop_order_status' => array(),
		'shop_payment_system' => array(),
		'source' => array(),
	);

	/**
	 * Default sorting for models
	 * @var array
	 */
	protected $_sorting = array(
		'shop_orders.datetime' => 'ASC',
	);

	/**
	 * Forbidden tags. If list of tags is empty, all tags will show.
	 * @var array
	 */
	protected $_forbiddenTags = array(
		'datetime',
		'payment_datetime',
		'status_datetime',
	);

	/**
	 * Constructor.
	 * @param int $id entity ID
	 */
	public function __construct($id = NULL)
	{
		parent::__construct($id);

		if (is_null($id))
		{
			$oUserCurrent = Core_Entity::factory('User', 0)->getCurrent();
			$this->_preloadValues['user_id'] = is_null($oUserCurrent) ? 0 : $oUserCurrent->id;
			$this->_preloadValues['guid'] = Core_Guid::get();
			$this->_preloadValues['ip'] = Core_Array::get($_SERVER, 'REMOTE_ADDR', '127.0.0.1');
			$this->_preloadValues['datetime'] = Core_Date::timestamp2sql(time());
			$this->_preloadValues['status_datetime'] = Core_Date::timestamp2sql(time());

			$this->_preloadValues['siteuser_id'] = Core::moduleIsActive('siteuser') && isset($_SESSION['siteuser_id']) ? intval($_SESSION['siteuser_id']) : 0;
		}
	}

	/**
	 * Delete object from database
	 * @param mixed $primaryKey primary key for deleting object
	 * @return Core_Entity
	 */
	public function delete($primaryKey = NULL)
	{
		if (is_null($primaryKey))
		{
			$primaryKey = $this->getPrimaryKey();
		}

		$this->id = $primaryKey;

		// Удаляем значения доп. свойств
		$aPropertyValues = $this->getPropertyValues();
		foreach($aPropertyValues as $oPropertyValue)
		{
			$oPropertyValue->Property->type == 2 && $oPropertyValue->setDir($this->getOrderPath());
			$oPropertyValue->delete();
		}

		$this->Shop_Order_Items->deleteAll(FALSE);

		// Удаляем связи с зарезервированными, прямая связь
		$this->Shop_Item_Reserveds->deleteAll(FALSE);

		return parent::delete($primaryKey);
	}

	/**
	 * Change cancel on opposite
	 * @return self
	 * @hostcms-event shop_order.onBeforeChangeStatusPaid
	 * @hostcms-event shop_order.onAfterChangeStatusPaid
	 */
	public function changeStatusPaid()
	{
		Core_Event::notify($this->_modelName . '.onBeforeChangeStatusPaid', $this);

		if ($this->shop_payment_system_id)
		{
			$oShop_Payment_System_Handler = Shop_Payment_System_Handler::factory(
				Core_Entity::factory('Shop_Payment_System', $this->shop_payment_system_id)
			);

			if ($oShop_Payment_System_Handler)
			{
				$oShop_Payment_System_Handler->shopOrder($this)->shopOrderBeforeAction(clone $this);
			}
			// HostCMS v. 5
			elseif (defined('USE_HOSTCMS_5') && USE_HOSTCMS_5)
			{
				$shop = new shop();
				$order_row = $shop->GetOrder($this->id);
			}
		}

		$this->paid == 0
			? $this->paid()
			: $this->cancelPaid();

		if ($this->shop_payment_system_id)
		{
			if ($oShop_Payment_System_Handler)
			{
				$oShop_Payment_System_Handler->changedOrder('changeStatusPaid');
			}
			// HostCMS v. 5
			elseif (defined('USE_HOSTCMS_5') && USE_HOSTCMS_5)
			{
				// Вызываем обработчик платежной системы для события сменя статуса HostCMS v. 5
				$shop->ExecSystemsOfPayChangeStatus($order_row['shop_system_of_pay_id'], array(
					'shop_order_id' => $this->id,
					'action' => 'status',
					// Предыдущие даные о заказе до редактирования
					'prev_order_row' => $order_row
				));
			}
		}

		Core_Event::notify($this->_modelName . '.onAfterChangeStatusPaid', $this);

		return $this;
	}

	/**
	 * Get sum of order
	 * @return float
	 */
	public function getAmount()
	{
		$sum = 0;

		$aOrderItems = $this->Shop_Order_Items->findAll();
		foreach($aOrderItems as $oOrderItem)
		{
			$sum += $oOrderItem->getAmount();
		}

		return $sum;
	}

	/**
	 * Get quantity of items in an order
	 * @return float
	 */
	public function getQuantity()
	{
		$quantity = 0;

		$aOrderItems = $this->Shop_Order_Items->findAll();
		foreach($aOrderItems as $oOrderItem)
		{
			$quantity += $oOrderItem->quantity;
		}

		return $quantity;
	}

	/**
	 * Get sum of order
	 * @return float
	 */
	public function getSum()
	{
		return $this->getAmount();
	}

	/**
	 * Get order sum with currency name
	 * @return string
	 */
	public function sum()
	{
		return sprintf("%.2f %s", $this->getAmount(), $this->Shop_Currency->name);
	}

	/**
	 * Backend callback method
	 * @return string
	 */
	public function weight()
	{
		$weight = 0;

		$aOrderItems = $this->Shop_Order_Items->findAll();
		foreach($aOrderItems as $oOrderItem)
		{
			$weight += $oOrderItem->Shop_Item->weight * $oOrderItem->quantity;
		}
		return sprintf("%.2f", $weight);
	}

	/**
	 * Change cancel on opposite
	 * @return self
	 * @hostcms-event shop_order.onBeforeChangeStatusCanceled
	 * @hostcms-event shop_order.onAfterChangeStatusCanceled
	 */
	public function changeStatusCanceled()
	{
		Core_Event::notify($this->_modelName . '.onBeforeChangeStatusCanceled', $this);

		$oShop_Payment_System_Handler = Shop_Payment_System_Handler::factory(
			Core_Entity::factory('Shop_Payment_System', $this->shop_payment_system_id)
		);

		if ($oShop_Payment_System_Handler)
		{
			$oShop_Payment_System_Handler->shopOrder($this)->shopOrderBeforeAction(clone $this);
		}
		// HostCMS v. 5
		elseif (defined('USE_HOSTCMS_5') && USE_HOSTCMS_5)
		{
			$shop = new shop();
			$order_row = $shop->GetOrder($this->id);
		}

		$this->canceled = 1 - $this->canceled;
		$this->save();

		if ($oShop_Payment_System_Handler)
		{
			$oShop_Payment_System_Handler->changedOrder('cancelPaid');
		}
		// HostCMS v. 5
		elseif (defined('USE_HOSTCMS_5') && USE_HOSTCMS_5)
		{
			// Вызываем обработчик платежной системы для события сменя статуса HostCMS v. 5
			$shop->ExecSystemsOfPayChangeStatus($order_row['shop_system_of_pay_id'], array(
				'shop_order_id' => $this->id,
				'action' => $this->canceled ? 'cancel' : 'undoCancel',
				// Предыдущие даные о заказе до редактирования
				'prev_order_row' => $order_row
			));
		}

		Core_Event::notify($this->_modelName . '.onAfterChangeStatusCanceled', $this);

		return $this;
	}

	/**
	 * Recalc delivery price by delivery conditions
	 * @return boolean
	 * @hostcms-event shop_order.onBeforeRecalcDelivery
	 * @hostcms-event shop_order.onAfterRecalcDelivery
	 */
	public function recalcDelivery()
	{
		Core_Event::notify($this->_modelName . '.onBeforeRecalcDelivery', $this);

		$iOrderSum = 0;
		$iOrderWeight = 0;

		$aOrderItems = $this->Shop_Order_Items->findAll();
		$oShop = $this->Shop;

		foreach($aOrderItems as $oOrderItem)
		{
			$iOrderSum += floatval($oOrderItem->price * $oOrderItem->quantity);
			$iOrderWeight += floatval($oOrderItem->Shop_Item->weight * $oOrderItem->quantity);
		}

		$oShopDelivery = $this->Shop_Delivery_Condition->Shop_Delivery;
		$iCountryId = $this->shop_country_id;
		$iLocationId = $this->shop_country_location_id;
		$iCityId = $this->shop_country_location_city_id;
		$iCityAreaId = $this->shop_country_location_city_area_id;

		for($i = 0; $i < 5; $i++)
		{
			$sql = "
			SELECT `shop_delivery_conditions`.*,
			IF(`min_weight` > 0
				AND `max_weight` > 0
				AND `min_price` > 0
				AND `max_price` > 0, 1, 0) AS `orderfield`
			FROM `shop_deliveries`, `shop_delivery_conditions`
			WHERE `shop_id`='{$oShop->id}'
				AND `shop_deliveries`.`deleted`='0'
				AND `shop_delivery_conditions`.`deleted`='0'
				AND `shop_deliveries`.`id`=`shop_delivery_conditions`.`shop_delivery_id`
				AND `shop_delivery_conditions`.`shop_delivery_id`='{$this->Shop_Delivery_Condition->shop_delivery_id}'
				AND `shop_country_id`='{$iCountryId}'
				AND `shop_country_location_id`='{$iLocationId}'
				AND `shop_country_location_city_id` = '{$iCityId}'
				AND `shop_country_location_city_area_id` = '{$iCityAreaId}'
				AND `min_weight` <= '{$iOrderWeight}'
				AND (`max_weight` >= '{$iOrderWeight}' OR `max_weight` = '0')
				AND `min_price` <= '{$iOrderSum}'
				AND (`max_price` >= '{$iOrderSum}' OR `max_price` = '0')
			ORDER BY
				`orderfield` DESC,
				`min_weight` DESC,
				`max_weight` DESC,
				`min_price` DESC,
				`max_price` DESC,
				`price` DESC
			";

			$aRows = Core_DataBase::instance()
				->setQueryType(0)
				->query($sql)
				->asObject('Shop_Delivery_Condition_Model')
				->result()
			;

			$iRowCount = count($aRows);

			if ($iRowCount)
			{
				if ($iRowCount > 1)
				{
					Core::$log
					->clear()
					->status(1)
					->notify(TRUE)
					->write(Core::_('Shop_Order.cond_of_delivery_duplicate', $oShopDelivery->name, $aRows[0]->id));
				}


				if ($this->shop_delivery_condition_id == $aRows[0]->id)
				{
					// Нашли то же условие доставки
				}
				else
				{
					// Нашли новое условие доставки
					$this->shop_delivery_condition_id = $aRows[0]->id;
					$this->save();
				}

				return TRUE;
			}
			else
			{
				switch ($i)
				{
					case 0 :
						$iCityAreaId = 0;
					break;
					case 1 :
						$iCityId = 0;
					break;
					case 2 :
						$iLocationId = 0;
					break;
					case 3 :
						$iCountryId = 0;
					break;
				}
			}
		}

		// Не нашли никаких условий доставки
		$this->shop_delivery_condition_id = 0;
		$this->save();

		Core_Event::notify($this->_modelName . '.onAfterRecalcDelivery', $this);

		return TRUE;
	}

	/**
	 * Get orders by shop id
	 * @param int $shop_id shop id
	 * @return array
	 */
	public function getByShopId($shop_id)
	{
		$this->queryBuilder()
			//->clear()
		->where('shop_id', '=', $shop_id);

		return $this->findAll();
	}

	/**
	 * Show countries data in XML
	 * @var boolean
	 */
	protected $_showXmlCountry = FALSE;

	/**
	 * Show country in XML
	 * @param boolean $showXmlCountry
	 * @return self
	 */
	public function showXmlCountry($showXmlCountry = TRUE)
	{
		$this->_showXmlCountry = $showXmlCountry;
		return $this;
	}

	/**
	 * Show currency data in XML
	 * @var boolean
	 */
	protected $_showXmlCurrency = FALSE;

	/**
	 * Show currency in XML
	 * @param boolean $showXmlCurrency
	 * @return self
	 */
	public function showXmlCurrency($showXmlCurrency = TRUE)
	{
		$this->_showXmlCurrency = $showXmlCurrency;
		return $this;
	}

	/**
	 * Show siteuser data in XML
	 * @var boolean
	 */
	protected $_showXmlSiteuser = FALSE;

	/**
	 * Show siteuser in XML
	 * @param boolean $showXmlSiteuser
	 * @return self
	 */
	public function showXmlSiteuser($showXmlSiteuser = TRUE)
	{
		$this->_showXmlSiteuser = $showXmlSiteuser;
		return $this;
	}

	/**
	 * Show order items data in XML
	 * @var boolean
	 */
	protected $_showXmlItems = FALSE;

	/**
	 * Show items in XML
	 * @param boolean $showXmlItems
	 * @return self
	 */
	public function showXmlItems($showXmlItems = TRUE)
	{
		$this->_showXmlItems = $showXmlItems;
		return $this;
	}

	/**
	 * Show delivery data in XML
	 * @var boolean
	 */
	protected $_showXmlDelivery = FALSE;

	/**
	 * Show delivery in XML
	 * @param boolean $showXmlDelivery
	 * @return self
	 */
	public function showXmlDelivery($showXmlDelivery = TRUE)
	{
		$this->_showXmlDelivery = $showXmlDelivery;
		return $this;
	}

	/**
	 * Show payment systems data in XML
	 * @var boolean
	 */
	protected $_showXmlPaymentSystem = FALSE;

	/**
	 * Show payment system in XML
	 * @param boolean $showXmlPaymentSystem
	 * @return self
	 */
	public function showXmlPaymentSystem($showXmlPaymentSystem = TRUE)
	{
		$this->_showXmlPaymentSystem = $showXmlPaymentSystem;
		return $this;
	}

	/**
	 * Show order statuses data in XML
	 * @var boolean
	 */
	protected $_showXmlOrderStatus = FALSE;

	/**
	 * Show order's status in XML
	 * @param boolean $showXmlOrderStatus
	 * @return self
	 */
	public function showXmlOrderStatus($showXmlOrderStatus = TRUE)
	{
		$this->_showXmlOrderStatus = $showXmlOrderStatus;
		return $this;
	}

	/**
	 * Show properties in XML
	 * @var boolean
	 */
	protected $_showXmlProperties = FALSE;

	/**
	 * Show properties in XML
	 * @param mixed $showXmlProperties array of allowed properties ID or boolean
	 * @return self
	 */
	public function showXmlProperties($showXmlProperties = TRUE)
	{
		$this->_showXmlProperties = is_array($showXmlProperties)
			? array_combine($showXmlProperties, $showXmlProperties)
			: $showXmlProperties;

		return $this;
	}

	/**
	 * Get XML for entity and children entities
	 * @return string
	 * @hostcms-event shop_order.onBeforeRedeclaredGetXml
	 */
	public function getXml()
	{
		Core_Event::notify($this->_modelName . '.onBeforeRedeclaredGetXml', $this);

		$this
			->clearXmlTags()
			->addXmlTag('amount', $this->getAmount())
			->addXmlTag('payment_datetime', $this->payment_datetime == '0000-00-00 00:00:00'
				? $this->payment_datetime
				: strftime($this->Shop->Site->date_time_format, Core_Date::sql2timestamp($this->payment_datetime)))
			->addXmlTag('status_datetime', $this->status_datetime == '0000-00-00 00:00:00'
				? $this->status_datetime
				: strftime($this->Shop->Site->date_time_format, Core_Date::sql2timestamp($this->status_datetime)))
			->addXmlTag('date', $this->datetime == '0000-00-00 00:00:00'
				? $this->datetime
				: strftime($this->Shop->Site->date_format, Core_Date::sql2timestamp($this->datetime)))
			->addXmlTag('datetime', $this->datetime == '0000-00-00 00:00:00'
				? $this->datetime
				: strftime($this->Shop->Site->date_time_format, Core_Date::sql2timestamp($this->datetime)));

		$this->_showXmlCurrency && $this->shop_currency_id && $this->addEntity($this->Shop_Currency);

		$this->source_id && $this->addEntity(
			$this->Source->clearEntities()
		);

		if ($this->_showXmlProperties)
		{
			if (is_array($this->_showXmlProperties))
			{
				$aProperty_Values = Property_Controller_Value::getPropertiesValues($this->_showXmlProperties, $this->id);

				foreach ($aProperty_Values as $oProperty_Value)
				{
					if ($oProperty_Value->Property->type == 2)
					{
						$oProperty_Value
							->setHref($this->getOrderHref())
							->setDir($this->getOrderPath());
					}

					/*isset($this->_showXmlProperties[$oProperty_Value->property_id]) && */$this->addEntity(
						$oProperty_Value
					);
				}
			}
			else
			{
				$aProperty_Values = $this->getPropertyValues();
				// Add all values
				$this->addEntities($aProperty_Values);
			}
		}

		if ($this->_showXmlCountry && $this->shop_country_id)
		{
			$oShop_Country = $this->Shop_Country->clearEntities();

			if ($this->shop_country_location_id)
			{
				$oShop_Country_Location = $this->Shop_Country_Location;
				$oShop_Country->addEntity($oShop_Country_Location);

				if ($this->shop_country_location_city_id)
				{
					$oShop_Country_Location_City = $this->Shop_Country_Location_City;
					$oShop_Country_Location->addEntity($oShop_Country_Location_City);

					if ($this->shop_country_location_city_area_id)
					{
						$oShop_Country_Location_City_Area = $this->Shop_Country_Location_City_Area;
						$oShop_Country_Location_City->addEntity($oShop_Country_Location_City_Area);
					}
				}
			}

			$this->addEntity($oShop_Country);
		}

		if ($this->_showXmlDelivery && $this->shop_delivery_condition_id)
		{
			$oShop_Delivery_Condition = $this->Shop_Delivery_Condition->clearEntities();
			$oShop_Delivery = $oShop_Delivery_Condition->Shop_Delivery
				->clearEntities()
				->addEntity($oShop_Delivery_Condition);
			$this->addEntity($oShop_Delivery);
		}

		$this->_showXmlPaymentSystem && $this->shop_payment_system_id && $this->addEntity($this->Shop_Payment_System);

		$this->_showXmlOrderStatus && $this->shop_order_status_id && $this->addEntity($this->Shop_Order_Status);

		$this->_showXmlSiteuser && $this->siteuser_id && Core::moduleIsActive('siteuser') && $this->addEntity(
			$this->Siteuser->showXmlProperties($this->_showXmlProperties)
		);

		$amount = 0;
		$total_tax = 0;

		if ($this->_showXmlItems)
		{
			$aShop_Order_Items = $this->Shop_Order_Items->findAll();
			foreach ($aShop_Order_Items as $oShop_Order_Item)
			{
				$this->addEntity(
					$oShop_Order_Item->clearEntities()
						->showXmlProperties($this->_showXmlProperties)
						->showXmlItem(TRUE)
				);
				//$tax = $oShop_Order_Item->quantity * $oShop_Order_Item->price / (100 + $oShop_Order_Item->rate) * $oShop_Order_Item->rate;
				//$tax = Shop_Controller::instance()->round($oShop_Order_Item->price * $oShop_Order_Item->rate / 100);

				$total_tax += $oShop_Order_Item->getTax() * $oShop_Order_Item->quantity;
				$amount += $oShop_Order_Item->getAmount();
			}
		}

		// Total order amount
		$this->addEntity(
			Core::factory('Core_Xml_Entity')
				->name('total_amount')
				->value(Shop_Controller::instance()->round($amount))
		)->addEntity(
			Core::factory('Core_Xml_Entity')
				->name('total_tax')
				->value(Shop_Controller::instance()->round($total_tax))
		);

		return parent::getXml();
	}

	/**
	 * Pay the order
	 * @return self
	 * @hostcms-event shop_order.onBeforePaid
	 * @hostcms-event shop_order.onAfterPaid
	 */
	public function paid()
	{
		Core_Event::notify($this->_modelName . '.onBeforePaid', $this);

		if (!$this->paid)
		{
			$this->paid = 1;
			$this->payment_datetime = Core_Date::timestamp2sql(time());

			// Списать товары
			$this->_paidTransaction();

			// Удалить зарезервированные товары
			$this->Shop_Item_Reserveds->deleteAll();
		}

		Core_Event::notify($this->_modelName . '.onAfterPaid', $this);

		return $this->save();
	}

	/**
	 * Cancel payment
	 * @return self
	 * @hostcms-event shop_order.onBeforeCancelPaid
	 * @hostcms-event shop_order.onAfterCancelPaid
	 */
	public function cancelPaid()
	{
		Core_Event::notify($this->_modelName . '.onBeforeCancelPaid', $this);

		if ($this->paid)
		{
			$this->paid = 0;
			$this->payment_datetime = '0000-00-00 00:00:00';

			// Вернуть списанные товары
			$this->_paidTransaction();
		}

		Core_Event::notify($this->_modelName . '.onAfterCancelPaid', $this);

		return $this->save();
	}

	/**
	 * Списание или возврат товара на склад, начисление и стронирование операций по лицевому счету
	 *
	 */
	protected function _paidTransaction()
	{
		$oShop = $this->Shop;

		$mode = $this->paid == 0 ? -1 : 1;

		// Получаем список товаров заказа
		$aShop_Order_Items = $this->Shop_Order_Items->findAll();
		foreach ($aShop_Order_Items as $oShop_Order_Item)
		{
			// электронный товар
			if ($oShop_Order_Item->Shop_Item->type == 1
				&& $oShop_Order_Item->Shop_Order_Item_Digitals->getCount(FALSE) == 0)
			{
				// Получаем все файлы электронного товара
				$aShop_Item_Digitals = $oShop_Order_Item->Shop_Item->Shop_Item_Digitals->getBySorting();

				if (count($aShop_Item_Digitals))
				{
					// Указываем, какой именно электронный товар добавляем в заказ
					//$oShop_Order_Item->shop_item_digital_id = $aShop_Item_Digitals[0]->id;

					$countGoodsNeed = $oShop_Order_Item->quantity;

					foreach ($aShop_Item_Digitals as $oShop_Item_Digital)
					{
						if ($oShop_Item_Digital->count == -1 || $oShop_Item_Digital->count > 0)
						{
							if ($oShop_Item_Digital->count == -1)
							{
								$iCount = $countGoodsNeed;
							}
							// Списывам файлы, если их количество не равно -1
							else
							{
								$iCount = $oShop_Item_Digital->count < $countGoodsNeed
									? $oShop_Item_Digital->count
									: $countGoodsNeed;
							}

							for ($i = 0; $i < $iCount; $i++)
							{
								$oShop_Order_Item_Digital = Core_Entity::factory('Shop_Order_Item_Digital');
								$oShop_Order_Item_Digital->shop_item_digital_id = $oShop_Item_Digital->id;
								$oShop_Order_Item->add($oShop_Order_Item_Digital);

								$countGoodsNeed--;
							}

							// Списываем электронный товар, если он ограничен
							if ($oShop_Item_Digital->count != -1)
							{
								$oShop_Item_Digital->count -= $iCount * $mode;
								$oShop_Item_Digital->save();
							}

							if ($countGoodsNeed == 0)
							{
								break;
							}
						}
					}
				}

				$oShop_Order_Item->save();
			}
			// Пополнение лицевого счета
			elseif ($oShop_Order_Item->type == 2)
			{
				// Проведение/стронирование транзакции
				$oShop_Siteuser_Transaction = Core_Entity::factory('Shop_Siteuser_Transaction');
				$oShop_Siteuser_Transaction->shop_id = $oShop->id;
				$oShop_Siteuser_Transaction->siteuser_id = $this->siteuser_id;
				$oShop_Siteuser_Transaction->active = 1;
				$oShop_Siteuser_Transaction->amount_base_currency = $oShop_Siteuser_Transaction->amount = $oShop_Order_Item->price * $oShop_Order_Item->quantity * $mode;
				$oShop_Siteuser_Transaction->shop_order_id = $this->id;
				$oShop_Siteuser_Transaction->type = 0;
				$oShop_Siteuser_Transaction->description = $oShop_Order_Item->name;
				$oShop_Siteuser_Transaction->save();
			}

			// Списание/начисление товаров
			if ($oShop->write_off_paid_items)
			{
				$oShop_Warehouse = $oShop_Order_Item->shop_warehouse_id
					? $oShop_Order_Item->Shop_Warehouse
					: $oShop->Shop_Warehouses->getDefault();

				if (!is_null($oShop_Warehouse) && $oShop_Order_Item->Shop_Item->id)
				{
					$oShop_Warehouse_Item = $oShop_Warehouse->Shop_Warehouse_Items->getByShopItemId($oShop_Order_Item->Shop_Item->id);

					if (!is_null($oShop_Warehouse_Item))
					{
						$oShop_Warehouse_Item->count -= $oShop_Order_Item->quantity * $mode;
						$oShop_Warehouse_Item->save();
					}
				}
			}
		}

		// Транзакции пользователю за уровни партнерской программы
		if ($this->siteuser_id && Core::moduleIsActive('siteuser'))
		{
			$aSiteusers = array();
			// Получаем все дерево аффилиатов от текущего пользователя до самого верхнего в иерархии
			$level = 1; // Уровень начинается с 1
			$oSiteuserAffiliate = $this->Siteuser;
			do
			{
				$oSiteuserAffiliate = $oSiteuserAffiliate->Affiliate;

				if ($oSiteuserAffiliate->id)
				{
					$aSiteusers[$level] = $oSiteuserAffiliate;
				}
				else
				{
					break;
				}
				$level++;
			} while($oSiteuserAffiliate->id && $level < 30);

			// Есть аффилиаты, приведшие пользователя
			if (count($aSiteusers))
			{
				// Сумма заказа
				$fOrderAmount = $this->getAmount();

				// Количество товара в заказе
				$iQuantity = $this->getQuantity();

				// Цикл по партнерским программам магазина
				$oAffiliate_Plans = $oShop->Affiliate_Plans;
				$oAffiliate_Plans->queryBuilder()
					->where('affiliate_plans.min_count_of_items', '<=', $iQuantity)
					->where('affiliate_plans.min_amount_of_items', '<=', $fOrderAmount);

				$aAffiliate_Plans = $oAffiliate_Plans->findAll();
				foreach ($aAffiliate_Plans as $oAffiliate_Plan)
				{
					// Не включать стоимость доставки в расчет вознаграждения, вычитаем из суммы заказа
					if ($oAffiliate_Plan->include_delivery == 0)
					{
						$aShop_Order_Items = $this->Shop_Order_Items->findAll();
						foreach ($aShop_Order_Items as $oShop_Order_Item)
						{
							// Товар является доставкой
							if ($oShop_Order_Item->type == 1)
							{
								$fOrderAmount -= Shop_Controller::instance()->round(
									Shop_Controller::instance()->round($oShop_Order_Item->price + $oShop_Order_Item->getTax()) * $oShop_Order_Item->quantity
								);
							}
						}
					}

					$aAffiliate_Plan_Levels = $oAffiliate_Plan->Affiliate_Plan_Levels->findAll();
					foreach ($aAffiliate_Plan_Levels as $oAffiliate_Plan_Level)
					{
						if (isset($aSiteusers[$oAffiliate_Plan_Level->level]))
						{
							// Получаем сумму
							$sum = $oAffiliate_Plan_Level->type == 0
								? $fOrderAmount * ($oAffiliate_Plan_Level->percent / 100)
								: $oAffiliate_Plan_Level->value;

							if ($sum > 0)
							{
								// Транзакция начисление/списание бонусов
								$oShop_Siteuser_Transaction = Core_Entity::factory('Shop_Siteuser_Transaction');
								$oShop_Siteuser_Transaction->shop_id = $oShop->id;
								$oShop_Siteuser_Transaction->siteuser_id = $aSiteusers[$oAffiliate_Plan_Level->level]->id;
								$oShop_Siteuser_Transaction->active = 1;
								$oShop_Siteuser_Transaction->amount = $sum * $mode;

								// Сумма в виде процентов, расчитывается в валюте заказа
								if ($oAffiliate_Plan_Level->type == 0)
								{
									// Определяем коэффициент пересчета
									$fCurrencyCoefficient = $this->shop_currency_id > 0 && $oShop->shop_currency_id > 0
										? Shop_Controller::instance()->getCurrencyCoefficientInShopCurrency(
											$this->Shop_Currency, $oShop->Shop_Currency
										)
										: 0;

									$oShop_Siteuser_Transaction->amount_base_currency = $oShop_Siteuser_Transaction->amount * $fCurrencyCoefficient;
									$oShop_Siteuser_Transaction->shop_currency_id = $this->shop_currency_id;
								}
								else
								{
									// Фиксированное вознаграждение только в валюте магазина
									$oShop_Siteuser_Transaction->amount_base_currency = $oShop_Siteuser_Transaction->amount;
									$oShop_Siteuser_Transaction->shop_currency_id = $oShop->shop_currency_id;
								}

								$oShop_Siteuser_Transaction->shop_order_id = $this->id;
								$oShop_Siteuser_Transaction->type = 1;
								$oShop_Siteuser_Transaction->description = Core::_('Shop.form_edit_add_shop_special_prices_price', $this->id);
								$oShop_Siteuser_Transaction->save();
							}
						}
					}
				}
			}
		}

		return $this;
	}

	/**
	 * Get item path include CMS_FOLDER
	 * @return string
	 */
	public function getOrderPath()
	{
		return $this->Shop->getPath() . '/' . Core_File::getNestingDirPath($this->id, $this->Shop->Site->nesting_level) . '/order_' . $this->id . '/';
	}

	/**
	 * Get item href
	 * @return string
	 */
	public function getOrderHref()
	{
		return '/' . $this->Shop->getHref() . '/' . Core_File::getNestingDirPath($this->id, $this->Shop->Site->nesting_level) . '/order_' . $this->id . '/';
	}

	/**
	 * Create directory for item
	 * @return self
	 */
	public function createDir()
	{
		clearstatcache();

		if (!is_dir($this->getOrderPath()))
		{
			try
			{
				Core_File::mkdir($this->getOrderPath(), CHMOD, TRUE);
			} catch (Exception $e) {}
		}

		return $this;
	}

	/**
	 * Values of all properties of item
	 * Значения всех свойств товара
	 * @param boolean $bCache cache mode status
	 * @return array Property_Value
	 */
	public function getPropertyValues($bCache = TRUE)
	{
		if ($bCache && !is_null($this->_propertyValues))
		{
			return $this->_propertyValues;
		}

		// Warning: Need cache
		$aProperties = Core_Entity::factory('Shop_Order_Property_List', $this->shop_id)
			->Properties
			->findAll();

		//$aReturn = array();
		$aProperiesId = array();
		foreach ($aProperties as $oProperty)
		{
			$aProperiesId[] = $oProperty->id;
			//$aReturn = array_merge($aReturn, $this->_getPropertyValue($oProperty, $bCache));
		}

		$aReturn = Property_Controller_Value::getPropertiesValues($aProperiesId, $this->id);

		// setHref()
		foreach ($aReturn as $oProperty_Value)
		{
			if ($oProperty_Value->Property->type == 2)
			{
				$oProperty_Value
					->setHref($this->getOrderHref())
					->setDir($this->getOrderPath());
			}
		}

		if ($bCache)
		{
			$this->_propertyValues = $aReturn;
		}

		return $aReturn;
	}

	/**
	 * Copy object
	 * @return Core_Entity
	 */
	public function copy()
	{
		$newObject = parent::copy();
		$newObject->guid = Core_Guid::get();
		$newObject->save();
		$newObject->invoice = $newObject->id;
		$newObject->save();

		$aShop_Order_Items = $this->Shop_Order_Items->findAll();
		foreach($aShop_Order_Items as $oShop_Order_Item)
		{
			$newObject->add(clone $oShop_Order_Item);
		}

		return $newObject;
	}

	/**
	 * Add order CommerceML
	 * @param Core_SimpleXMLElement $oXml
	 */
	public function addCml(Core_SimpleXMLElement $oXml)
	{
		$oOrderXml = $oXml->addChild('Документ');
		$oOrderXml->addChild('Ид', $this->id);
		$oOrderXml->addChild('Номер', $this->id);
		$datetime = explode(' ', $this->datetime);
		$date = $datetime[0];
		$time = $datetime[1];
		$oOrderXml->addChild('Дата', $date);
		$oOrderXml->addChild('ХозОперация', 'Заказ товара');
		$oOrderXml->addChild('Роль', 'Продавец');
		$oOrderXml->addChild('Валюта', $this->Shop_Currency->code);
		$oOrderXml->addChild('Курс', $this->shop_currency_id > 0 && $this->Shop->shop_currency_id > 0 ? Shop_Controller::instance()->getCurrencyCoefficientInShopCurrency($this->Shop_Currency, $this->Shop->Shop_Currency) : 0);
		$oOrderXml->addChild('Сумма', $this->getAmount());

		$oContractor = $oOrderXml->addChild('Контрагенты');
		$oContractor = $oContractor->addChild('Контрагент');

		$aTmpArray = array();
		$this->surname != '' && $aTmpArray[] = $this->surname;
		$this->name != '' && $aTmpArray[] = $this->name;
		$this->patronymic != '' && $aTmpArray[] = $this->patronymic;

		!count($aTmpArray) && $aTmpArray[] = $this->email;

		$sContractorName = implode(' ', $aTmpArray);

		$sContractorId = $this->siteuser_id
			? $this->siteuser_id
			: Core::crc32($sContractorName);

		// При отсутствии модуля "Пользователи сайта" ИД пользователя рассчитывается как crc32($sContractorName)
		$oContractor->addChild('Ид', $sContractorId);
		$oContractor->addChild('Наименование', $sContractorName);
		$oContractor->addChild('Роль', 'Покупатель');
		$oContractor->addChild('ПолноеНаименование', $sContractorName);
		$oContractor->addChild('Фамилия', $this->surname);
		$oContractor->addChild('Имя', $this->name);
		$oContractor->addChild('Отчество', $this->patronymic);
		$oContractor->addChild('АдресРегистрации')->addChild('Представление', $this->address);

		// Адрес контрагента
		$oContractorAddress = $oContractor->addChild('Адрес');
		$oContractorAddress->addChild('Представление', implode(', ', array($this->postcode,$this->shop_country->name,$this->shop_country_location_city->name,$this->address)));
		$oAddressField = $oContractorAddress->addChild('АдресноеПоле');
		$oAddressField->addChild('Тип', 'Почтовый индекс');
		$oAddressField->addChild('Значение', $this->postcode);
		$oAddressField = $oContractorAddress->addChild('АдресноеПоле');
		$oAddressField->addChild('Тип', 'Страна');
		$oAddressField->addChild('Значение', $this->shop_country->name);
		$oAddressField = $oContractorAddress->addChild('АдресноеПоле');
		$oAddressField->addChild('Тип', 'Город');
		$oAddressField->addChild('Значение', $this->shop_country_location_city->name);
		$oAddressField = $oContractorAddress->addChild('АдресноеПоле');
		$oAddressField->addChild('Тип', 'Улица');
		$oAddressField->addChild('Значение', $this->address);
		$oAddressContacts = $oContractor->addChild('Контакты');
		$oContact = $oAddressContacts->addChild('Контакт');
		$oContact->addChild('Тип','Почта');
		$oContact->addChild('Значение', $this->email);
		$oContact = $oAddressContacts->addChild('Контакт');
		$oContact->addChild('Тип','Телефон');
		$oContact->addChild('Значение',$this->phone);

		// Статус оплаты заказа
		$oOrderProperties = $oOrderXml->addChild('ЗначенияРеквизитов');
		$oOrderProperty = $oOrderProperties->addChild('ЗначениеРеквизита');
		$oOrderProperty->addChild('Наименование', 'Заказ оплачен');
		$oOrderProperty->addChild('Значение', $this->paid == 1 ? 'true' : 'false');

		// Способ доставки
		$oOrderProperty = $oOrderProperties->addChild('ЗначениеРеквизита');
		$oOrderProperty->addChild('Наименование', 'Способ доставки');
		$oOrderProperty->addChild('Значение', $this->shop_delivery->name);

		// Метод оплаты
		$oOrderProperty = $oOrderProperties->addChild('ЗначениеРеквизита');
		$oOrderProperty->addChild('Наименование', 'Метод оплаты');
		$oOrderProperty->addChild('Значение', $this->shop_payment_system->name);
		////////////////////

		$oOrderXml->addChild('Время', $time);
		$oOrderXml->addChild('Комментарий', $this->description);

		$oOrderItems = $oOrderXml->addChild('Товары');

		$aOrderItems = $this->Shop_Order_Items->findAll(FALSE);

		foreach($aOrderItems as $oOrderItem)
		{
			$oCurrentItem = $oOrderItems->addChild('Товар');
			$oCurrentItem->addChild('Ид', $oOrderItem->Shop_Item->modification_id ? sprintf('%s#%s', $oOrderItem->Shop_Item->Modification->guid, $oOrderItem->Shop_Item->guid) : ($oOrderItem->type == 1 ? 'ORDER_DELIVERY' : $oOrderItem->Shop_Item->guid));
			$oCurrentItem->addChild('Наименование', $oOrderItem->name);
			$oCurrentItem->addChild('БазоваяЕдиница', $oOrderItem->Shop_Item->Shop_Measure->name);
			$oCurrentItem->addChild('ЦенаЗаЕдиницу', $oOrderItem->price);
			$oCurrentItem->addChild('Количество', $oOrderItem->quantity);
			$oCurrentItem->addChild('Сумма', $oOrderItem->getAmount());

			$oProperty = $oCurrentItem->addChild('ЗначенияРеквизитов');
			$oValue = $oProperty->addChild('ЗначениеРеквизита');
			$oValue->addChild('Наименование', 'ВидНоменклатуры');
			$oValue->addChild('Значение', $oOrderItem->type == 1 ? 'Услуга' : 'Товар');
			$oValue = $oProperty->addChild('ЗначениеРеквизита');
			$oValue->addChild('Наименование', 'ТипНоменклатуры');
			$oValue->addChild('Значение', $oOrderItem->type == 1 ? 'Услуга' : 'Товар');
		}

		return $this;
	}
}