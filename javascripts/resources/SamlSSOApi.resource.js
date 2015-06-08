(function (angular) {
    'use strict';

    var injectParams = ['$timeout', 'piwikApi'];
    var apiResource = function ($timeout, piwikApi) {
        var $this = this;

        $this.getSettings = function (dimensions) {
            var parameters;
            parameters = angular.extend(
                {
                    method: 'LoginSamlSSO.getSettings'
                },
                dimensions
            );

            return piwikApi.fetch(parameters);
        };

        $this.getSetting = function (dimensions) {
            var parameters = angular.extend(
                {
                    method: 'LoginSamlSSO.getSetting'
                },
                dimensions
            );

            return piwikApi.fetch(parameters);
        };

        $this.updateSettings = function (dimensions, postParams) {
            var parameters = angular.extend(
                {
                    method: 'LoginSamlSSO.updateSettings'
                },
                dimensions
            );

            return piwikApi.post(parameters, postParams);
        };
    };

    apiResource.$inject = injectParams;

    angular.module('piwikApp')
        .service('samlSSO.apiResource', apiResource);

}(window.angular));