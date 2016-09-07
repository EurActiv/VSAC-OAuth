# VSAC-OAuth

This is A VSAC extension to enable oAuth login instead of hard-coded username:passphrase based login.

##Installation

Download either the whole extension or just the [PHAR](./oauth.phar) archive. Upload it to your web host and modify your front controller to use it. It should look something like this:

    <?php
    set_include_path(
        '/path/to/data/__application_phar__'
        . PATH_SEPARATOR .
        'phar:///path/to/vsac/application.phar'
    );
    require_once "application.php";
    VSAC\set_data_directory('/path/to/data');
    // this is the line you should add, after data directory and before bootstrap
    VSAC\add_include_path('phar://path/to/vsac/oauth.phar');

    VSAC\bootstrap_web($debug = false);
    VSAC\front_controller_dispatch();


## Configuration

After you've got it installed, you'll not be able to log in to your VSAC installation (your login page will raise an error). See the [main documentation page](./oauth/docs/oauth.md) for instructions on configuring the extension to use your oAuth provider or adding a custom oAuth provider.
