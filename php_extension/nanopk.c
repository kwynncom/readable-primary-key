#include <php.h>  // for PHP's sake
#include "php_nanopk.h"

#include <time.h> // for C/C++, not PHP, for time, for nanotime (clock_gettime())

#include <inttypes.h> // for C/C++, not PHP, for tsc (rdtscp)
#include <stdint.h>
#include <x86intrin.h>

#include <sys/sysinfo.h> // for C/C++ for uptime

zend_function_entry nanopk_functions[] = {
    PHP_FE(nanopk, NULL)
    {NULL, NULL, NULL}
};

zend_module_entry nanopk_module_entry = {
    STANDARD_MODULE_HEADER,
    PHP_NANOPK_EXTNAME,
    nanopk_functions,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    PHP_NANOPK_VERSION,
    STANDARD_MODULE_PROPERTIES
};

ZEND_GET_MODULE(nanopk)
ZEND_BEGIN_ARG_INFO_EX(nanopk, 0, 0, 0)
ZEND_END_ARG_INFO()

void c_loc_rdtscp(uint64_t *tick,  uint64_t *cpuna) {
    uint32_t cpun;
    *tick  = (uint64_t)__rdtscp(&cpun);
    *cpuna = cpun;
} 

long int c_loc_nanopk() {
    struct timespec sts;
    int ret = clock_gettime(CLOCK_REALTIME, &sts);
    if (ret != 0) return 0;
    long int epochns = sts.tv_sec * 1000000000 + sts.tv_nsec;
    return epochns;
}

long int c_loc_sysinfo() {
    struct sysinfo ssi;

    int err = sysinfo(&ssi);
    if (err != 0) return -1;

    return ssi.uptime;    
}

PHP_FUNCTION(nanopk) {  
    
    uint64_t tick;
    uint64_t cpun;
    
    c_local_rdtscp(&tick, &cpun);

    array_init    (return_value);
    add_index_long(return_value, 0, c_loc_nanopk());
    add_index_long(return_value, 1, tick); 
    add_index_long(return_value, 2, cpun);
    add_index_long(return_value, 3, c_loc_sysinfo());
}
