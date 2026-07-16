<?php
$res = @file_get_contents('https://clementine.my.id/_debug/logs');
if ($res === false) {
    echo "Failed to fetch logs. Headers:\n";
    print_r($http_response_header);
} else {
    echo "Success! Output:\n";
    echo $res;
}
