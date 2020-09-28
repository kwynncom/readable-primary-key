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

ZEND_BEGIN_ARG_INFO_EX(rdtscp_php, 0, 0, 2)
    ZEND_ARG_INFO(1, ticka )
    ZEND_ARG_INFO(1, cpuna )
ZEND_END_ARG_INFO()

void loc_c_rdtscp(uint64_t *ret  ,  uint64_t *pid ) {
    *ret = (uint64_t)__rdtscp((uint32_t *)pid);
    printf("C 12:58am\n");
}

PHP_FUNCTION(rdtscp_php) {
    
    zval *p1;
    zval *p2;

    if (zend_parse_parameters(ZEND_NUM_ARGS(), "zz", &p1, &p2) == FAILURE) {
        return;
    }

    ZVAL_DEREF(p1);
    ZVAL_DEREF(p2);
    convert_to_long(p1);
    convert_to_long(p2);
    
    ZVAL_DOUBLE(param, php_fahrenheit_to_celsius(Z_DVAL_P(param)));

    loc_c_rdtscp(Z_LVAL_P(p1), Z_LVAL_P(p2));
}


