<?php
echo "PHP Test - " . date('Y-m-d H:i:s') . "\n";
echo "File: " . __FILE__ . "\n";
echo "Directory: " . __DIR__ . "\n";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
phpinfo(INFO_GENERAL);
