<?php
add_action( 'init', function () {
    // Fix for undefined function error on feed generation with cron
    if ( ! function_exists( 'getallheaders' ) ) {
        return;
    }
    $request_headers = getallheaders();
    if ( isset( $request_headers['X-Hub-Signature-256'] ) ) {
        $git_dir_path = 'wp-content/plugins/wordpress-plugin-auto-git-pull'; // Add plugin directory name here - Get dynamically later
        $secret = 'G6sdCgcSs8dhfG0'; // Add secret string here - !Preferably save this in an ENV variable!
        $rawPost = file_get_contents('php://input');
        $github_hash = $request_headers['X-Hub-Signature-256'];
        $hash = 'sha256=' . hash_hmac( 'sha256', $rawPost, $secret );
        if ( $hash === $github_hash ) {
            exec( "cd " . $git_dir_path . " && git reset --hard && git pull" );
        }
    }
} );
