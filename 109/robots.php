<?php
    header('Content-type: text/plain');
    $subdomainNotIndex = ['lanvyleather'];
    if (getenv('REMOTE_ADDR') == '127.0.0.1') {
        $rootDomain = '118.kmt';
    } else {
        $rootDomain = '110.vn';
    }

    define('HOST', $_SERVER['HTTP_HOST']);
    define('HTTP_HOST', '//'.$_SERVER['HTTP_HOST']);
    define('ROOT_DOMAIN', $rootDomain);

    $domain = get_domain(HTTP_HOST);
    $arr_host = explode('.', HOST);
    $arr_domain = explode('.', $domain);
    $hostName = ($arr_host[0] != 'www') ? $arr_host[0] : $arr_host[1];
    $hostNameDomain = ($arr_domain[0] != 'www') ? $arr_domain[0] : $arr_domain[1];
    if ($domain == ROOT_DOMAIN && in_array($hostName, $subdomainNotIndex)) {
        echo "User-agent: *\n";
        echo "Disallow: /\n";
    } else {
        include("robots.txt");
    }

    function get_domain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }
