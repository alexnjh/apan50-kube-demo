<?php

// Use ls command to shell_exec
// function
$output = shell_exec('stress-ng --cpu 4 --io 2 --vm 1 --vm-bytes 512M --timeout 60s --metrics-brief');

// Display the list of all file
// and directory
echo "<pre>$output</pre>";
?> 
