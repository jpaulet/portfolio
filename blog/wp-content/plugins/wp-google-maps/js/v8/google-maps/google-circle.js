/**
 * @namespace WPGMZA
 * @module GoogleCircle
 * @requires WPGMZA.Circle
 */
(function($) {
	
	WPGMZA.GoogleCircle = function(options, googleCircle)
	{
		var self = this;
		
		WPGMZA.Circle.call(this, options, googleCircle);
		
		if(googleCircle)
		{
			this.googleCircle = googleCircle;
		}
		else
		{
			this.googleCircle = new google.maps.Circle();
			this.googleCircle.wpgmzaCircle = this;
		}
		
		google.maps.event.addListener(this.googleCircle, "click", function() {
			self.dispatchEvent({type: "click"});
		});
		
		if(options)
		{
			var googleOptions = {};
			
			googleOptions = $.extend({}, options);
			delete googleOptions.map;
			delete googleOptions.center;
			
			if(options.center)
				googleOptions.center = new google.maps.LatLng({
					lat: options.center.lat,
					lng: options.center.lng
				});
			
			this.googleCircle.setOptions(googleOptions);
			
			if(options.map)
				options.map.addCircle(this);
		}
	}
	
	WPGMZA.GoogleCircle.prototype = Object.create(WPGMZA.Circle.prototype);
	WPGMZA.GoogleCircle.prototype.constructor = WPGMZA.GoogleCircle;
	
})(jQuery);