// include the PHP API itself
#include <php.h>
// then include the header of your extension
#include "php_rdtscp.h"
// *****
#include <inttypes.h>
#include <stdint.h>
#include <stdio.h>
#include <stdlib.h>
#include <x86intrin.h>

// *****
// register our function to the PHP API 
// so that PHP knows, which functions are in this module
zend_function_entry rdtscp_php_functions[] = {
    PHP_FE(rdtscp_php, NULL)
    {NULL, NULL, NULL}
};

// some pieces of information about our module
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

// use a macro to output additional C code, to make ext dynamically loadable
ZEND_GET_MODULE(rdtscp_php)

// Finally, we implement our "Hello World" function
// this function will be made available to PHP
// and prints to PHP stdout using printf

ZEND_BEGIN_ARG_INFO_EX(rdtscp_php, 0, 0, 0)
ZEND_END_ARG_INFO()

void loc_rdtscp(uint64_t *tick,  uint64_t *cpuna) {
    uint32_t cpun;
    *tick  = (uint64_t)__rdtscp(&cpun);
    *cpuna = cpun;
} 

PHP_FUNCTION(rdtscp_php) {

    uint64_t tick;
    uint64_t cpun;
    
    loc_rdtscp(&tick, &cpun);

    array_init    (return_value); 
    add_index_long(return_value, 0, tick); 
    add_index_long(return_value, 1, cpun);
}


