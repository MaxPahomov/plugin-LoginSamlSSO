(function (angular) {
    'use strict';

    var injectParams = ['samlSSO.formService', 'samlSSO.uiService'];
    var SamlSSOFormCtrl = function (formService, uiService) {
        var vm = this;

        vm.form = formService;
        vm.initialLoad = true;

        function init() {
            vm.form.loadConfig().then(function () {
                vm.initialLoad = false;
            }, function () {
                vm.initialLoad = false;
                uiService.notification.show('Error with request for Saml SSO Config. Please try again later.',
                    {title: 'Error Request', context: 'error', type: 'toast'});
            });
        }

        init();

        vm.saveConfig = function (samlSsoForm) {
            if (samlSsoForm.$valid) {
                vm.form.save().then(function () {
                    uiService.notification.show('Config has been saved successfully',
                        {title: 'Success save', context: 'success', type: 'toast'});
                }, function () {
                    uiService.notification.show('Sorry, an error has occurred, please refresh the page and try again, or contact your administrator',
                        {title: 'Error save', context: 'error', type: 'toast'});
                });
            }
        };
    };

    SamlSSOFormCtrl.$inject = injectParams;

    angular.module('piwikApp')
        .controller('samlSSO.FormCtrl', SamlSSOFormCtrl);

}(window.angular));
