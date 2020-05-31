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

    <?php
       require 'db.php';
       // Create connection
       $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
       // Check connection
       if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
       }

       $sql = "CREATE TABLE IF NOT EXISTS book_tbl(
          book_id INT NOT NULL AUTO_INCREMENT,
          book_name VARCHAR(100) NOT NULL,
          book_information VARCHAR(100) NOT NULL,
          book_link VARCHAR(100) NOT NULL,
          creation_date DATE,
          PRIMARY KEY (book_id));";

      if ($conn->query($sql) === FALSE) {
        echo "Error creating table: " . $conn->error;
      }
    ?>

    <nav>
      <div class="logo"></div>
      <ul class="menu">
        <div class="menu__item toggle"><span></span></div>
        <li class="menu__item"><a href="benchmark.php" class="link link--dark"><i class="fa fa-line-chart"></i> Benchmark</a></li>
      </ul>
    </nav>
    <div class="hero">
      <h1 class="hero__title">Have a book to share?</h1>
      <p class="hero__description">Sharing is caring</p>
    </div>
    <div class="hero__terminal">
      <pre>
        <center>
        <form action="submit.php" method="post">
          <label for="bookname">Book name:</label><br>
          <input type="text" id="bookname" name="bookname" style="width:303px"><br>
          <label for="booklink">Book link:</label><br>
          <input type="text" id="booklink" name="booklink" style="width:303px"><br>
          <label for="bookdesc">Book description:</label><br>
          <textarea id="bookdesc" name="bookdesc" rows="4" cols="40" style="resize:none"></textarea><br>
          <input type="submit" value="Submit">
        </form>
      </center>
      </pre>
    </div>
    <div class="wrapper">
      <table id="customers">
        <tr>
          <th>Book name</th>
          <th>Description</th>
          <th>Book link</th>
        </tr>
        <?php
        $sql = "SELECT book_name, book_information, book_link FROM book_tbl";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
            echo "<tr><td>".htmlspecialchars($row['book_name'])."</td>";
            echo "<td>".htmlspecialchars($row['book_information'])."</td>";
            echo "<td><a class='button--primary' href='".htmlspecialchars($row['book_link'])."'>Link</a></td></tr>";
          }
        }
        ?>
      </table>
      </div>
    </div>
    <footer class="footer">Credits to <a href="https://tympanus.net/codrops/2018/01/12/freebie-scribbler-website-template-html-sketch/" target="_blank" class="link link--light">Scribber</a> for the template</footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <script src="scribbler.js"></script>
  </body>
</html>
