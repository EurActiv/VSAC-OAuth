<?php

/**
 * This module provides the backend functionality for oauth login
 */

namespace VSAC;


//----------------------------------------------------------------------------//
//-- Implementation                                                         --//
//----------------------------------------------------------------------------//

function oauth_generic_depends()
{
    return array('http', 'request', 'router', 'response');
}

/** @see oauth_forward_to_provider() */
function oauth_generic_forward_to_provider()
{
    $base = framework_config('oauth_authorization_url', '');
    $query = array_merge(
        framework_config('oauth_authorization_parameters', array()),
        array(
            'client_id'     => framework_config('oauth_client_id', ''),
            'redirect_uri'  => router_url('/login.php', true),
        )
    );
    $url = router_add_query($base, $query);
    response_redirect($url, false);
}

/** @see oauth_request_code() */
function oauth_generic_request_code()
{
    return request_query('code', false);
}

/** @see oauth_code_to_token() */
function oauth_generic_code_to_token($code)
{
    $url = framework_config('oauth_access_token_url', '');
    $data = array_merge(
        framework_config('oauth_access_token_parameters', array()),
        array(
            'code' => $code,
            'client_id' => framework_config('oauth_client_id', ''),
            'client_secret' => framework_config('oauth_client_secret', ''),
            'redirect_uri'  => router_url('/login.php', true),
        )
    );
    $response = http_post($url, $data);
    if ($response['error']) {
        return false;
    }

    $response = json_decode($response['body'], true);
    if (!is_array($response) || empty($response['access_token'])) {
        return false;
    }
    return $response['access_token'];
}

/** @see oauth_token_to_user() */
function oauth_generic_token_to_user($token)
{
    $url = framework_config('oauth_user_info_url', '');
    $header = framework_config('oauth_user_info_header', '');
    $headers = array(
        'Cache-Control: no-cache',
        sprintf($header, $token),
    );
    $options = array(CURLOPT_HTTPHEADER => $headers);
    $response = http_get($url, false, $options);
    if ($response['error']) {
        return false;
    }
    $user = json_decode($response['body'], true);
    return $user && is_array($user) ? $user : false;
}


/** @see oauth_user_is_authorized() */
function oauth_generic_user_is_authorized($user)
{
    $field = framework_config('oauth_authorize_match_field', '');
    $authorized_values = framework_config('oauth_authorize_values', array());
    $authorized_values = array_map('strtolower', $authorized_values);

    if (empty($user[$field])) {
        return false;
    }
    $values = is_array($user[$field]) ? $user[$field] : array($user[$field]);
    foreach ($values as $value) {
        if (in_array(strtolower($value), $authorized_values)) {
            return true;
        }
    }
    return false;
}








