<?

include 'SessionMagicData.php';

$sessiondb = SessionMagicData::create();

// how many can read value from variable text. Max 10
$sessiondb->set("variable", "my value", 0, 10);

for ($x = 0; $x < 12; $x++) {
    echo $x, ": ", $sessiondb->get("variable"), "\n";
}

// how long can read value from variable text. Max 2s
$sessiondb->set("variable", "my value", 2);

for ($x = 0; $x < 5; $x++) {
    echo $x, ": ", $sessiondb->get("variable"), "\n";
    sleep(1);
}
