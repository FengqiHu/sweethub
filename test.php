<?php
echo "Current PHP user: " . exec('whoami');
echo "\nPHP process user: " . get_current_user();
phpinfo();
?>