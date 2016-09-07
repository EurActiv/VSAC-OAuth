<?php

namespace VSAC;

use_module('backend-all');
use_module('response');
use_module('router');
use_module('request');
use_module('oauth');


auth_start_session();

$login_error = function ($message) {
    $url = router_url('/login.php', true);
    $url = router_add_query($url, array('error' => $message));
    response_redirect($url, false);
};


if (request_query('logout')) {
    auth_set_authenticated(false);
    response_redirect(router_url('/index.php'), true);
}


if ($redirect = request_query('redirect')) {
    auth_set_redirect($redirect);
    $url = request_url();
    list($_url, $_query) = explode('?', $url, 2);
    parse_str($_query, $query);
    unset($query['redirect']);
    $url = router_add_query($_url, $query);
    response_redirect($url);
}


if ($error = request_query('error', false)) {
    backend_head('Authentication error');
    ?><p>We could not log you in because:</p>
    <pre><?= htmlspecialchars($error) ?></pre>
    <p class="text-center">
        <a class="btn btn-primary" href="<?= router_url('/index.php') ?>">Cancel</a>
        <a class="btn btn-primary" href="<?= router_url('/login.php') ?>">Retry</a>
    </p>
    <?php
    backend_foot();
} elseif ($code = oauth_request_code()) {
    if ($token = oauth_code_to_token($code)) {
        if ($user = oauth_token_to_user($token)) {
            if (oauth_user_is_authorized($user)) {
                auth_set_authenticated(true);
                auth_redirect();
            } else {
                $login_error('You do not have access to this system');
            }
        } else {
           $login_error('oAuth provider refused provide information');
        }
    } else {
        $login_error('oAuth provider refused to grant token');
    }
} else {
    oauth_forward_to_provider();
}

