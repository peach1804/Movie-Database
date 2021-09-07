<?php require "connect.php";?>

<form class="form" action="search_sql.php" method="post">
    
    <label>Title:</label><br>
    <input type="text" id="title" name="title"><br>
    
    <label>Genre:</label><br>
    <select id="genre" name="genre">
        <option value="0">Search Genres</option>
        <?php
        $sql = "SELECT DISTINCT genre FROM movies ORDER BY genre;";
        $stmt = $conn->query($sql);
        while ($row = $stmt->fetch()) {
            echo "<option>$row[genre]</option><br>";
        }
        ?>
    </select><br>
    
    <label>Rating:</label><br>
    <select id="rating" name="rating">
        <option value="0">Search Ratings</option>
        <?php
        $sql = "SELECT DISTINCT rating FROM movies ORDER BY rating;";
        $stmt = $conn->query($sql);
        while ($row = $stmt->fetch()) {
            echo "<option>$row[rating]</option><br>";
        }
        ?>
    </select><br>
    
    <label>Year:</label><br>
    <select id="year" name="year">
        <option value="0">Search Years</option>
        <?php
        $sql = "SELECT DISTINCT year FROM movies ORDER BY year DESC;";
        $stmt = $conn->query($sql);
        while ($row = $stmt->fetch()) {
            echo "<option>$row[year]</option><br>";
        }
        ?>
    </select><br>
    
    <input type="submit" value="Search" style= "margin-top: 10px;">

</form>