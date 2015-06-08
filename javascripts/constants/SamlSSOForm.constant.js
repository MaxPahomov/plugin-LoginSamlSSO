(function (angular) {
    'use strict';

    angular
        .module('piwikApp')
        .constant('samlSSO.formConstant', [
            {
                label: 'Saml Attribute Key',
                name: 'samlAttributeKey',
                value: '',
                type: 'text',
                helpText: _pk_translate('LoginSamlSSO_HelpSamlAttributeKey')
            },
            {
                label: 'Service Provider Entity Id',
                name: 'samlServiceProviderEntityId',
                value: '',
                type: 'text',
                helpText: _pk_translate('LoginSamlSSO_HelpSamlServiceProviderEntityId')
            },
            {
                label: 'Service Provider Name ID Format',
                name: 'samlServiceProviderNameIDFormat',
                value: '',
                type: 'text',
                helpText: _pk_translate('LoginSamlSSO_HelpSamlServiceProviderNameIDFormat')
            },
            {
                label: 'Saml Service Provider Assertion Consumer Service Url',
                name: 'samlServiceProviderAssertionConsumerServiceUrl',
                value: '',
                regexPattern: /[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/,
                errorMsgPattern: 'This value should be a URL',
                type: 'text',
                helpText: _pk_translate('LoginSamlSSO_HelpSamlServiceProviderAssertionConsumerServiceUrl')
            },
            {
                label: 'Service Provider Public Certificate',
                name: 'samlServiceProviderPublicCertificate',
                value: '',
                type: 'text',
                allowEmpty: true,
                helpText: _pk_translate('LoginSamlSSO_HelpSamlServiceProviderPublicCertificate')
            },
            {
                label: 'Service Provider Private Key',
                name: 'samlServiceProviderPrivateKey',
                value: '',
                type: 'text',
                allowEmpty: true,
                helpText: _pk_translate('LoginSamlSSO_HelpSamlServiceProviderPrivateKey')
            },

            {
                label: 'Identity Provider Entity Id',
                name: 'samlIdentityProviderEntityId',
                value: '',
                type: 'text',
                helpText: _pk_translate('LoginSamlSSO_HelpSamlIdentityProviderEntityId')
            },
            {
                label: 'Identity Provider Single Sign On Service Url',
                name: 'samlIdentityProviderSingleSignOnServiceUrl',
                value: '',
                regexPattern: /[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/,
                errorMsgPattern: 'This value should be a URL',
                type: 'text',
                helpText: _pk_translate('LoginSamlSSO_HelpSamlIdentityProviderSingleSignOnServiceUrl')
            },
            {
                label: 'Identity Provider Public Certificate',
                name: 'samlIdentityProviderPublicCertificate',
                value: '',
                type: 'text',
                allowEmpty: true,
                helpText: _pk_translate('LoginSamlSSO_HelpSamlIdentityProviderPublicCertificate')
            },
            {
                label: 'Identity Provider Private Key',
                name: 'samlIdentityProviderPrivateKey',
                value: '',
                type: 'text',
                allowEmpty: true,
                helpText: _pk_translate('LoginSamlSSO_HelpSamlIdentityProviderPrivateKey')
            },
            {
                label: 'Log level',
                name: 'log_level',
                value: '',
                options: ['error', 'warning', 'info', 'debug'],
                type: 'select',
                helpText: _pk_translate('LoginSamlSSO_HelpSamlLogLevel')
            },
            {
                label: 'Sign service provider request',
                name: 'samlSecurityAuthnRequestsSigned',
                value: '',
                options: ['true', 'false'],
                type: 'select',
                helpText: _pk_translate('LoginSamlSSO_HelpSamlSecurityAuthnRequestsSigned')
            },
            {
                label: 'Sign the metadata',
                name: 'samlSecuritySignMetadata',
                value: '',
                options: ['true', 'false'],
                type: 'select',
                helpText: _pk_translate('LoginSamlSSO_HelpSamlSecuritySignMetadata')
            },
            {
                label: 'Signed message response',
                name: 'samlSecurityWantMessagesSigned',
                value: '',
                options: ['true', 'false'],
                type: 'select',
                helpText: _pk_translate('LoginSamlSSO_HelpSamlSecurityWantMessagesSigned')
            },
            {
                label: 'Signed assertion in response',
                name: 'samlSecurityWantAssertionsSigned',
                value: '',
                options: ['true', 'false'],
                type: 'select',
                helpText: _pk_translate('LoginSamlSSO_HelpSamlSecurityWantAssertionsSigned')
            },
            {
                label: 'Encrypted NameID in response',
                name: 'samlSecurityWantNameIdEncrypted',
                value: '',
                options: ['true', 'false'],
                type: 'select',
                helpText: _pk_translate('LoginSamlSSO_HelpSamlSecurityWantNameIdEncrypted')
            },
        ]);

}(window.angular));