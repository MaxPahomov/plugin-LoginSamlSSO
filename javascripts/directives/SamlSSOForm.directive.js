(function (angular) {
    'use strict';

    angular.module('piwikApp').directive(
        'samlSsoForm',
        function () {
            return {
                scope: false,
                restrict: 'A',
                templateUrl: 'plugins/LoginSamlSSO/templates/ng/form.directive.html',
                controller: 'samlSSO.FormCtrl',
                controllerAs: 'formCtrl',
                link: function (scope, element) {
                    element.tooltip({
                        track: true,
                        items: 'span[title]',
                        tooltipClass: 'rowActionTooltip',
                        show: false,
                        hide: false
                    });
                }
            };
        }
    );
}(window.angular));
