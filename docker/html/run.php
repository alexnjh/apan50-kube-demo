<?php

// Use ls command to shell_exec
// function
$output = shell_exec('stress-ng --cpu 4 --timeout 60s --metrics-brief > /dev/null 2>/dev/null &');


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
    <div class="hero">
      <h1 class="hero__title">Benchmark request completed</h1><br>
      <h2 class="hero__title">Redirecting you back to the main page</h2>
    </div>
    </div>
  </body>
</html>
