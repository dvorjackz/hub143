<html>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<link rel="stylesheet" href="styles.css">
<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />

<nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="index.php">
        <img src="/img/favicon.ico" width="30" height="30" class="d-inline-block align-top" style="margin-left: 15px; margin-right: 5px;" alt="">
        <b>Hub143</b>
    </a>
</nav>

<head>
    <title>Hub143</title>
</head>

<body>
    <div class="row">
        <div class="container">
            <h1>Write a review!</h1>
            <form class="search" action="" method="POST">
                <label>Name:</label>
                <input type="text" class="form-control" placeholder="Your name" name="name" required>
                <br>
                <label>Rating:</label>
                <input type="range" class="form-range" min="1" max="5" step="0.01" id="customRange2" name="rating">
                <br>
                <br>
                <label>Review:</label>
                <input type="text" class="form-control" placeholder="Your review" name="comment" required>
                <br>
                <button class="btn btn-outline-success" style="margin: auto;display: inline-block;" type="submit" value='Submit' id='submit' name='submit'>Submit</button>
            </form>

            <?php

            $db = new mysqli('localhost', 'cs143', '', 'cs143');
            if ($db->connect_errno > 0) {
                die('Unable to connect to database [' . $db->connect_error . ']');
            }

            if (isset($_POST['submit'])) {
                $query = "INSERT INTO Review (name, time, mid, rating, comment)
                                VALUES (\"$_POST[name]\", NOW(), $_GET[mid], $_POST[rating], \"$_POST[comment]\")";

                $rs = $db->query($query);
                if (!$rs) {
                    $errmsg = $db->error;
                    print "Query failed: $errmsg <br>";
                    exit(1);
                } else {
                    header("Location: movie.php?id=$_GET[mid]");
                }
            }

            $rs->free();

            ?>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

</html>