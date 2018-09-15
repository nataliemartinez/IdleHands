<!DOCTYPE html>
<html>
    <head>
        <title>SETUP DB</title>
    </head>
    <body>
        <?php

            function connect_db() {
                $file_path = "db_credentials.txt";
                $file = fopen($file_path, "r");
                fscanf($file, "%s %s %s %s", $server, $database, $username, $password);
                fclose($file);

                $conn = mysqli_connect($server, $username, $password, $database);

                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                    return -1;
                }

                return $conn;
            }

            $conn = connect_db();
            $query = "CREATE TABLE IF NOT EXISTS events(
                id INT(6) NOT NULL AUTO_INCREMENT,
                event_name VARCHAR(128) NOT NULL,
                start_date TIMESTAMP NOT NULL,
                end_date TIMESTAMP NOT NULL,
                location VARCHAR(128),
                PRIMARY KEY (id))";

            if (mysqli_query($conn, $query)) {
                echo "\nEvents table created.";
            } else {
                echo "Error creating events_table: " . mysqli_error($conn);
            }
            echo "\n";
            mysqli_close($conn);

            $conn = connect_db();
            $query = "CREATE TABLE IF NOT EXISTS tasks(
                id INT(6) NOT NULL AUTO_INCREMENT,
                task_name VARCHAR(128) NOT NULL,
                duration TIMESTAMP NOT NULL,
                start_date TIMESTAMP,
                end_date TIMESTAMP,
                location VARCHAR(128),
                priority VARCHAR(8),
                locked TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (id))";

            if (mysqli_query($conn, $query)) {
                echo "Tasks table created.";
            } else {
                echo "Error creating tasks_table: " . mysqli_error($conn);
            }
            echo "\n";
            mysqli_close($conn);
        
        ?>
    </body>
</html>