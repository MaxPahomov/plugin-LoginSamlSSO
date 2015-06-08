# Piwik LoginSamlSSO Plugin

[![Build Status](https://travis-ci.org/PiwikPro/plugin-LoginSamlSSO.svg?branch=master)](https://travis-ci.org/PiwikPro/plugin-LoginSamlSSO)
[![Coverage Status](https://img.shields.io/coveralls/PiwikPro/plugin-LoginSamlSSO.svg)](https://coveralls.io/r/PiwikPro/plugin-LoginSamlSSO?branch=master)

## Description

**Piwik authentication module that allows users to log in to Piwik using Saml IdP.**


## Installation

### Requirements:
1. php in version at least 5.3
2. We recommend to use the latest Piwik version.

### Build steps:
1. Copy code of plugin into plugins/LoginSamlSSO directory in Piwik.
2. Enter plugins/LoginSamlSSO plugin. (cd plugins/LoginSamlSSO)
3. Download composer.phar (curl -sS https://getcomposer.org/installer | php)
4. Run composer install command. (php composer.phar install)

### Installation steps
1. Login as a superuser
2. On the _Manager > Plugins_ admin page, enable the LoginSamlSSO plugin
3. Navigate to the _Settings > Configure LoginSamlSSO_ page
4. Enter and save settings for your LoginSamlSSO plugin
5. Enable plugins/LoginSamlSSO/web/index.php file as public resource in web server. This file is endpoint for IdP and should be available for  SSO server as redirect endpoint. 
6. Refresh assets files to compile javascript files. (just remove content of tmp/assets and Piwik will generate them)
7. You can now login with Saml IdP.

_**Note:** To login with LoginSamlSSO plugin in to Piwik you have to create users with the same login like value in 
IdP Response attribute with key equal to samlAttributeKey config entry_


## SSO login debug logs
In Saml SSO Configuration panel superadmin can configure log level:
* error,
* warning,
* info,
* debug.

The higher level, the smaller number of entries in logs. The highest level is error level (the lowest is debug).

### Saml SSO authentication process:

1.  Redirect to Identity Provider (when "Log in with SSO" button on login screen is clicked)  
    **Log level**: info  
    **Log message**: Redirect to Identity Provider
2.  Before response from Identity Provider has come  
    **Log level**: info  
    **Log message**: Waiting for response from Identity Provider
3.  When response from Identity Provider has come  
    **Log level**: info  
    **Log message**: Identity Provider returned a response
4.  If there are some errors in response from Identity Provider  
    **Log level**: error  
    **Log message**: Library php-saml returned errors: "some errors here" with reason: "some reason here"
5.  If user has not been authenticated in Identity Provider  
    **Log level**: info  
    **Log message**: User not authenticated in Identity Provider
6.  If user has been authenticated in Identity Provider  
    **Log level**: info  
    **Log message**: User authenticated in Identity Provider
7.  If user is authenticated in Identity Provider and user based on attribute key was found in Piwik  
    **Log level**: info  
    **Log message**: User with login "some login here" authenticated in Piwik
8.  If user is authenticated in Identity Provider but proper user was not found in Piwik  
    **Log level**: error  
    **Log message**: User not authenticated in Piwik
    
### Library php-saml can throw exceptions at some point:
1.  \OneLogin_Saml2_Error exception  
    **Log level**: error  
    **Log message**: Exception thrown by library php-saml with message: "some message here"
2.  \Exception exception  
    **Log level**: error  
    **Log message**: Exception thrown by library php-saml with message: "some message here"
    
### Possible errors and exceptions throwing by php-saml library:
Possible exceptions are described below if the condition is not met.

*   **Condition**: Library verify that the url read from samlIdentityProviderSingleSignOnServiceUrl setting is to a http or https site  
    **Exception**: \OneLogin_Saml2_Error  
    **Exception message**: Redirect to invalid URL: "some url here"
*   Condition: Response from Identity Provider must be POST method and SAMLResponse in POST array must be set.  
    **Exception**: \OneLogin_Saml2_Error  
    **Exception message**: SAML Response not found, Only supported HTTP_POST Binding  
    In this case library also set 'invalid_binding' error, which is shown to user. Exception message (and not error message) is logged with error log level, because gives more informations about what happened. 
*   **Condition**: Decoded response from Identity Provider should have correct XML format  
    **Exception**: \Exception  
    **Exception message**: Detected use of ENTITY in XML, disabled to prevent XXE/XEE attacks
*   **Condition**: XML document (obtained from decoded response from Identity Provider) should be processed to DOM document  
    **Exception**: \Exception  
    **Exception message**: SAML Response could not be processed  
    Possible errors returned by library are described below if condition is not met. Errors will be transformed to the following log message: Library php-saml returned errors: "Errors" with reason: "Reason".
*   **Condition**: In DOM document there is attribute "Version" with value "2.0"  
    **Errors**: invalid_response  
    **Reason**: Unsupported SAML version
*   **Condition**: In DOM document there is attribute "ID"  
    **Errors**: invalid_response  
    **Reason**: Missing ID attribute on SAML response
*   **Condition**: DOM document contains Status element  
    **Errors**: invalid_response  
    **Reason**: Missing Status on response
*   **Condition**: DOM document contains Status Code element  
    **Errors**: invalid_response  
    **Reason**: Missing Status Code on response
*   **Condition**: DOM document Status Code element is Success  
    **Errors**: invalid_response  
    **Reason**: The Status Code of the Response was not Success, was "some status code"
*   **Condition**: DOM document contains only a single Assertion (encrypted or not)  
    **Errors**: invalid_response  
    **Reason**: SAML response must contain 1 assertion
*   **Condition**: DOM document has the expected signed nodes  
    **Errors**: invalid_response  
    **Reason**: Found an unexpected Signature Element. SAML Response rejected
*   **Condition**: DOM document contains Signature element  
    **Errors**: invalid_response  
    **Reason**: No Signature found. SAML Response rejected
*   **Condition**: DOM document contains Signature element  
    **Errors**: invalid_response  
    **Reason**: Signature validation failed. SAML Response rejected
*   **Condition**: Locate Signature Node in DOM document  
    **Errors**: invalid_response  
    **Reason**: Cannot locate Signature Node
*   **Condition**: Locate certificate Key in DOM document  
    **Errors**: invalid_response  
    **Reason**: We have no idea about the key
*   **Condition**: DOM document contains Reference element  
    **Errors**: invalid_response  
    **Reason**: Reference nodes not found
*   **Condition**: Validate Reference in DOM document  
    **Errors**: invalid_response  
    **Reason**: Reference validation failed
  

## Plugin configuration
Plugin requires following config entries in config.ini.php file:

    [LoginSamlSSO]  
    samlAuthClass = "\OneLogin_Saml2_Auth"  
    samlServiceProviderEntityId = "http://example.piwik.org/"  
    samlServiceProviderAssertionConsumerServiceUrl = "http://example.piwik.org/plugins/LoginSamlSSO/web/index.php"  
    samlServiceProviderNameIDFormat = "urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified"  
    samlServiceProviderPublicCertificate = "/absolute/path/to/sp/.cert-file"  
    samlServiceProviderPrivateKey = "/absolute/path/to/sp/.key-file"  
    samlIdentityProviderEntityId = "http://idp.example.com/saml2sso"  
    samlIdentityProviderSingleSignOnServiceUrl = "http://idp.example.com/saml2sso"  
    samlIdentityProviderPublicCertificate = "/absolute/path/to/idp/.cert-file"  
    samlIdentityProviderPrivateKey = "/absolute/path/to/idp/.key-file"  
    samlAttributeKey = employee_number  
    log_level = "debug"  
    log_writers[] = "file"  
    logger_file_path = ""  
    string_message_format = "%level% %tag%[%datetime%] %message%"  
    samlSecurityAuthnRequestsSigned = "true|false"  
    samlSecuritySignMetadata = "true|false"  
    samlSecurityWantMessagesSigned = "true|false"  
    samlSecurityWantAssertionsSigned = "true|false"  
    samlSecurityWantNameIdEncrypted = "true|false"
    
### Description of parameters:
*   **samlAuthClass** - fully-qualified namespace to Auth class (should stay as default)
*   **samlServiceProviderEntityId** - identifier of the SP entity (must be a URI)
*   **samlServiceProviderAssertionConsumerServiceUrl** - URL Location where the  from the IdP will be returned. This plugin supports endpoint for the HTTP-POST binding only.
*   **samlServiceProviderNameIDFormat** - Specifies the constraints on the name identifier to be used to represent the requested subject.
*   **samlServiceProviderPublicCertificate - **Absolute path to public x509 certificate of the SP.
*   **samlServiceProviderPrivateKey - **Absolute path to public private key of the SP.
*   **samlIdentityProviderEntityId** -Identifier of the IdP entity (must be a URI)
*   **samlIdentityProviderSingleSignOnServiceUrl** - SSO endpoint info of the IdP. (Authentication Request protocol) URL Target of the IdP where the Authentication Request Message will be sent.
*   **samlIdentityProviderPublicCertificate** - Absolute path to public x509 certificate of the IdP.
*   **samlIdentityProviderPrivateKey - **Absolute path to public private key of the IdP.
*   **samlAttributeKey** - Name of attribute from  which plugin should use as user login.
*   **log_level** - Level for logger. Available levels: error, warning, info, debug (in order of importance, default: warning)
*   **log_writers** - Array of writers. Available writers: screen, database, file
*   **logger_file_path** - Absolute or relative to Piwik tmp directory path for file logger. (default: PIWIK_ROOT_DIRECTORY/tmp/logs/samlsso.log)
*   **string_message_format** - logger message format. (default: %level% %tag%[%datetime%] %message%)
*   **samlSecurityAuthnRequestsSigned - **Indicates whether the  messages sent by this SP will be signed. (possible values: true or false)
*   **samlSecuritySignMetadata - **Indicates whether metadata should be signed. (possible values: true or false)
*   **samlSecurityWantMessagesSigned - **Indicates a requirement for the  elements received by this SP to be signed. (possible values: true or false)
*   **samlSecurityWantAssertionsSigned - **Indicates a requirement for the  elements received by this SP to be signed. (possible values: true or false)
*   **samlSecurityWantNameIdEncrypted - **Indicates a requirement for the NameID received by this SP to be encrypted. (possible values: true or false)