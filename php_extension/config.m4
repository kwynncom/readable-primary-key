PHP_ARG_ENABLE(php_rdtscp, Whether to enable the rdtscpPHP extension, [ --enable-rdtscp-php Enable rdtscpPHP])

if test "$PHP_RDTSCP" != "no"; then
    PHP_NEW_EXTENSION(php_rdtscp, php_rdtscp.c, $ext_shared)
fi
