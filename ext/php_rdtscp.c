#include <php.h>  // for PHP's sake
#include "php_rdtscp.h"

// *****
#include <inttypes.h> // for C/C++, not PHP
#include <stdint.h>
#include <x86intrin.h>

// *****
zend_function_entry rdtscp_php_functions[] = {
    PHP_FE(rdtscp, NULL)
    {NULL, NULL, NULL}
};

zend_module_entry rdtscp_php_module_entry = {
    STANDARD_MODULE_HEADER,
    PHP_RDTSCP_EXTNAME,
    rdtscp_php_functions,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    PHP_RDTSCP_VERSION,
    STANDARD_MODULE_PROPERTIES
};

ZEND_GET_MODULE(rdtscp_php)

ZEND_BEGIN_ARG_INFO_EX(rdtscp, 0, 0, 0)
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
