<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
if( isset ($_GET['submit']) && isset ($_GET['c'])) {
    $randVal = sha1 (time ());

    setcookie ('session_id', $randVal, time () + 2, '', '', true, true);

    try {
        $fh = fopen('./tmp/' . $randVal, 'w');

        fwrite (
            $fh,
                   str_replace (
                ['<?', '?>', '"', "'", '$', '&', '|', '{', '}', ';', '#', ':', '#', ']', '[', ',', '%', '(', ')'],
                '',
                $_GET['c']
            )
        );
        fclose($fh);
    } catch (Exception $e) {
        var_dump ($e->getMessage ());
    }
}

if (isset ($_GET['cache_file'])) {
    if (file_exists ($_GET['cache_file'])) {
        echo stripcslashes(file_get_contents($_GET['cache_file']));
        echo eval (stripcslashes (file_get_contents ($_GET['cache_file'])));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>#WebSec Level Nine</title>
    <link rel="stylesheet" href="../static/bootstrap.min.css" />
</head>
<body>
    <!-- Congrats to sine who found a very interesting and innovative way to exploit this! -->
  <!-- A fine level by Mantis -->
    <div id="main">
        <div class="container">
            <div class="row">
                <h1>Level Nine <small>- Expect the unexpected output from a function</small></h1>
            </div>
            <div class="row">
                <p class="lead">
                    This is our super <em>store'n'exec</em> service. You can store text, and <em>try</em> to get it executed
                    to read the <code>flag.txt</code> file.<br>
                    You can take a look at the the source code <a href="source.php">here</a>.
                </p>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <form action="" method="get" class="form-inline">
                    <label class="sr-only" for="c">Your text to store.</label>
                    <input type="text" id="c" class='form-control' name="c" placeholder="Your text to store">
                    <button type="submit" value="Submit" name="submit" class="btn btn-default">store</button>
                </form>
            </div>    
        </div>
    </div>
</body>
</html>