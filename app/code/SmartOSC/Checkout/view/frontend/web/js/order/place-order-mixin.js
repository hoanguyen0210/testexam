define([
	'jquery',
	'mage/utils/wrapper',
	'Magento_CheckoutAgreements/js/model/agreements-assigner',
	'Magento_Checkout/js/model/quote',
	'Magento_Customer/js/model/customer',
	'Magento_Checkout/js/model/url-builder',
	'mage/url',
	'Magento_Checkout/js/model/error-processor',
	'uiRegistry'
], function (
	$,
	wrapper,
	agreementsAssigner,
	quote,
	customer,
	urlBuilder,
	urlFormatter,
	errorProcessor,
	registry
) {
	'use strict';

	return function (placeOrderAction) {

		/** Override default place order action and add agreement_ids to request */
		return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, messageContainer) {
			agreementsAssigner(paymentData);
			var isCustomer = customer.isLoggedIn();
			var quoteId = quote.getQuoteId();

			var url = urlFormatter.build('smtcheckout/quote/save');

			var customerHobbies = $('[name="hobbies"]').val();
			var customerInCome = $('[name="income"]').val();

			if (customerHobbies || customerInCome) {

				var payload = {
					'cartId': quoteId,
					'hobbies': customerHobbies,
					'income': customerInCome,
					'is_customer': isCustomer
				};

				if (!payload.hobbies) {
					return true;
				}

				var result = true;

				$.ajax({
					url: url,
					data: payload,
					dataType: 'text',
					type: 'POST',
				}).done(
					function (response) {
						result = true;
					}
				).fail(
					function (response) {
						result = false;
						errorProcessor.process(response);
					}
				);
			}

			return originalAction(paymentData, messageContainer);
		});
	};
});
