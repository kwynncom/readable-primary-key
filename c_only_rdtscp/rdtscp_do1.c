#include <inttypes.h>
#include <stdint.h>
#include <stdio.h>
#include <stdlib.h>
#include <x86intrin.h>

int main(void) {
    uint32_t pid1;
    uint64_t r1 = (uint64_t)__rdtscp(&pid1);

    printf("0x%016" PRIX64 " 0x%08" PRIX32 "\n", r1, pid1);
    return EXIT_SUCCESS;
} // gcc -ggdb3 -O0 -std=c99 -Wall -Wextra -pedantic -o cck1.out rdtscp_do1.c
