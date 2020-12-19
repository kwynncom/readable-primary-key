#include <php.h>
#include "php_nanopk.h"
#include <time.h>
#include <inttypes.h>
#include <stdint.h>
#include <x86intrin.h>
#include <sys/sysinfo.h>
#include <stdbool.h>

zend_function_entry nanopk_functions[] = {
    PHP_FE(nanopk, NULL)
    PHP_FE(nanotime, NULL)
    PHP_FE(rdtscp, NULL)
    PHP_FE(uptime, NULL)
    PHP_FE_END
};

PHP_MINIT_FUNCTION(nanopk)
{
    REGISTER_MAIN_LONG_CONSTANT("NANOPK_UNS"    , NANOPK_UNS    , CONST_CS|CONST_PERSISTENT);
    REGISTER_MAIN_LONG_CONSTANT("NANOPK_U"      , NANOPK_U      , CONST_CS|CONST_PERSISTENT);
    REGISTER_MAIN_LONG_CONSTANT("NANOPK_UNSOI"  , NANOPK_UNSOI  , CONST_CS|CONST_PERSISTENT);
    REGISTER_MAIN_LONG_CONSTANT("NANOPK_UNSOF"  , NANOPK_UNSOF  , CONST_CS|CONST_PERSISTENT);
    REGISTER_MAIN_LONG_CONSTANT("NANOPK_TSC"    , NANOPK_TSC    , CONST_CS|CONST_PERSISTENT); 
    REGISTER_MAIN_LONG_CONSTANT("NANOPK_PID"    , NANOPK_PID    , CONST_CS|CONST_PERSISTENT); 
    REGISTER_MAIN_LONG_CONSTANT("NANOPK_VERSION", NANOPK_VERSION, CONST_CS|CONST_PERSISTENT); 

    return SUCCESS;
}

zend_module_entry nanopk_module_entry = {
    STANDARD_MODULE_HEADER,
    PHP_NANOPK_EXTNAME,
    nanopk_functions,
    PHP_MINIT(nanopk),
    NULL,
    NULL,
    NULL,
    NULL,
    PHP_NANOPK_VERSION,
    STANDARD_MODULE_PROPERTIES
};

ZEND_GET_MODULE(nanopk)

void c_rdtscp(uint64_t *tick,  uint64_t *pida) {
    uint32_t pid;
    *tick  = (uint64_t)__rdtscp(&pid);
    *pida = pid;
} 

PHP_FUNCTION(rdtscp) {

    uint64_t tick;
    uint64_t pid;
    
    c_rdtscp(&tick, &pid);

    array_init    (return_value); 
    add_assoc_long(return_value, "tick" , tick); 
    add_assoc_long(return_value, "coren", pid);
}

long int c_uptime() {
    struct sysinfo ssi;
    int sret = sysinfo(&ssi);
    if (sret != 0) return -1;
    return ssi.uptime;
}

PHP_FUNCTION(uptime) {
    
    long int uptime = c_uptime();

    struct timespec sts;
    int sret = clock_gettime(CLOCK_REALTIME, &sts);
    long int Uboot = -1;
    long int U     = -1;
    long int Ubmin = -1;
    long int Ubmax = -1; 

    if (sret == 0) U = sts.tv_sec;
    if (U > -1 && uptime > -1) Uboot = U - uptime;

    array_init    (return_value);
    add_assoc_long(return_value, "uptime", uptime);
    add_assoc_long(return_value, "Ubest" , Uboot);
    add_assoc_long(return_value, "Ubmin" , Uboot - 3);
    add_assoc_long(return_value, "Ubmax" , Uboot + 3);
}

struct timespec c_get_timespec() {
    struct timespec sts;
    clock_gettime(CLOCK_REALTIME, &sts); // not sure if need to do anything with int ret / return value
    return sts;
}

long int c_nanotime_only() {
    struct timespec sts = c_get_timespec();
    long int Uns = sts.tv_sec * 1000000000 + sts.tv_nsec;
    return Uns;
}

PHP_FUNCTION(nanotime) { 
    long int ns = c_nanotime_only();
    RETURN_LONG(ns); 
}

PHP_FUNCTION(nanopk) {  
    
    const long defaultArgs = NANOPK_UNS | NANOPK_TSC | NANOPK_PID;
    long arg = defaultArgs;
    if (ZEND_NUM_ARGS() > 0 && zend_parse_parameters(ZEND_NUM_ARGS(), "l", &arg) == FAILURE) return;
    if (arg == 0) arg = defaultArgs;

    uint64_t tick = -1;
    uint64_t pid  = -1;

    array_init    (return_value);    
    if (arg & (NANOPK_TSC  | NANOPK_PID)) c_rdtscp(&tick, &pid);
    if (arg &  NANOPK_TSC)   add_assoc_long(return_value, "tsc", tick); 
    if (arg &  NANOPK_PID)   add_assoc_long(return_value, "pid", pid);
    
    long int uns = -1;
    struct timespec sts;
    bool extraU = (arg & (NANOPK_U & NANOPK_UNSOI & NANOPK_UNSOF)) == 0;

    const long billion = 1000000000;

    if (!extraU && ((arg & NANOPK_UNS)     )) uns = c_nanotime_only();
    if ( extraU) {
        sts = c_get_timespec ();
        if (uns == -1) uns = sts.tv_sec * billion + sts.tv_nsec;
    }

    if (arg & NANOPK_UNS) add_assoc_long(return_value, "Uns", uns);
    
    long int U     = -1;
    long int unsoi = -1;
    double   unsof = -1;

//                      123456789


    if (arg & NANOPK_U)       add_assoc_long  (return_value, "U"    , sts.tv_sec);
    if (arg & NANOPK_UNSOI)   add_assoc_long  (return_value, "Unsoi", sts.tv_nsec);
    if (arg & NANOPK_UNSOF)   add_assoc_double(return_value, "Unsof", (double) sts.tv_nsec / billion);
    if (arg & NANOPK_VERSION) add_assoc_string(return_value, "nanopk_v", PHP_NANOPK_VERSION);

/*
    array_init    (return_value);
    add_assoc_long(return_value, "tick" , tick); 
    add_assoc_long(return_value, "coren", pid);
    add_assoc_long(return_value, "U"    , U);
    add_assoc_long(return_value, "Uns", Uns);
    add_assoc_long(return_value, "Unsonly", Unsonly);
    add_assoc_string(return_value, "nanopk_v", PHP_NANOPK_VERSION); */
}
