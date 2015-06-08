/*!
 * Piwik - free/libre analytics platform
 *
 * Screenshot integration tests.
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

describe("LoginSamlSSOSettingsForm", function () {
    this.timeout(0);

    // uncomment this if you want to define a custom fixture to load before the test instead of the default one
    this.fixture = "Piwik\\Plugins\\LoginSamlSSO\\tests\\Fixtures\\Settings";

    var generalParams = 'idSite=1&period=day&date=2010-01-03';
    var contentSelector = '#content';

    before(function () {
        testEnvironment.pluginsToLoad = ['LoginSamlSSO'];
        testEnvironment.save();
    });

    it('should load a simple page by its module and action and take a partial screenshot', function (done) {
        var screenshotName, urlToTest;
        screenshotName  = 'partialView';
        urlToTest       = "?" + generalParams + "&module=LoginSamlSSO&action=configure";

        expect.screenshot(screenshotName).to.be.captureSelector(contentSelector, function (page) {
            page.load(urlToTest);
        }, done);
    });

    it('should disable save configuration button after add not valid value to input', function (done) {
        var screenshotName;
        screenshotName  = 'notValid';

        expect.screenshot(screenshotName).to.be.captureSelector(contentSelector, function (page) {
            page.evaluate(function () {
                angular.element('input[dynamic-name="samlServiceProviderAssertionConsumerServiceUrl"]').val('');
            });
            page.sendKeys('input[dynamic-name="samlServiceProviderAssertionConsumerServiceUrl"]', 'bad url');
        }, done);
    });

    it('should disable save configuration button after add not valid value to input 2', function (done) {
        var screenshotName, urlToTest;
        screenshotName  = 'notValid-2';
        urlToTest       = "?" + generalParams + "&module=LoginSamlSSO&action=configure";


        expect.screenshot(screenshotName).to.be.captureSelector(contentSelector, function (page) {
            page.load(urlToTest);
            page.sendKeys('input[dynamic-name="samlAttributeKey"]', 'empty');
            page.evaluate(function () {
                var formScope;

                formScope = angular.element('[saml-sso-form]').scope();
                angular.forEach(formScope.formCtrl.form.fields, function (elem) {
                    if (elem.name === 'samlAttributeKey') {
                        elem.value = '';
                    }
                });
                formScope.$digest();
            });
        }, done);
    });


    it('should save configuration by click submit button', function (done) {
        var screenshotName, urlToTest;
        screenshotName  = 'afterSubmit';
        urlToTest       = "?" + generalParams + "&module=LoginSamlSSO&action=configure";

        expect.screenshot(screenshotName).to.be.captureSelector(contentSelector, function (page) {
            page.load(urlToTest);
            page.click('button[type="submit"]', 3000);
        }, done);
    });

    it('should change log level and save configuration by click submit button', function (done) {
        var screenshotName, urlToTest;
        screenshotName  = 'afterSubmit-2';
        urlToTest       = "?" + generalParams + "&module=LoginSamlSSO&action=configure";

        expect.screenshot(screenshotName).to.be.captureSelector(contentSelector, function (page) {
            page.load(urlToTest);
            page.evaluate(function () {
                var formScope;

                formScope = angular.element('[saml-sso-form]').scope();
                angular.forEach(formScope.formCtrl.form.fields, function (elem) {
                    if (elem.name === 'log_level') {
                        elem.value = 'warning';
                    }
                });
                formScope.$digest();
            });
            page.click('button[type="submit"]', 3000);
        }, done);
    });
});