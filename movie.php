<html>
<link href="/bootstrap/bootstrap.min.css" rel="stylesheet">
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
            <?php

            $db = new mysqli('localhost', 'cs143', '', 'cs143');
            if ($db->connect_errno > 0) {
                die('Unable to connect to database [' . $db->connect_error . ']');
            }

            // Basic information

            $query =
                "SELECT M.title, M.year, M.rating, M.company, M.mid, GROUP_CONCAT(DISTINCT M.name ORDER BY M.name ASC SEPARATOR \", \") AS directors, GROUP_CONCAT(DISTINCT M.genre ORDER BY M.genre ASC SEPARATOR \", \") AS genre
                FROM (
                        SELECT m.title, m.year, m.rating, m.company, mg.genre, md.mid, md.did, CONCAT(d.first, \" \", d.last) AS name
                        FROM Movie m INNER JOIN MovieGenre mg ON m.id = mg.mid LEFT JOIN MovieDirector md ON m.id = md.mid LEFT JOIN Director d ON d.id = md.did
                        WHERE m.id = $_GET[id] 
                    ) as M
                GROUP BY M.title";

            $avgRatingQuery =
                "SELECT AVG(rating) as avgrating
                    FROM Review
                    WHERE mid = $_GET[id]";

            $rs = $db->query($query);
            if (!$rs) {
                $errmsg = $db->error;
                print "Query failed: $errmsg <br>";
                exit(1);
            }

            $rsRating = $db->query($avgRatingQuery);
            if (!$rsRating) {
                $errmsg = $db->error;
                print "Query failed: $errmsg <br>";
                exit(1);
            }

            $avgRating;
            while ($row = $rsRating->fetch_assoc()) {
                $avgRating = number_format($row['avgrating'], 1);
            }
            if ($avgRating == 0)
                $avgRating = "Unrated";

            if (mysqli_num_rows($rs) == 0) {
                print "<h1>Error: Movie not found.</h1>";
            } else {
                while ($row = $rs->fetch_assoc()) {
                    $title = $row['title'];
                    $year = $row['year'];
                    $rating = $row['rating'];
                    $company = $row['company'];
                    $genre = $row['genre'];
                    $directors = $row['directors'];

                    print "<h1>$title ($year)</h1>";

                    print "<div>";
                    print "<div><strong>Average rating: <strong class=\"rating\">$avgRating</strong></strong></div>";
                    print "<div><b>MPAA:</b> Rated $rating</div>";
                    print "<div><b>Producer:</b> $company</div>";
                    print "<div><b>Genre:</b> $genre</div>";
                    print "<div><b>Directed by:</b> $directors</div>";
                    print "</div>";
                }
            }

            $rs->free();

            // Actors acted in

            $query =
                "SELECT a.id, a.first, a.last, ma.role
                FROM Movie m, MovieActor ma, Actor a
                WHERE m.id = ma.mid AND a.id = ma.aid AND m.id = $_GET[id]
                ORDER BY a.first ASC, a.last ASC";

            $rs = $db->query($query);
            if (!$rs) {
                $errmsg = $db->error;
                print "Query failed: $errmsg <br>";
                exit(1);
            }

            print "<div class=\"one-col\" style=\"margin-top:30px;\">";
            print "<h3>Cast<h3>";
            print "<table class=\"table table-hover\" style=\"margin-top:5px;\">
                            <thead>
                            <tr>
                                <th scope=\"col\">Name</th>
                                <th scope=\"col\">Role</th>
                            </tr>
                            </thead>
                                <tbody>";

            while ($row = $rs->fetch_assoc()) {
                $id = $row['id'];
                $first = $row['first'];
                $last = $row['last'];
                $role = $row['role'];

                print "<tr>";
                print "<td><a href=\"actor.php?id=$id\">$first $last</a></td>
                            <td>$role</td>";
                print "</tr>";
            }

            print "</tbody></table></div>";

            $rs->free();

            // Reviews

            $query =
                "SELECT *
                FROM Review
                WHERE mid = $_GET[id]
                ORDER BY time ASC, name ASC";

            $rs = $db->query($query);
            if (!$rs) {
                $errmsg = $db->error;
                print "Query failed: $errmsg <br>";
                exit(1);
            }

            print "<div class=\"one-col\" style=\"margin-top:30px;\">";
            print "<div style=\"position: relative;\">
                        <h3>Reviews</h3>
                        <a class=\"button\" href=\"review.php?mid=$_GET[id]\" style=\"position: absolute; top: 0px; right: 0px;\">Write Review</a>
                    </div>";
            if (mysqli_num_rows($rs) == 0) {
                print "This movie has no reviews yet.";
            } else {
                print "<div class=\"accordion\" style=\"margin-top:5px;\">";
                $i = 0;
                while ($row = $rs->fetch_assoc()) {
                    $name = $row['name'];
                    $time = $row['time'];
                    $rating = $row['rating'];
                    $comment = $row['comment'];

                    print "<div class=\"accordion-item\">
                            <h2 class=\"accordion-header\" id=\"heading$i\">
                            <button class=\"accordion-button\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#collapse$i\">
                                <div><strong class=\"rating\">$rating</strong>  -  <strong>$name</strong> at $time</div>
                            </button>
                            </h2>
                            <div id=\"collapse$i\" class=\"accordion-collapse collapse show\">
                            <div class=\"accordion-body\">
                                <code>$comment</code>
                            </div>
                            </div>
                        </div>";

                    $i += 1;
                }
                print "</div>";
            }
            print "</div>";

            $rs->free();

            ?>
        </div>
    </div>
</body>

<script src="/bootstrap/bootstrap.bundle.min.js"></script>

</html>