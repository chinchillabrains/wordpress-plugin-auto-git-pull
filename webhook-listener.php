<?php
add_action( 'init', function () {
    $request_headers = getallheaders();
    if ( isset( $request_headers['X-Hub-Signature-256'] ) ) {
        $git_dir_path = 'wp-content/plugins/wordpress-plugin-auto-git-pull'; // Add plugin directory name here - Get dynamically later
        $secret = 'G4p7aS4uBEANtmE2'; // Add secret string here - !Preferably save this in an ENV variable!
        $rawPost = file_get_contents('php://input');
        $github_hash = $request_headers['X-Hub-Signature-256'];
        $hash = 'sha256=' . hash_hmac( 'sha256', $rawPost, $secret );
        if ( $hash === $github_secret_hash ) {
            exec( "cd " . $git_dir_path . " && git pull" );
        }
    }
} );
