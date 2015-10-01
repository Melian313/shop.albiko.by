(function(jQuery){
	// Функции без создания коллекции
	jQuery.extend({
		bootstrapAddIntoCart: function(path, shop_item_id, count){
			$.clientRequest({
				path: path + '?add=' + shop_item_id + '&count=' + count,
				'callBack': $.bootstrapAddIntoCartCallback,
				context: $('.little-cart')
			});
			return false;
		},
		bootstrapAddIntoCartCallback: function(data, status, jqXHR)
		{
			$.loadingScreen('hide');
			jQuery(this).html(data);
		},
		subscribeMaillist: function(path, maillist_id, type){
			$.clientRequest({
				path: path + '?maillist_id=' + maillist_id + '&type=' + type,
				'callBack': $.subscribeMaillistCallback,
				context: $('#subscribed_' + maillist_id)
			});
			return false;
		},
		subscribeMaillistCallback: function(data, status, jqXHR)
		{
			$.loadingScreen('hide');
			jQuery(this).removeClass('hidden').next().hide();
		}
	});
})(jQuery);