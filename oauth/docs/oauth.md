#VSAC oAuth Extension - Documentation


##Configuration

After installing the extension, you will need to add some paramters to your framework config file (`config/_framework.php`). These parameters are:

    $config = array(
        // ...
        // the generic driver works for most oAuth v2 providers
        'oauth_driver'                   => 'genericoauth',

        // your application's client id (public id) with the oauth provider
        'oauth_client_id'                => 'my_application_id',

        // your application's client secret with the oauth provider
        'oauth_client_secret'            => 'keyboard_cat',

        // The base url that users will be redirected to to login (ie, the
        // resource owner's login screen).
        'oauth_authorization_url'        => 'https://example.com/oauth/v2/auth',

        // any query parameters to pass to the login url in addition to
        // client_id and redirect_uri
        'oauth_authorization_parameters' => array(
            'scope'         => 'user',
            'response_type' => 'code',
            'grant_type'    => 'authorization_code',
        ),

        // The URL to convert the login response code to an access token
        'oauth_access_token_url'        => 'https://example.com/oauth/v2/token',

        // any post parameters to pass to the token url in addition to 
        // code, client_id, client_secret and redirect_uri
        'oauth_access_token_parameters' => array(
            'grant_type' => 'authorization_code',
        ),

        // The URL to query for the user info. The generic driver will expect
        // this endpoint to return JSON.
        'oauth_user_info_url'           => 'https://example.com/oauth/v2/auth',

        // The format for the authorization header to send with the user info
        // request.  Will be passed to sprintf with the token as the only parameter
        'oauth_user_info_header'        => 'Authorization: Bearer %s',

        // The field in the user info to match on for authorization
        'oauth_authorize_match_field'   => 'username',

        // The values to match the above field against.  If the field matches
        // any entry here, the user will be authenticated. Otherwise, they won't.
        // If the field is also an array, if any entry matches any value here,
        // the user will be authorized.
        'oauth_authorize_values'        => array('example-user'),
    );


##Adding Service Providers

The extension includes a generic driver for oAuth2 providers.  It will work without further modification provided:

 1. The service provider follows the oAuth2 standard fairly closely
 *  The service provider provides a user info endpoint that will return a JSON
    objects

If your service provider does not meed these requirements, you'll have implement your own driver (it's easy).  For a generic example of how to implement a driver, see the [VSAC examples extension][1]. 

##Examples

####Google Resource Owner

To use Google as an oauth backend, first create a [Google application with oAuth credentials][2]. For the purpose of this example, the application must request access to the user's email address.

Then, add the following lines to your framework configuration file:

    $config = array(
        // ...
        'oauth_driver'                   => 'genericoauth',
        'oauth_client_id'                => 'my-application-id.apps.googleusercontent.com',
        'oauth_client_secret'            => 'keyboard_cat',
        'oauth_authorization_url'        => 'https://accounts.google.com/o/oauth2/auth',
        'oauth_authorization_parameters' => array(
            'scope'         => 'email profile',
            'response_type' => 'code',
        ),
        'oauth_access_token_url'        => 'https://accounts.google.com/o/oauth2/token',
        'oauth_access_token_parameters' => array(
            'grant_type' => 'authorization_code',
        ),
        'oauth_user_info_url'           => 'https://www.googleapis.com/oauth2/v1/userinfo',
        'oauth_user_info_header'        => 'Authorization: Bearer %s',
        'oauth_authorize_match_field'   => 'email',
        'oauth_authorize_values'        => array('example@gmail.com'),
    );

That's it.


####FOSOAuthServerBundle

If you have a web application that acts as an oAuth server via the [Friends of Symfony oAuth Server Bundle][3], you can plug this app into it with the following parameters:


    $config = array(
        // ...
        'oauth_driver'                   => 'genericoauth',
        'oauth_client_id'                => 'my-client-id',
        'oauth_client_secret'            => 'keyboard_cat',
        'oauth_authorization_url'        => 'https://example.com/oauth/v2/auth',
        // these will obviously depend on your app's configuration
        'oauth_authorization_parameters' => array(
            'scope'         => 'user',
            'response_type' => 'code',
            'grant_type'    => 'authorization_code',
        ),
        'oauth_access_token_url'        => 'https://example.com/oauth/v2/auth',
        'oauth_access_token_parameters' => array(
            'grant_type' => 'authorization_code',
        ),
        // You'll need to create this endpoint yourself, in this example we'll
        // assume that the response includes an array with the user's
        // FOSUserBundle roles
        'oauth_user_info_url'           => 'https://example.com/oauth/v2/user',
        'oauth_user_info_header'        => 'Authorization: %s',
        'oauth_authorize_match_field'   => 'roles',
        'oauth_authorize_values'        => array('ROLE_SUPER_ADMIN'),
    );


[1]: https://github.com/EurActiv/VSAC-Examples/tree/master/examples/modules
[2]: https://developers.google.com/identity/protocols/OAuth2
[3]: https://github.com/FriendsOfSymfony/FOSOAuthServerBundle
