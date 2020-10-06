#include <time.h>
#include <stdio.h>

long int loc_clock_gettime() {
    struct timespec sts;
    int ret = clock_gettime(CLOCK_REALTIME, &sts);
    if (ret != 0) return 0;
    long int epochns = sts.tv_sec * 1000000000 + sts.tv_nsec;
    return epochns;
}

int main(void) {
    printf("%ld\n", loc_clock_gettime());
}