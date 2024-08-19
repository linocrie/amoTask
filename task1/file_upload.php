<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $allowedfileExtensions = array('txt');

    if (in_array($fileExtension, $allowedfileExtensions)) {
      $uploadDir = __DIR__ . '/files/';
      $uploadFile = $uploadDir . basename($fileName);

      if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0777, true);
      }
      if (move_uploaded_file($fileTmpPath, $uploadFile)) {
        $fileContent = file_get_contents($uploadFile);
        $lines = explode('|', $fileContent);

        $_SESSION['content'] = $lines;

        header("Location: index.php?status=success");
        exit;
      } 
      else {
        header("Location: index.php?status=error");
        exit;
      }
    } 
    else {
      header("Location: index.php?status=invalid_file");
      exit;
    }
  } 
  else {
    header("Location: index.php?status=error");
    exit;
  }
}
