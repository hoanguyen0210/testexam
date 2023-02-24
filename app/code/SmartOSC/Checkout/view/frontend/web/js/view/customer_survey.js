define([
	'ko',
	'uiComponent',
	'underscore',
	'Magento_Checkout/js/model/step-navigator'
], function (ko, Component, _, stepNavigator) {
	'use strict';

	return Component.extend({
		defaults: {
			template: 'SmartOSC_Checkout/customer_survey'
		},

		// add here your logic to display step,
		isVisible: ko.observable(true),

		/**
		 * @returns {*}
		 */
		initialize: function () {
			this._super();

			// register your step
			stepNavigator.registerStep(
				'survey',
				null,
				'Customer Survey',
				this.isVisible,
				_.bind(this.navigate, this),
				15
			);

			return this;
		},

		/**
		 * The navigate() method is responsible for navigation between checkout steps
		 * during checkout. You can add custom logic, for example some conditions
		 * for switching to your custom step
		 * When the user navigates to the custom step via url anchor or back button we_must show step manually here
		 */
		navigate: function () {
			this.isVisible(true);
		},

		/**
		 * @returns void
		 */
		navigateToNextStep: function () {
			stepNavigator.next();
		},
	});
});