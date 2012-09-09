<?php
// PHP is stupid
function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

ini_set('display_errors', 'On');
error_reporting(E_ALL);

function html_response() {
    header('Content-type: text/html; charset=UTF-8');
    ?>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-20805386-1']);
      _gaq.push(['_setDomainName', 'none']);
      _gaq.push(['_setAllowLinker', true]);
      _gaq.push(['_trackPageview']);
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
</head>
<body lang=en>
    <!-- User Agent: "<?= $_SERVER['HTTP_USER_AGENT'] ?>" -->
    <code id="address"><?= $_SERVER['REMOTE_ADDR'] ?></code>
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
