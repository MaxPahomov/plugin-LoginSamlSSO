(function (angular, require, Piwik_Popover) {
    'use strict';

    var uiService, UI;

    UI = require('piwik/UI');
    uiService = function () {
        var service = this;

        service.notification = new UI.Notification();
        service.popover = Piwik_Popover;
    };

    angular.module('piwikApp')
        .service('samlSSO.uiService', uiService);

}(window.angular, require, Piwik_Popover));