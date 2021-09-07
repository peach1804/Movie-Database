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
            <?php require "connect.php";

            if (!empty($_POST["email"])) {
                $email = $_POST["email"];
            }

            try {
                $sql = "INSERT emails
                        INTO registrations;";

                $stmt = $conn->prepare($sql);

                if (!empty($_POST["email"])) {
                    $stmt->bindParam(":email", $email);
                }

                $stmt->execute();

            } catch (PDOException $e) {
                echo "<p>Your email could not be registered</p>";
            }

            $conn = null;

            ?>
        </div>
    </body>
</html>