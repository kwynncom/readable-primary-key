#define PHP_RDTSCP_EXTNAME "php_rdtscp"
#define PHP_RDTSCP_VERSION "0.0.1"
PHP_FUNCTION(rdtscp);

/****************
I started this extension based on 
https://stackoverflow.com/questions/3632160/how-to-make-a-php-extension

Specifically, this answer:

answered Sep 14 '15 at 23:35
edited Jun 1 '18 at 10:07
Jens A. Koch
https://stackoverflow.com/users/1163786/jens-a-koch

Question: How to make a PHP extension
Asked 10 years, 1 month ago Active 3 months ago [as of 2020/09/30] 
***************
********************************
These were also helpful:

https://docstore.mik.ua/orelly/webprog/php/ch14_08.htm
http://www.phpinternalsbook.com/php7/extensions_design/php_functions.html

*/