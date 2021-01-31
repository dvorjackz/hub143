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
        <div class="container two-col">
            <h1>Matching actors:</h1>

            <?php

            $db = new mysqli('localhost', 'cs143', '', 'cs143');
            if ($db->connect_errno > 0) {
                die('Unable to connect to database [' . $db->connect_error . ']');
            }

            $names = explode(" ", $_GET["query"]);

            $query =
                "SELECT id, first, last
                FROM Actor
                WHERE ";

            for ($i = 0; $i < count($names); $i++) {
                if ($i != 0)
                    $query .= "OR ";
                $query .= "first = \"$names[$i]\" OR ";
                $query .= "last = \"$names[$i]\" ";
            }

            $query .= "ORDER BY last ASC, first ASC";

            $rs = $db->query($query);
            if (!$rs) {
                $errmsg = $db->error;
                print "Query failed: $errmsg <br>";
                exit(1);
            }

            if (mysqli_num_rows($rs) == 0) {
                print "No actors found.";
            } else {
                print "<ul class=\"list-group col\">";
                while ($row = $rs->fetch_assoc()) {
                    $id = $row['id'];
                    $first = $row['first'];
                    $last = $row['last'];

                    print "<a href=\"actor.php?id=$id\" class=\"list-group-item list-group-item-action\">";
                    print "$last, $first";
                    print "</a>";
                }
                print "</ul>";
            }

            $rs->free();

            ?>
        </div>

        <div class="container two-col">
            <h1>Matching movies:</h1>
            <?php

            $db = new mysqli('localhost', 'cs143', '', 'cs143');
            if ($db->connect_errno > 0) {
                die('Unable to connect to database [' . $db->connect_error . ']');
            }

            $names = explode(" ", $_GET["query"]);

            $query =
                "SELECT DISTINCT m.id, m.title, m.year, m.rating, m.company
                FROM Movie m, MovieActor ma, Actor a
                WHERE m.id = ma.mid AND a.id = ma.aid AND (";

            for ($i = 0; $i < count($names); $i++) {
                if ($i != 0)
                    $query .= "OR ";
                $query .= "m.title LIKE \"%$names[$i]%\" ";
            }

            $query .= "
                )
                ORDER BY m.title ASC";

            $rs = $db->query($query);
            if (!$rs) {
                $errmsg = $db->error;
                print "Query failed: $errmsg <br>";
                exit(1);
            }

            if (mysqli_num_rows($rs) == 0) {
                print "No movies found.";
            } else {
                print "<ul class=\"list-group col\">";
                while ($row = $rs->fetch_assoc()) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $year = $row['year'];
                    $rating = $row['rating'];
                    $company = $row['company'];

                    print "<a href=\"movie.php?id=$id\" class=\"list-group-item list-group-item-action\">";
                    print "$title ($year)";
                    print "</a>";
                }
                print "</ul>";
            }

            $rs->free();

            ?>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

</html>