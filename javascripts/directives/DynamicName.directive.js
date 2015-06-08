/**
 * @module ui.clearcode
 *
 * @author Mateusz Jonak <m.jonak@clearcode.cc>
 */

(function (angular) {
    'use strict';

    /**
     * Directive Dynamic Name for input
     *
     * @constructor
     * @ngInject
     * @export
     */
    function DynamicName() { // jshint ignore:line

        return {
            restrict: 'A',
            require: ['ngModel', '^form'],
            link: function (scope, element, attrs, ctrls) {
                ctrls[0].$name = scope.$eval(attrs.dynamicName) || attrs.dynamicName;
                ctrls[1].$addControl(ctrls[0]);
            }
        };
    }

    angular
        .module('piwikApp')
        .directive('dynamicName', DynamicName);



}(window.angular));