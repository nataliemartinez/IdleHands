<?php

    //connect to database
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

    //execute mySQL query
    function run_query($query, $result_mode=MYSQLI_STORE_RESULT) {
        $conn = connect_db();
        $result = mysqli_query($conn, $query, $result_mode);
        mysqli_close($conn);
        return $result;
    }

    //get event
    function get_event($id) {
        $query = "SELECT * FROM event WHERE id='$id'";
        $result = run_query($query);
        if (mysqli_num_rows($result) == 0) {
            die ("Could not locate task");
        } else {
            $row = mysqli_fetch_assoc($result);
            return $row;
        }
    }

    //add event
    function add_event($name, $start, $end, $location) {
        $query = "INSERT INTO events (name, start_date, end_date, location) VALUES ('$name', '$start', '$end', '$location')";
        run_query($query);
    }

    //delete event
    function delete_event($id) {
        $query = "DELETE FROM events WHERE id='$id'";
        run_query($query);
    }

    //edit event
    function edit_event($id, $fields, $values) {
        $query = "UPDATE events SET ";
        $i = 0;
        while ($i < (count($fields))) {
            $query .= "'" . $field[$i] . "' = '" . $values[$i] . "', ";
        }

        $query = substr($query, 0, -2);
        $query .= " WHERE id='$id'";
        run_query($query);
    }

    //get task
    function get_task($id) {
        $query = "SELECT * FROM tasks WHERE id='$id'";
        $result = run_query($query);
        if (mysqli_num_rows($result) == 0) {
            die ("Could not locate task");
        } else {
            $row = mysqli_fetch_assoc($result);
            return $row;
        }
    }

    //add task
    function add_task($name, $duration, $location, $priority) {
        $query = "INSERT INTO tasks (name, duration, location) VALUES ('$name', '$start', '$end', '$location')";
        run_query($query);
    }

    //delete task
    function delete_task($id) {
        $query = "DELETE FROM tasks WHERE id='$id'";
        run_query($query);
    }

    //edit task
    function edit_task($id, $fields, $values) {
        $query = "UPDATE tasks SET ";
        $i = 0;
        while ($i < (count($fields))) {
            $query .= "'" . $field[$i] . "' = '" . $values[$i] . "', ";
        }

        $query = substr($query, 0, -2);
        $query .= " WHERE id='$id'";
        run_query($query);
    }

    //get events for one day
    function get_day_schedule($date) {
        $query = "SELECT * FROM events WHERE start_date='$date'";
        $result = run_query($query);
        return $result;
    }
