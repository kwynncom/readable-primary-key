#include <inttypes.h>
#include <stdint.h>
#include <stdio.h>
#include <stdlib.h>
#include <x86intrin.h>

struct tick_n {
    uint64_t tick;
    uint32_t cpun;
};

struct tick_n loc_rdtscp() {
    uint64_t tick;
    uint32_t cpun;
    struct tick_n ret;

    tick = (uint64_t)__rdtscp(&cpun);
    
    ret.tick = tick;
    ret.cpun = cpun;

    return ret;
} 

int main(void) {
    struct tick_n v = loc_rdtscp();
    printf("0x%016" PRIX64 " 0x%08" PRIX32 "\n", v.tick, v.cpun);
    return EXIT_SUCCESS;
} // gcc -ggdb3 -O0 -std=c99 -Wall -Wextra -pedantic -o struct.out rdtscp_struct.c