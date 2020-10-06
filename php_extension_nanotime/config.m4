PHP_ARG_ENABLE(nanotime, Whether to enable the nanotimePHP extension, [ --enable-nanotime Enable nanotimePHP])

if test "$NANOTIME" != "no"; then
    PHP_NEW_EXTENSION(nanotime, nanotime.c, $ext_shared)
fi
