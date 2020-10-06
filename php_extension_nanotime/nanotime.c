#include <php.h>  // for PHP's sake
#include "php_nanotime.h"

#include <time.h> // for C/C++, not PHP

zend_function_entry nanotime_functions[] = {
    PHP_FE(nanotime, NULL)
    {NULL, NULL, NULL}
};

zend_module_entry nanotime_module_entry = {
    STANDARD_MODULE_HEADER,
    PHP_NANOTIME_EXTNAME,
    nanotime_functions,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    PHP_NANOTIME_VERSION,
    STANDARD_MODULE_PROPERTIES
};

ZEND_GET_MODULE(nanotime)
ZEND_BEGIN_ARG_INFO_EX(nanotime, 0, 0, 0)
ZEND_END_ARG_INFO()

long int c_loc_nanotime() {
    struct timespec sts;
    int ret = clock_gettime(CLOCK_REALTIME, &sts);
    if (ret != 0) return 0;
    long int epochns = sts.tv_sec * 1000000000 + sts.tv_nsec;
    return epochns;
}

PHP_FUNCTION(nanotime) {  RETURN_LONG(c_loc_nanotime()); }
