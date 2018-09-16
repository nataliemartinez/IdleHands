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
        // echo $query;
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
    function add_event($name, $start, $start_time, $end, $end_time, $location) {
        $query = "INSERT INTO events (name, start_date, start_time, end_date, end_time, location) VALUES ('$name', DATE '$start', '$start_time', DATE '$end', '$end_time', '$location')";
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
    function add_task($name, $duration, $deadline, $location, $priority) {
        $query = "INSERT INTO tasks (name, duration, deadline, location, priority) VALUES ('$name', '$duration', DATE '$deadline', '$location', '$priority')";
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
        $query = "SELECT * FROM events WHERE start_date='$date' ORDER BY (start_time)";
        $result = run_query($query);
        if (mysqli_num_rows($result) == 0) {
            return 0;
        } else {
            $rows = array();
            while($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
    }

    //populate time gaps for a given day
    function populate_gaps($date) {
        $events = get_day_schedule($date);
        $gap_start = "0000";
        foreach ($events as $event) {
            $query = "INSERT INTO gaps (start_date, start_time, end_date, end_time) VALUES ('$date', '$gap_start', '$date', '$event['end_time']')";
            run_query($query);
            $gap_start = $event['end_time'];
        }

        $query1 = "INSERT INTO gaps (start_date, start_time, end_date, end_time) VALUES ('$date', '$gap_start', '$date', '2459')";
        run_query($query1);
    }

    //fill an empty time slot
    function fill_time_slot($date, $task_id) {
        $task = get_task($task_id);
        $duration = $task['duration'];
        $query1 = "SELECT TOP 1 * FROM gaps WHERE duration > '$duration' ORDER BY (duration - '$duration')";
        $result = run_query($query1);

        if (mysqli_num_rows($result) == 0) {
            die ("Could not locate empty time block");
        } 
        $slot = mysqli_fetch_assoc($result);
        if ($slot['duration'] < 0) {
            die ("No time blocks long enough");
        }

        $query2 = "UPDATE tasks SET start_date = '$slot['start_date']', start_time = '$slot['start_time']', end_date = '$slot['end_date']', end_time = '$slot['end_time'] WHERE id = '$task_id'";
        run_query($query2);

        $task = get_task($task_id);
        if ($task['duration'] == $slot['duration']) {
            $query3 = "DELETE FROM tasks WHERE id='$slot['id']'";
            run_query($query3)
        } else {
            $query3 = "UPDATE gaps SET start_time = '$task['end_time']'";
            run_query($query3);
        }



    }
