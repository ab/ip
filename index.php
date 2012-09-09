<?php
// PHP is stupid
function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

function html_response() {
    header('Content-type: text/html; charset=UTF-8');
    ?>
<!doctype html>
<html>
<body lang=en>
<!-- User Agent: "<?= $_SERVER['HTTP_USER_AGENT'] ?>" -->
<input type=text name=ip value="<?= $_SERVER['REMOTE_ADDR'] ?>" readonly=1 />
</body>
</html>
<?php
}

function plain_response() {
    header('Content-type: text/plain; charset=UTF-8');

    echo $_SERVER['REMOTE_ADDR'] . "\n";

}


$agent = $_SERVER['HTTP_USER_AGENT'];

$html = True;
if (startswith($agent, 'curl/')) {
    $html = False;
} else if (startswith($agent, 'Wget/')) {
    $html = False;
}

if ($html) {
    html_response();
} else {
    plain_response();
}

?>
