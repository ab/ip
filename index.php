<?php
// PHP is stupid
function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

ini_set('display_errors', 'On');
error_reporting(E_ALL);

function html_response($remote_addr, $remote_hostname) {
    header('Content-type: text/html; charset=UTF-8');
    $whois_url = "http://whois.arin.net/rest/ip/$remote_addr.txt";
    $whois_url_html = "http://whois.arin.net/rest/ip/$remote_addr.html";
    $whois = file_get_contents($whois_url);
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
    <code id="address"><?= $remote_addr ?></code><br />
    <code><?= $remote_hostname ?></code><br /></br>

    <pre><a href="<?= htmlspecialchars($whois_url_html) ?>"><?= htmlspecialchars($whois_url_html) ?></a></pre>
    <pre style="margin-left: 1em;"><?= htmlspecialchars($whois) ?></pre>

</body>
</html>
<?php
}

function plain_response($remote_addr, $remote_hostname) {
    header('Content-type: text/plain; charset=UTF-8');

    echo $remote_addr . "\n";
}


$agent = $_SERVER['HTTP_USER_AGENT'];

$html = True;
if (startswith($agent, 'curl/')) {
    $html = False;
} else if (startswith($agent, 'Wget/')) {
    $html = False;
} else if (startswith($agent, 'python-requests/')) {
    $html = False;
} else if (startswith($agent, 'PycURL/')) {
    $html = False;
} else if ($agent == 'Ruby') {
    $html = False;
}

$remote_addr = $_SERVER['REMOTE_ADDR'];
$remote_hostname = gethostbyaddr($remote_addr);

// PHP doesn't seem to understand how return codes work
if ($remote_hostname == $remote_addr) {
    $remote_hostname = null;
}

if ($html) {
    html_response($remote_addr, $remote_hostname);
} else {
    plain_response($remote_addr, $remote_hostname);
}

?>
