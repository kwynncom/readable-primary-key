<?php

$goton = 1000;

$b = hrtime(1);
for ($i=0; $i < $goton; $i++) $a[] = new MongoDB\BSON\ObjectId();
$e = hrtime(1);

echo number_format(($e - $b) / $goton);
