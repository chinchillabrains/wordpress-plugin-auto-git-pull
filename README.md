# wordpress-plugin-auto-git-pull
PHP snippet for WordPress plugin to execute git pull when repo gets pushed
- Add webhook to repository from Settings > Webhooks
- Copy/Paste webhook-listener.php snippet to your plugin main .php file
- Edit webhook-listener.php to change $git_dir_path & $secret
