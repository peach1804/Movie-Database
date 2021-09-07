<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Movie Rental</title>
    <link rel="stylesheet" href="movies.css">
</head>

<body>
    <div id="top">
        <h1>Movie Rental</h1>
    </div>
    
    <div>
        <ul>
            <li><a href="search.php">Search Movie</a></li>
            <li><a href="top_movies.php">Top 10</a></li>
        </ul>
    </div>

    <div>
    <?php

    class TableRows extends RecursiveIteratorIterator
    {
        public function construct($it)
        {
            parent::construct($it, self::LEAVES_ONLY);
        }
        
        public function current()
        {
            return "<td style = 'width:200px; border:1px solid #4CAF50;'>" . 
            parent::current() . "</td>";
        }
        
        public function beginChildren()
        {
            echo "<tr>";
        }
        
        public function endChildren()
        {
            echo "</tr>" . "\n";
        }
    }

    require("connect.php");
    $where="";
    $searching="Searching";

    if (!empty($_POST["title"])) {
        $title = "%" . $_POST["title"] . "%";
        $where.= "title LIKE :title AND ";
        $searching.= " for " . $_POST["title"];
    }

    if (!empty($_POST["genre"])) {
        $genre = $_POST["genre"];
        $where.="genre = :genre AND ";
        $searching.= " in " . $_POST["genre"];
    }

    if (!empty($_POST["year"])) {
        $year = $_POST["year"];
        $where.= "year = :year AND ";
        $searching.= " from " . $_POST["year"];
    }

    if (!empty($_POST["rating"])) {
        $rating = $_POST["rating"];
        $where.="rating = :rating AND ";
        $searching.= " with rating " . $_POST["rating"];
    }

    $where = rtrim($where, " AND ");

    try {
        $sql = "SELECT title, year, genre, rating, studio, status, sound, versions, recretprice, aspect
                FROM movies 
                WHERE $where
                ORDER BY title;";

        // echo "<p>" . $sql . "</p><br>";
        echo "<p>" . $searching . "</p><br>";

        $stmt = $conn->prepare($sql);

        if (!empty($_POST["title"])) {
            $stmt->bindParam(":title", $title);
        }

        if (!empty($_POST["genre"])) {
            $stmt->bindParam(":genre", $genre);
        }
        
        if (!empty($_POST["year"])) {
            $stmt->bindParam(":year", $year);
        }

        if (!empty($_POST["rating"])) {
            $stmt->bindParam(":rating", $rating);
        }
        
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {

            echo "<table style class='table';>";
            echo "<tr><th>Title</th><th>Year</th><th>Genre</th><th>Rating</th><th>Studio</th><th>Status</th><th>Sound</th><th>Versions</th><th>Retail Price</th><th>Aspect</th></tr>";

            foreach (new TableRows(new RecursiveArrayIterator($result)) as $k=>$v) {
                echo $v;
            }

        } else {
            echo "<p>Your search returned no movies</p>";
        }
        
    } catch (PDOException $e) {
        echo "<p>Your search returned no movies</p>";
    }

    $conn = null;

    ?>
    </div>
</body>
</html>