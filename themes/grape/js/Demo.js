(function(namespace, $) {
	"use strict";

	var Demo = function() {
		// Create reference to this instance
		var o = this;
		// Initialize app when document is ready
		$(document).ready(function() {
			o.initialize();
		});

	};
	var p = Demo.prototype;

	// =========================================================================
	// MEMBERS
	// =========================================================================

	p.tmp = null;

	// =========================================================================
	// INIT
	// =========================================================================

	p.initialize = function() {
		this._enableEvents();

		this._initButtonStates();
	};

	// =========================================================================
	// EVENTS
	// =========================================================================

	// events
	p._enableEvents = function() {
		var o = this;

		$('.box-head .tools .btn-refresh').on('click', function(e) {
			o._handleBoxRefresh(e);
		});
		$('.box-head .tools .btn-collapse').on('click', function(e) {
			o._handleBoxCollapse(e);
		});
		$('.box-head .tools .btn-close').on('click', function(e) {
			o._handleBoxClose(e);
		});
		$('.box-head .tools .menu-box-styling a').on('click', function(e) {
			o._handleBoxStyling(e);
		});
		$('#header .theme-selector a').on('click', function(e) {
			o._handleThemeSwitch(e);
		});
	};

	// handlers
	p._handleBoxRefresh = function(e) {
		var o = this;
		var box = $(e.currentTarget).closest('.box');
		boostbox.App.addBoxLoader(box);
		setTimeout(function() {
			boostbox.App.removeBoxLoader(box);
		}, 1500);
	};

	p._handleBoxCollapse = function(e) {
		var box = $(e.currentTarget).closest('.box');
		boostbox.App.toggleBoxCollapse(box);
	};

	p._handleBoxClose = function(e) {
		var box = $(e.currentTarget).closest('.box');
		boostbox.App.removeBox(box);
	};

	p._handleBoxStyling = function(e) {
		// Get selected style and active box
		var newStyle = $(e.currentTarget).data('style');
		var box = $(e.currentTarget).closest('.box');

		// Display the selected style in the dropdown menu
		$(e.currentTarget).closest('ul').find('li').removeClass('active');
		$(e.currentTarget).closest('li').addClass('active');

		// Find all boxes with a 'style-' class
		var styledBox = box.closest('[class*="style-"]');

		if (styledBox.length > 0 && (!styledBox.hasClass('style-white') && !styledBox.hasClass('style-transparent'))) {
			// If a styled box is found, replace the style with the selected style
			// Exclude style-white and style-transparent
			styledBox.attr('class', function(i, c) {
				return c.replace(/\bstyle-\S+/g, newStyle);
			});
		}
		else {
			// Create variable to check if a style is switched
			var styleSwitched = false;

			// When no boxes are found with a style, look inside the box for styled headers or body
			box.find('[class*="style-"]').each(function() {
				// Replace the style with the selected style
				// Exclude style-white and style-transparent
				if (!$(this).hasClass('style-white') && !$(this).hasClass('style-transparent')) {
					$(this).attr('class', function(i, c) {
						return c.replace(/\bstyle-\S+/g, newStyle);
					});
					styleSwitched = true;
				}
			});

			// If no style is switched, add 1 to the main Box
			if (styleSwitched === false) {
				box.addClass(newStyle);
			}
		}
	};

	p._handleThemeSwitch = function(e) {
		e.preventDefault();
		//var parts = $(e.currentTarget).attr('href').split('/');
		var newTheme = $(e.currentTarget).attr('href');//parts[parts.length-1];
		
		$('link').each(function(){
			var href = $(this).attr('href');
			href = href.replace(/(assets\/css\/)(.*)(\/)/g, 'assets/css/' + newTheme + '/');
			$(this).attr('href', href);
		});
	};
	
	// =========================================================================
	// BUTTON STATES (LOADING)
	// =========================================================================

	p._initButtonStates = function() {
		$('.btn-loading-state').click(function() {
			var btn = $(this);
			btn.button('loading');
			setTimeout(function() {
				btn.button('reset');
			}, 3000);
		});
	};

	// =========================================================================
	namespace.Demo = new Demo;
}(this.boostbox, jQuery)); // pass in (namespace, jQuery):
