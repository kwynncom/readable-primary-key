?php

$goto = 200;

for ($i=0; $i < $goto; $i++) $r[] = rdtscp();
for ($i=1; $i < $goto; $i++) echo(number_format($r[$i][0] - $r[$i-1][0]) . "\n");
