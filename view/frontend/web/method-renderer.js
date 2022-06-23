define(
    [
        'jquery',
        'mage/url',
        'Magento_Checkout/js/model/full-screen-loader',
        'uiComponent',
    ],
    function (
        $,
        urlBuilder,
        fullScreenLoader,
        Component,
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Mobbex_Subscriptions/method',
                redirectAfterPlaceOrder: false,
            },

            /**
             * Prepare data to place order.
             */
             beforePlaceOrder: function () {
                fullScreenLoader.startLoader();

                this.createSubscriber(function (response) {
                    // Validate response format
                    if (!response || !response.id)
                        return this.showError('Error al obtener la información del carrito');

                    // Create magento order
                    this.placeOrder();

                    // Open payment modal or redirect to mobbex
                    if (response.embed)
                        this.openModal(response);
                    else
                        window.top.location.href = response.url;
                });
            },

            /**
             * Create subscriber asynchronously.
             * 
             * @param {CallableFunction} callback
             */
            createSubscriber: function (callback) {
                $.ajax({
                    url: urlBuilder.build('mobbex_subscriptions/process/'),
                    method: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        callback(response);
                    },
                    error: function (request, errorMessage) {
                        return this.showError('Error al obtener la información del carrito: ' + errorMessage);
                    }
                });
            },

            /**
             * Open the Mobbex payment modal.
             * 
             * @param {Array} response Mobbex subscriber response.
             */
            openModal: function (response) {
                let mobbexEmbed = window.MobbexEmbed.init({
                    id: response.id,
                    sid: response.sid,
                    type: 'subscriber_source',
                    onResult: (data) => {
                        var status = data.status.code;
                        window.top.location.href = response.data.return_url + '&status=' + status + (data.id ? '&transactionId=' + data.id : '');
                    },
                    onClose: (cancelled) => {
                        // Only if cancelled
                        if (cancelled)
                            window.top.location.reload();
                    }
                });

                mobbexEmbed.open();
            },

            /**
             * Show error message.
             *
             * @param {String} errorMessage
             */
            showError: function (errorMessage) {
                fullScreenLoader.stopLoader();

                globalMessageList.addErrorMessage({
                    message: errorMessage
                });
            }
        });
    }
);