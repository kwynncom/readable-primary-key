#include <inttypes.h>
#include <stdint.h>
#include <stdio.h>
#include <stdlib.h>

#include <x86intrin.h>

int main(void) {
    uint32_t pid1;
    uint32_t pid2;
    uint64_t r1 = (uint64_t)__rdtscp(&pid1);
    uint64_t r2 = (uint64_t)__rdtscp(&pid2);

    printf("0x%016" PRIX64 "\n", r1);
    printf("0x%016" PRIX64 "\n", r2);
    printf("0x%08"  PRIX32 "\n", pid1);
    printf("0x%08"  PRIX32 "\n", pid2);


    printf("%lu" PRIX64 "\n",r1);
    printf("%lu" PRIX64 "\n",r2);

    return EXIT_SUCCESS;
} // gcc -ggdb3 -O0 -std=c99 -Wall -Wextra -pedantic -o cck2.out rdtscp_do2.c
