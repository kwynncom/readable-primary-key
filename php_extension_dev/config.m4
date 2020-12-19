PHP_ARG_ENABLE(nanopk, Whether to enable the nanopkdevPHP extension, [ --enable-nanopk_dev Enable nanopkPHP])

if test "$NANOPK_DEV" != "no"; then
    PHP_NEW_EXTENSION(nanopk_dev, nanopk_dev.c, $ext_shared)
fi
