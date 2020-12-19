#include <php.h>  // for PHP's sake
#include "php_nanopkdev.h"

#include <time.h> 

#include <inttypes.h> 
#include <stdint.h>
#include <x86intrin.h>

#include <sys/sysinfo.h> // for C/C++ for uptime

zend_function_entry nanopkdev_functions[] = {
    PHP_FE(nanotimedev, NULL)
    PHP_FE_END
};


PHP_MINIT_FUNCTION(nanopkdev)
{
    REGISTER_MAIN_LONG_CONSTANT("NANOPK_U", 1, CONST_CS|CONST_PERSISTENT);

    return SUCCESS;
}


zend_module_entry nanopkdev_module_entry = {
    STANDARD_MODULE_HEADER,
    PHP_NANOPKDEV_EXTNAME,
    nanopkdev_functions,
    PHP_MINIT(nanopkdev),
    NULL,
    NULL,
    NULL,
    NULL,
    PHP_NANOPKDEV_VERSION,
    STANDARD_MODULE_PROPERTIES
};

ZEND_GET_MODULE(nanopkdev)

struct timespec c_get_timespec() {
    struct timespec sts;
    clock_gettime(CLOCK_REALTIME, &sts); // not sure if need to do anything with int ret / return value
    return sts;
}

PHP_FUNCTION(nanotimedev) {  

    long arg = 0;

    if (ZEND_NUM_ARGS() > 0) if (zend_parse_parameters(ZEND_NUM_ARGS(), "l", &arg) == FAILURE) return;
  
    struct timespec sts = c_get_timespec();

    long int epochns = sts.tv_sec * 1000000000 + sts.tv_nsec;

    if (arg == 0) RETURN_LONG(epochns);
}

