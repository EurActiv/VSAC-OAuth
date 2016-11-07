<?php

/**
 * This module provides the backend functionality for oauth login
 */

namespace VSAC;

//----------------------------------------------------------------------------//
//-- Framework required functions                                           --//
//----------------------------------------------------------------------------//

function oauth_depends()
{
    return driver_call('oauth', 'depends');
}

/** @see example_module_config_items() */
function oauth_config_items()
{
    return array();
}

/** @see example_module_sysconfig */
function oauth_sysconfig()
{
    return true;
}

/** @see example_module_test() */
function oauth_test()
{
    return true;
}


//----------------------------------------------------------------------------//
//-- Public API                                                             --//
//----------------------------------------------------------------------------//

/**
 * Redirect the user to the provider login screen
 *
 * @return void will die
 */
function oauth_forward_to_provider()
{
    return driver_call('oauth', 'forward_to_provider');
}

/**
 * Check if the oAuth auth code is set in the request and return it if so.
 *
 * @return string the auth code, or false if not set
 */
function oauth_request_code()
{
    return driver_call('oauth', 'request_code');
}

/**
 * Get the access token from the oauth provider
 *
 * @param string $code the code parameter returned by the oauth provider when
 * redirecting back to the login page
 *
 * @return string|false the oauth token, or false if validation failed
 */
function oauth_code_to_token($code)
{
    return driver_call('oauth', 'code_to_token', [$code]);
}

/**
 * Call back to the provider with the token to get the user's info
 *
 * @param string $token the token returned by oauth_code_to_token
 *
 * @return array the user info, or false if not found
 */
function oauth_token_to_user($token)
{
    return driver_call('oauth', 'token_to_user', [$token]);
}


/**
 * Check if the user returned by oauth_token_to_user should be granted access
 *
 * @param array $user the user returned by oauth_token_to_user
 *
 * @return bool the user should be authenticated or not
 */
function oauth_user_is_authorized($user)
{
    return driver_call('oauth', 'user_is_authorized', [$user]);
}
