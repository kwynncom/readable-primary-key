#include <inttypes.h>
#include <stdint.h>
// #include <stdio.h>
// #include <stdlib.h>
#include <x86intrin.h>

void loc_rdtscp(uint64_t *ret  ,  uint64_t *pid ) {

    *ret = (uint64_t)__rdtscp((uint32_t *)pid);
} 

int main(void) {
    uint64_t pid;
    uint64_t ret;
    loc_rdtscp( &ret , &pid);
    // printf("0x%016" PRIX64 " 0x%08" PRIX32 "\n", ret, pid);
    return EXIT_SUCCESS;
} // gcc -ggdb3 -O0 -std=c99 -Wall -Wextra -pedantic -o rargs1.out rdtscp_args.c