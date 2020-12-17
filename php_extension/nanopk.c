#include <php.h>  // for PHP's sake
#include "php_nanopk.h"

#include <time.h> // for C/C++, not PHP, for time, for nanotime (clock_gettime())

#include <inttypes.h> // for C/C++, not PHP, for tsc (rdtscp)
#include <stdint.h>
#include <x86intrin.h>

#include <sys/sysinfo.h> // for C/C++ for uptime

zend_function_entry nanopk_functions[] = {
    PHP_FE(nanopk, NULL)
    PHP_FE(nanotime, NULL)
    PHP_FE(rdtscp, NULL)
    PHP_FE(rdtscp_assoc, NULL)
    PHP_FE(uptime, NULL)
    PHP_FE_END
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

void c_local_rdtscp(uint64_t *tick,  uint64_t *cpuna) {
    uint32_t cpun;
    *tick  = (uint64_t)__rdtscp(&cpun);
    *cpuna = cpun;
} 

PHP_FUNCTION(rdtscp) {

    uint64_t tick;
    uint64_t cpun;
    
    c_local_rdtscp(&tick, &cpun);

    array_init    (return_value); 
    add_index_long(return_value, 0, tick); 
    add_index_long(return_value, 1, cpun);
}

PHP_FUNCTION(rdtscp_assoc) {

    uint64_t tick;
    uint64_t cpun;
    
    c_local_rdtscp(&tick, &cpun);

    array_init    (return_value); 
    add_assoc_long(return_value, "tick" , tick); 
    add_assoc_long(return_value, "coren", cpun);
}

long int c_local_nanopk(long int *U, long int *nso, long int *Uns) {
    struct timespec sts;
    int sret = clock_gettime(CLOCK_REALTIME, &sts);
    if (sret != 0) return -1;
    *U    = sts.tv_sec;
    *nso  = sts.tv_nsec;
    *Uns  = sts.tv_sec * 1000000000 + sts.tv_nsec;
}

long int c_local_nanotime() {
    struct timespec sts;
    int ret = clock_gettime(CLOCK_REALTIME, &sts);
    if (ret != 0) return 0;
    long int epochns = sts.tv_sec * 1000000000 + sts.tv_nsec;
    return epochns;
}

PHP_FUNCTION(nanotime) {  RETURN_LONG(c_local_nanotime()); }

long int c_local_uptime() {
    struct sysinfo ssi;

    int sret = sysinfo(&ssi);
    if (sret != 0) return -1;

    return ssi.uptime;    
}

PHP_FUNCTION(uptime) {
    
    long int uptime = c_local_uptime();

    struct timespec sts;
    int sret = clock_gettime(CLOCK_REALTIME, &sts);
    long int Uboot = -1;
    long int U     = -1;

    if (sret == 0) U = sts.tv_sec;
    if (U > -1 && uptime > -1) Uboot = U - uptime;

    array_init    (return_value);
    add_assoc_long(return_value, "uptime", uptime);
    add_assoc_long(return_value, "Uboot", Uboot);   
}

PHP_FUNCTION(nanopk) {  
    
    uint64_t tick = -1;
    uint64_t cpun = -1;
    long int U    = -1;
    long int Unsonly = -1;
    long int Uns = -1;

    c_local_rdtscp(&tick, &cpun);
    c_local_nanopk(&U, &Unsonly, &Uns);

    array_init    (return_value);
    add_assoc_long(return_value, "tick" , tick); 
    add_assoc_long(return_value, "coren", cpun);
    add_assoc_long(return_value, "U"    , U);
    add_assoc_long(return_value, "Uns", Uns);
    add_assoc_long(return_value, "Unsonly", Unsonly);
    add_assoc_string(return_value, "nanopk_v", PHP_NANOPK_VERSION);
}
