# Solution

Lợi dụng [trick](https://book.hacktricks.xyz/network-services-pentesting/pentesting-web/php-tricks-esp#strcmp-strcasecmp) với `strcmp/strcasecmp`.


POST lên với `flag[]=` => `strcasecmp` return NULL -> `!strcasecmp` return true => FLAG


**WEBSEC{Try_it_yourself_dont_copy_and_paste}**