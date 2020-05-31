<?php
require 'db.php';

if( $_POST["bookname"] || $_POST["bookdesc"] ) {
   if (preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/',$_POST['name'] )) {
      die ("invalid name and name should be alpha");
   }

   // Create connection
   $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
   // Check connection
   if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
   }

   $bookname = $_POST["bookname"];
   $bookdesc = $_POST["bookdesc"];
   $booklink = $_POST["booklink"];

   $sql = $conn->prepare("INSERT INTO book_tbl (book_name, book_information, book_link, creation_date) VALUES (?, ?, ?, now())");
   $sql->bind_param('sss',$bookname, $bookdesc, $booklink);

   if ($sql->execute() === FALSE) {
     echo "Error creating table: " . $sql->error;
   }

   $sql->close();

   $result = "Book ".$bookname." Added<br>";
}
?>

<!doctype html>
<html>
  <head>
      <meta charset="utf-8">
      <meta name="description" content="">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Book Information</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800,900" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/railscasts.min.css">
      <link rel="stylesheet" href="scribbler-global.css">
      <link rel="stylesheet" href="scribbler-landing.css">
      <link rel="author" href="humans.txt">
  </head>
  <body>
    <script>
       setTimeout(function(){
          window.location.href = 'index.php';
       }, 3000);
    </script>
    <nav>
      <div class="logo"></div>
      <ul class="menu">
        <div class="menu__item toggle"><span></span></div>
        <li class="menu__item"><a href="" class="link link--dark"><i class="fa fa-github"></i> Login</a></li>
      </ul>
    </nav>
    <div class="hero">
      <h1 class="hero__title"><?php echo $result ?></h1><br>
      <h2 class="hero__title">Redirecting you back to the main page</h2>
    </div>
    </div>
  </body>
</html>
