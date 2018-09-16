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
        //echo $query;
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
        $formatted_start_time = format_time($start_time);
        $formatted_end_time = format_time($end_time);
        $query = "INSERT INTO events (name, start_date, start_time, end_date, end_time, location) VALUES ('$name', DATE '$start', '$formatted_start_time', DATE '$end', '$formatted_end_time', '$location')";
        run_query($query);
        
        $gapsQuery = "DELETE * FROM gaps";
        run_query($gapsQuery);
        
        $datesToTest = array('2018-09-16', '2018-09-17','2018-09-18','2018-09-19','2018-09-20','2018-09-21','2018-09-22');
        foreach( $datesToTest as $toTest ) {
            populate_gaps( $toTest );
        }
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
            $query .= $fields[$i] . ' = "' . $values[$i] . '", ';
            $i++;
        }

        $query = substr($query, 0, -2);
        $query .= " WHERE id='$id'";
        run_query($query);
        
        $gapsQuery = "DELETE * FROM gaps";
        run_query($gapsQuery);
        
        $datesToTest = array('2018-09-16', '2018-09-17','2018-09-18','2018-09-19','2018-09-20','2018-09-21','2018-09-22');
        foreach( $datesToTest as $toTest ) {
            populate_gaps( $toTest );
        }
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
        
        $gapsQuery = "DELETE * FROM gaps";
        run_query($gapsQuery);
        
        $datesToTest = array('2018-09-16', '2018-09-17','2018-09-18','2018-09-19','2018-09-20','2018-09-21','2018-09-22');
        foreach( $datesToTest as $toTest ) {
            populate_gaps( $toTest );
        }
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
        
        $gapsQuery = "DELETE * FROM gaps";
        run_query($gapsQuery);
        
        $datesToTest = array('2018-09-16', '2018-09-17','2018-09-18','2018-09-19','2018-09-20','2018-09-21','2018-09-22');
        foreach( $datesToTest as $toTest ) {
            populate_gaps( $toTest );
        }
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

    //get all tasks
    function get_all_tasks() {
        $query = "SELECT * FROM tasks ORDER BY start_date ASC";
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
            $event_start_time = $event['start_time'];
            $duration = get_duration($gap_start, $event_start_time);
            $query = "INSERT INTO gaps (start_date, start_time, end_date, end_time, duration) VALUES ('$date', '$gap_start', '$date', '$event_start_time', '$duration')";
            run_query($query);
            $gap_start = $event['end_time'];
        }
        
        $duration1 = get_duration($gap_start, 2359);

        $query1 = "INSERT INTO gaps (start_date, start_time, end_date, end_time, duration) VALUES ('$date', '$gap_start', '$date', '2359', '$duration1')";
        run_query($query1);
    }

    //fill an empty time slot
    function fill_time_slot($date, $task_id) {
        $task = get_task($task_id);
        $duration = $task['duration'];
        $query1 = "SELECT * FROM gaps WHERE duration >= '$duration' ORDER BY (duration - '$duration') LIMIT 1";
        $result = run_query($query1);

        if (mysqli_num_rows($result) == 0) {
            die ("Could not locate empty time block");
        } 
        $slot = mysqli_fetch_assoc($result);
        if ($slot['duration'] < 0) {
            die ("No time blocks long enough");
        }

        $slot_start_date = $slot['start_date'];
        $slot_start_time = $slot['start_time'];
        $slot_end_date = $slot['end_date'];
        $slot_end_time = $slot['end_time'];
        
        $query2 = "UPDATE tasks SET start_date = '$slot_start_date', start_time = '$slot_start_time', end_date = '$slot_end_date', end_time = '$slot_end_time' WHERE id = '$task_id'";
        run_query($query2);

        $task = get_task($task_id);
        if ($task['duration'] == $slot['duration']) {
            $slot_id = $slot['id'];
            $query3 = "DELETE FROM tasks WHERE id='$slot_id'";
            run_query($query3);
        } else {
            $task_end_time = $task['end_time'];
            $query3 = "UPDATE gaps SET start_time = '$task_end_time'";
            run_query($query3);
        }
    }

    //format time from (HH :,MM) to HHMM
    function format_time( $time ) {
        $timeArr = explode(',', $time);
        $hours = str_replace(array(' ', ':'), '', $timeArr[0]);
        $minutes = str_replace(array(' ', ':'), '', $timeArr[1]);
        $timeInt = (intval($hours) * 100) + intval($minutes);
        
        return $timeInt;
    }

    // get duration from start and end time
    function get_duration( $start, $end ) {
        $startMinutes = $start % 100;
        $endMinutes = $end % 100;
        $startHours = ($start - $startMinutes) / 100;
        $endHours = ($end - $endMinutes) / 100;
        $diffMinutes = (60 - $startMinutes) + $endMinutes;
        $diffHours = $endHours - ($startHours + 1);
        $duration = $diffMinutes + ($diffHours * 60);
        
        return $duration;
    }