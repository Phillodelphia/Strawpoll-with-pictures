<?php
if (isset($_POST["voteB"])) {
    $content = json_decode(file_get_contents($_POST['id']), true);
    $content['content'][$_POST['voteB']]['votes'] += 1;

    $fp = fopen($_POST['id'], 'w');
    fwrite($fp, json_encode($content));
    fclose($fp);

    header("Location: r.html?id=" . $content['id']);
}
?>