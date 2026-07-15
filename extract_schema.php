<?php
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$result = $db->query("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
foreach ($result as $row) {
    echo "TABLE: " . $row['name'] . "\n";
    echo $row['sql'] . "\n\n";
}
