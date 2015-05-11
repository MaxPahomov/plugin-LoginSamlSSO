(function (angular) {
    'use strict';

    var injectParams, formService;

    injectParams = ['samlSSO.apiResource', 'samlSSO.formConstant'];
    formService = function (apiResource, formConstant) {
        var service = this;

        service.fields = angular.copy(formConstant);
        service.loading = false;

        /**
         * @ngdoc method
         * @name formService
         * @method loadConfig
         * @description
         * Load config - settings for saml sso
         *
         * @returns {Object} $defer for getAllConfig
         */
        service.loadConfig = function () {
            var promiseFetch;

            service.loading = true;
            promiseFetch = apiResource.getSettings();
            promiseFetch.then(function (data) {
                service.loading = false;
                service.setFields(data);
            }, function () {
                service.loading = false;
            });

            return promiseFetch;
        };

        /**
         * @ngdoc method
         * @name formService
         * @method setFields
         * @description
         * Set loaded settings to form fields
         *
         * @returns {Array} Form with loaded value
         */
        service.setFields = function (configData) {
            if (!angular.isObject(configData)) {
                throw {message: 'formService.setFields expects first argument to be Object but received ' + (typeof configData) };
            }
            angular.forEach(service.fields, function (field) {
                if (configData.hasOwnProperty(field.name)) {
                    field.value = configData[field.name];
                }
            });
            return service.fields;
        };

        /**
         * @ngdoc method
         * @name formService
         * @method testPattern
         * @description
         * Tests for pattern of some field
         *
         * @returns {Object} object of RegEx with test function
         */
        service.testPattern = (function () {
            var regexTruth = {
                test: function () {
                    return true;
                }
            };
            return function (field) {
                var regex = field.regexPattern;
                return angular.isUndefined(regex) ? regexTruth : {
                    test: function (value) {
                        return regex.test(value);
                    }
                };
            };
        }());

        /**
         * @ngdoc method
         * @name formService
         * @method save
         * @description
         * Save congif - settings on server
         *
         * @returns {Object} $defer for updateSettings
         */
        service.save = function () {
            var params, promiseUpdate;

            params = {data: {}};
            angular.forEach(service.fields, function (elem) {
                params.data[elem.name] = elem.value;
            });

            service.loading = true;
            promiseUpdate = apiResource.updateSettings({}, params);
            promiseUpdate.then(function () {
                service.loading = false;
            }, function () {
                service.loading = false;
            });

            return promiseUpdate;
        };
    };

    formService.$inject = injectParams;

    angular.module('piwikApp')
        .service('samlSSO.formService', formService);

}(window.angular));