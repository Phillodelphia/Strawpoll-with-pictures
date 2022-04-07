<?php
session_start();

$target_dir = "uploads/";

$fi = new FilesystemIterator(__DIR__ . '/polls', FilesystemIterator::SKIP_DOTS);

$title = $_POST['title'];
trim($title);
$content = array("id"=>iterator_count($fi)+1, "title"=>htmlspecialchars($title));
$total = count($_FILES['pollOp']['name']);

for( $i=0 ; $i < $total ; $i++ ) {

  $target_file = $target_dir . basename($_FILES["pollOp"]["name"][$i]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {

    $check = getimagesize($_FILES["pollOp"]["tmp_name"][$i]);
    if($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
    } else {
      echo "File is not an image.";
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    } else {
      if (move_uploaded_file($_FILES["pollOp"]["tmp_name"][$i], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["pollOp"]["name"][$i])). " has been uploaded.";
        $content['content'][$i] = array("name"=>$target_file, "votes"=>0);
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
  }
}



//set cookie
$content['userId'] = $_COOKIE['PHPSESSID'];

//write result to json file
$fp = fopen('polls/' . $content['id'] . '.json', 'w');
fwrite($fp, json_encode($content));
fclose($fp);

echo "<script>document.location = 'r.html?id=" . $content['id'] . "';</script>";

?>
