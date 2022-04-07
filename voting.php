<?php
session_start();
if (isset($_POST["voteB"])) {
    
    // add session cookie
    if (isset($_POST['username'])) {
        setcookie("User", $_POST['username']);
    }

    // add 1 additional vote
    $content = json_decode(file_get_contents($_POST['id']), true);
    
    $userExist = 0;
    $fp = fopen("voted/". $content['id'] . ".txt", 'a+');
    $filename = "voted/". $content['id'] . ".txt";
    $array = explode(" ", file_get_contents($filename));
    //$arr = explode(" ", $lines);
    foreach ($array as $item) {
        if ($item == $_COOKIE['User']) {
            $userExist = 1;
        }
    }

    if ($userExist == 0) {
    // add user to voted list
    fwrite($fp, $_COOKIE['User'] . " ");
    fclose($fp);

    $content['content'][$_POST['voteB']]['votes'] += 1;

    // write to database
    $fp = fopen($_POST['id'], 'w');
    fwrite($fp, json_encode($content));
    fclose($fp);

    echo "<script>document.location = 'r.html?id=" . $content['id'] . "';</script>";
    }
    else {
        echo "You have already voted";
    }
    
}
?>