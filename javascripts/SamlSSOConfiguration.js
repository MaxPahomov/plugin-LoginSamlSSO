(function (angular) {
    'use strict';

    angular.module('piwikApp').directive(
        'samlSsoConfiguration',
        function () {
            return {
                scope: false,
                restrict: 'A',
                templateUrl: 'plugins/LoginSamlSSO/templates/ng/index.html'
            };
        }
    );
}(window.angular));
