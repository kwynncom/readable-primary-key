#include <inttypes.h>
#include <stdint.h>
#include <stdio.h>
#include <stdlib.h>
#include <x86intrin.h>

uint64_t[] loc_rdtscp() {
    uint64_t tick;
    uint32_t cpun;
    uint64_t ret[2];

    tick = (uint64_t)__rdtscp(&cpun);
    
    ret[0] = tick;
    ret[1] = (uint32_t)cpun;

    return ret;
} 

int main(void) {
    uint64_t[] v = loc_rdtscp();
    printf("0x%016" PRIX64 " 0x%08" PRIX32 "\n", v.tick, v.cpun);
    return EXIT_SUCCESS;
} // gcc -ggdb3 -O0 -std=c99 -Wall -Wextra -pedantic -o arr.out rdtscp_arr.c