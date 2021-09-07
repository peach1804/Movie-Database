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
            <li><a href="register.php">Register</a></li>
        </ul>
    </div>

    <?php
    require("connect.php");

    try {
        $sql = "SELECT title, ((rank - 99)*100)
                FROM movies 
                ORDER BY rank DESC
                LIMIT 10;";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rank = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        // var_export($rank);

        /*
        * Chart settings and create image
        */

        // Image dimensions
        $imageWidth = 400;
        $imageHeight = 400;

        // Grid dimensions and placement within image
        $gridTop = 10;
        $gridLeft = 10;
        $gridBottom = 370;
        $gridRight = 350;
        $gridHeight = $gridBottom - $gridTop;
        $gridWidth = $gridRight - $gridLeft;

        // Bar and line width
        $lineWidth = 1;
        $barWidth = 20;

        // Font settings
        $font = 'OpenSans-Regular.ttf';
        $fontSize = 10;

        // Margin between label and axis
        $labelMargin = 8;

        // Max value on x-axis
        $xMaxValue = 100;

        // Distance between grid lines on x-axis
        $xLabelSpan = 10;

        // Init image
        $chart = imagecreate($imageWidth, $imageHeight);

        // Setup colors
        $backgroundColor = imagecolorallocate($chart, 255, 255, 255);
        $axisColor = imagecolorallocate($chart, 85, 85, 85);
        $rankLabelColor = $axisColor;
        $titleLabelColor = $backgroundColor;
        $gridColor = imagecolorallocate($chart, 212, 212, 212);
        $barColor = imagecolorallocate($chart, 47, 133, 217);

        imagefill($chart, 0, 0, $backgroundColor);

        imagesetthickness($chart, $lineWidth);

        /*
        * Print grid lines bottom up
        */

        for ($i = 0; $i <= $xMaxValue; $i += $xLabelSpan) {
            $x = $gridLeft + $i * $gridWidth / $xMaxValue;

            // draw the line
            imageline($chart, $x, $gridTop, $x, $gridBottom, $gridColor);

            // draw right aligned label
            $labelBox = imagettfbbox($fontSize, 0, $font, strval($i));
            $labelWidth = $labelBox[4] - $labelBox[0];

            $labelX = $x;
            $labelY = $gridBottom + $fontSize*2;

            imagettftext($chart, $fontSize, 0, $labelX, $labelY, $rankLabelColor, $font, strval($i));
        }

        /*
        * Draw x- and y-axis
        */

        imageline($chart, $gridLeft, $gridTop, $gridLeft, $gridBottom, $axisColor);
        imageline($chart, $gridLeft, $gridBottom, $gridRight, $gridBottom, $axisColor);

        /*
        * Draw the bars with labels
        */

        $barSpacing = $gridWidth / count($rank);
        $itemX = $gridTop + $barSpacing / 2;

        foreach ($rank as $key => $value) {
            // Draw the bar
            $x1 = $gridLeft; 
            $y1 = $itemX + $barWidth / 2;

            $x2 = $gridLeft + $value / $xMaxValue * $gridWidth;
            $y2 = $itemX - $barWidth / 2;       

            imagefilledrectangle($chart, $x1, $y1, $x2, $y2, $barColor);

            // Draw the label
            $labelBox = imagettfbbox($fontSize, 0, $font, $key);
            $labelWidth = $labelBox[4] - $labelBox[0];
            
            $labelX = $gridLeft + $labelMargin;
            $labelY = $gridTop + $itemX - 5;

            imagettftext($chart, $fontSize, 0, $labelX, $labelY, $titleLabelColor, $font, $key);

            $itemX += $barSpacing;
        }

        /*
        * Output image to browser
        */
        imagepng($chart, "top10_chart.png");

        echo "<div class='graph'>";
        echo "<p>Top 10 Most Searched Movies</p>";
        echo "<img src='top10_chart.png'>";
        echo "</div>";
        
        imagedestroy($chart);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>

</body>
</html>