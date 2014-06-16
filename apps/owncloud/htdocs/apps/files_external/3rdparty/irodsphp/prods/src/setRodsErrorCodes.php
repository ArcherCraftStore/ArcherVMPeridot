<?php
$RODS_tree_root = dirname(__FILE__) . "/../../..";

$capi_error_table_file = $RODS_tree_root . "/lib/core/include/rodsErrorTable.h";
$prods_error_table_file = $RODS_tree_root . "/clients/prods/src/RodsErrorTable.inc.php";

// Add more error code here, if you wish. It will be added to the default
// RODS error code. Note that these errors code are for web server/client
// only. RODS server does not recongnize them.
$new_error_codes = array(
    array("GENERAL_PRODS_ERR", -3000000),
    array("PERR_INTERNAL_ERR", -3100000),
    array("PERR_UNEXPECTED_PACKET_FORMAT", -3101000),
    array("PERR_PATH_DOES_NOT_EXISTS", -3102000),
    array("PERR_UNSUPPORTED_PROTOCOL_SCHEME", -3103000),
    array("PERR_USER_INPUT_ERROR", -3104000),
    array("PERR_USER_INPUT_PATH_ERROR", -3105000),
    array("PERR_CONN_NOT_ACTIVE", -3106000)
);

$value_pairs = array();

$lines = explode("\n", file_get_contents($capi_error_table_file));

foreach ($lines as $line) {
    if (strlen($line) < 8) continue;
    if (substr($line, 0, 7) == '#define') {
        $rest = trim(substr($line, 7));
        $tokens = preg_split("/\s+/", $rest);
        if (count($tokens) < 2)
            continue;
        $val1 = NULL;
        $val2 = NULL;
        foreach ($tokens as $token) {
            if (strlen($token) > 3) {
                if (empty($val1)) $val1 = trim($token);
                else $val2 = trim($token);
            }
        }
        if ((!empty($val1)) && (!empty($val2))) {
            array_push($value_pairs, array($val1, $val2));
        }
    }
}

foreach ($new_error_codes as $new_code_pair) {
    if ((!is_array($new_code_pair)) || (count($new_code_pair) != 2))
        die("unexpected new_code_pair:$new_code_pair\n");
    array_push($value_pairs, $new_code_pair);
}

$outputstr = "<?php \n" .
    "/* This file is generated by setRodsErrorCodes.php. " .
    "   Please modify that file if you wish to update the " .
    "   error codes.            */\n";
$outputstr = $outputstr . '$GLOBALS[\'PRODS_ERR_CODES\']=array(' . "\n";
foreach ($value_pairs as $value_pair) {
    $val1 = $value_pair[0];
    $val2 = $value_pair[1];
    $outputstr = $outputstr . "  '$val1' => '$val2',\n";
}
$outputstr = $outputstr . ");\n";

$outputstr = $outputstr . '$GLOBALS[\'PRODS_ERR_CODES_REV\']=array(' . "\n";
foreach ($value_pairs as $value_pair) {
    $val1 = $value_pair[0];
    $val2 = $value_pair[1];
    $outputstr = $outputstr . "  '$val2' => '$val1',\n";
}
$outputstr = $outputstr . ");\n";

$outputstr = $outputstr . "?>\n";
file_put_contents($prods_error_table_file, $outputstr);
