<?php
// Generates and stores a CSRF token for the current session.
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validates a submitted CSRF token against the session's token.
function verify_csrf_token($token) {
    return !empty($_SESSION['csrf_token'])
        && !empty($token)
        && hash_equals($_SESSION['csrf_token'], $token);
}

// Basic rate limiting based on IP, independent of username,
// so an attacker can't just cycle usernames to dodge the limit.
function is_ip_rate_limited() {
    $maxAttempts = 8;
    $windowSeconds = 300; // 5 minutes

    if (!isset($_SESSION['ip_attempts'])) {
        $_SESSION['ip_attempts'] = [];
    }

    $now = time();

    // Drop attempts older than the window
    $_SESSION['ip_attempts'] = array_filter(
        $_SESSION['ip_attempts'],
        fn($t) => $t > $now - $windowSeconds
    );

    return count($_SESSION['ip_attempts']) >= $maxAttempts;
}

function record_ip_attempt() {
    $_SESSION['ip_attempts'][] = time();
}