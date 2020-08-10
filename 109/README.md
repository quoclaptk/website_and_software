# Guide

## Requirements
1. Node js
2. Yarn, Npm

## Deploy
1. Add file .htaccess
```
<IfModule mod_rewrite.c>
    RewriteEngine On
	# add / to last url
	RewriteRule ^[^/]+$ %{REQUEST_URI}/ [L,R=301]
	RewriteRule ^(node_modules)($|/) - [L]
    RewriteRule ^robots.php - [L]
    RewriteRule ^translate.php - [L]
    RewriteRule ^$ public/     [L]
    RewriteRule (.*) public/$1 [L]
    RewriteCond %{HTTP_HOST} ^www\.(.+) [NC]
</IfModule>
```	
2. Run`composer install`
3. Run `yarn install` or `npm install`
4. Run `yarn run dev`

