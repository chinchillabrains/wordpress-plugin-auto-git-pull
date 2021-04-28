<?php
add_action( 'init', function () {
    // Fix for undefined function error on feed generation with cron
    if ( ! function_exists( 'getallheaders' ) ) {
        return;
    }
    $request_headers = getallheaders();
    // Check if request has github signature
    if ( isset( $request_headers['X-Hub-Signature-256'] ) ) {
        $raw_post     = file_get_contents( 'php://input' );
        $safe_to_pull = false;
        $request_data = json_decode( $raw_post, true );
        // Check if request is successful check_suite > successful github workflow run
        if ( isset( $request_headers['X-GitHub-Event'] ) && 'check_suite' == $request_headers['X-GitHub-Event'] ) {
            // $request_data = json_decode( $raw_post, true );
            if ( isset( $request_data['check_suite']['conclusion'] ) && 'success' == $request_data['check_suite']['conclusion'] ) {
                $safe_to_pull = true;
            }
        }
        if ( $safe_to_pull ) {
            $git_dir_path = 'wp-content/plugins/your-plugin-name'; // Add plugin directory name here - Get dynamically later
            $secret       = 'yourpluginsecret'; // Add secret string here - !Preferably save this in an ENV variable!
            $github_hash  = $request_headers['X-Hub-Signature-256'];
            $hash         = 'sha256=' . hash_hmac( 'sha256', $raw_post, $secret );
            if ( $hash === $github_hash ) {
                exec( 'cd ' . $git_dir_path . ' && git reset --hard && git pull' );
            }
        }
    }
} );
