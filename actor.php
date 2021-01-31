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
            <?php

            $db = new mysqli('localhost', 'cs143', '', 'cs143');
            if ($db->connect_errno > 0) {
                die('Unable to connect to database [' . $db->connect_error . ']');
            }

            // Basic information

            $query =
                "SELECT *
                FROM Actor
                WHERE id = " . $_GET["id"];

            $rs = $db->query($query);
            if (!$rs) {
                $errmsg = $db->error;
                print "Query failed: $errmsg <br>";
                exit(1);
            }

            if (mysqli_num_rows($rs) == 0) {
                print "<h1>Error: Actor not found.</h1>";
            } else {
                while ($row = $rs->fetch_assoc()) {
                    $first = $row['first'];
                    $last = $row['last'];
                    $sex = $row['sex'];
                    $dob = $row['dob'];
                    $dod = $row['dod'];

                    print "<h1>$first $last</h1>";

                    print "<div>";
                    print "<div><b>Sex:</b> $sex</div>";
                    print "<div><b>Born:</b> $dob</div>";
                    if (!empty($dod))
                        print "<div><b>Died:</b>$dod</div>";
                    print "</div>";
                }
            }

            $rs->free();

            // Movies acted in

            $query =
                "SELECT m.id, m.title, m.year, ma.role
                FROM Actor a, MovieActor ma, Movie m
                WHERE m.id = ma.mid AND a.id = ma.aid AND a.id = " . $_GET["id"] . "
                ORDER BY m.year ASC";

            $rs = $db->query($query);
            if (!$rs) {
                $errmsg = $db->error;
                print "Query failed: $errmsg <br>";
                exit(1);
            }

            print "<div class=\"one-col\" style=\"margin-top:30px;\">";
            print "<h3>Filmography</h3>";
            if (mysqli_num_rows($rs) == 0) {
                print "This " . ($sex == "Male" ? "actor" : "actress") . " has not yet acted in any movies.";
            } else {
                print "<table class=\"table table-hover\" style=\"margin-top:5px;\">
                            <thead>
                            <tr>
                                <th scope=\"col\">Year</th>
                                <th scope=\"col\">Movie</th>
                                <th scope=\"col\">Role</th>
                            </tr>
                            </thead>
                                <tbody>";

                while ($row = $rs->fetch_assoc()) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $year = $row['year'];
                    $role = $row['role'];

                    print "<tr>";
                    print "<td>$year</td>
                            <td><a href=\"movie.php?id=$id\">$title</a></td>
                            <td>$role</td>";
                    print "</tr>";
                }

                print "</tbody></table>";
            }
            print "</div>";

            $rs->free();

            ?>
        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

</html>