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

    $whois_url = "https://whois.arin.net/rest/ip/$remote_addr.txt";
    $whois_url_html = "https://whois.arin.net/rest/ip/$remote_addr.html";
    // $whois_data = file_get_contents($whois_url);
    ?>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-39LLW7QMT4"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-39LLW7QMT4');
    </script>
    <script language="javascript" type="text/javascript">
      function resizeIframe(obj) {
        var height = document.body.scrollHeight - document.getElementById('upper').scrollHeight;
        obj.style.height = height + 'px';
      }
    </script>
</head>
<body lang=en>
  <div id="upper">
    <!-- User Agent: "<?= htmlspecialchars($_SERVER['HTTP_USER_AGENT']) ?>" -->

    <code id="address"><?= htmlspecialchars($remote_addr) ?></code><br />
<?php if ($remote_hostname) { ?>
    <code><?= htmlspecialchars($remote_hostname) ?></code><br />
<?php } ?>

<?php if (!empty($_GET["headers"])) { ?>
    <pre>Headers:
<?= htmlspecialchars(print_r(getallheaders(), True)); ?></pre>
<?php } ?>
    <pre><a href="<?= htmlspecialchars($whois_url_html) ?>"><?= htmlspecialchars($whois_url_html) ?></a></pre>
    <?php /* <pre style="margin&#45;left: 1em;"><?= htmlspecialchars($whois_data) ?></pre> */ ?>
  </div>
  <div id="lower">
    <iframe sandbox id="whois_iframe" src="<?= htmlspecialchars($whois_url) ?>" frameborder="0" scrolling="auto" style="width:100%; height: 30em" onload="resizeIframe(this)"></iframe>
  </div>
</body>
</html>
<?php
}

function plain_response($remote_addr, $remote_hostname) {
    header('Content-type: text/plain; charset=UTF-8');

    echo $remote_addr . "\n";
}

if (empty($_SERVER['HTTP_USER_AGENT'])) {
    $html = False;
    $agent = '';
} else {
    $agent = $_SERVER['HTTP_USER_AGENT'];

    $html = False;
    if (startswith($agent, 'Mozilla/')) {
        $html = True;
    } else if (startswith($agent, 'mozilla/')) {
        $html = True;
    }
}

if (isset($_GET["html"])) {
    if ($_GET["html"]) {
        $html = True;
    } else {
        $html = False;
    }
}

$remote_addr = $_SERVER['REMOTE_ADDR'];

// validate that IP address is numeric only
if (!preg_match('/\A[0-9a-f.:]+\z/', $remote_addr)) {
    $remote_addr = '0.0.0.0';
}

/*
 * PHP's built-in gethostbyaddr() is so dumb I'm too mad to explain.
 * Significantly, it has no way to specify a timeout.
 * Use dig(1) instead.
 */
$remote_hostname = shell_exec('dig +short -x ' . escapeshellarg($remote_addr) . ' 2>&1');

if ($html) {
    html_response($remote_addr, $remote_hostname);
} else {
    plain_response($remote_addr, $remote_hostname);
}

?>
