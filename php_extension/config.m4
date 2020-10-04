PHP_ARG_ENABLE(rdtscp, Whether to enable the rdtscpPHP extension, [ --enable-rdtscp Enable rdtscpPHP])

if test "$RDTSCP" != "no"; then
    PHP_NEW_EXTENSION(rdtscp, rdtscp.c, $ext_shared)
fi
