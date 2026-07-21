# Web Server Upload Hardening

Uploaded files under `public/storage` must never be executed as PHP.

For nginx, add this before the general PHP location:

```nginx
location ~* ^/storage/.*\.(php|php[0-9]|phtml|phar)$ {
    deny all;
    return 403;
}
```

Apache is covered by `public/.htaccess` and `storage/app/public/.htaccess`, provided `AllowOverride` is enabled.
