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
        $formatted_start_time = format_time($start_time);
        $formatted_end_time = format_time($end_time);
        $query = "INSERT INTO events (name, start_date, start_time, end_date, end_time, location, confirmed) VALUES ('$name', DATE '$start', '$formatted_start_time', DATE '$end', '$formatted_end_time', '$location', 1)";
        run_query($query);
        
        $gapsQuery = "DELETE FROM gaps";
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
        $datesToTest = array('2018-09-16', '2018-09-17','2018-09-18','2018-09-19','2018-09-20','2018-09-21','2018-09-22');
        foreach( $datesToTest as $toTest ) {
            populate_gaps( $toTest );
        }
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
        
        $gapsQuery = "DELETE FROM gaps";
        run_query($gapsQuery);
        
        $datesToTest = array('2018-09-16', '2018-09-17','2018-09-18','2018-09-19','2018-09-20','2018-09-21','2018-09-22');
        foreach( $datesToTest as $toTest ) {
            populate_gaps( $toTest );
        }
    }

    function confirm_event( $id ) {
        $query = "UPDATE events SET confirmed = 1 WHERE id='$id'";
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
        $newduration = format_duration($duration);
        
        $query = "INSERT INTO tasks (name, duration, deadline, location, priority) VALUES ('$name', '$newduration', DATE '$deadline', '$location', '$priority')";
        run_query($query);
        
        $gapsQuery = "DELETE FROM gaps";
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
        $datesToTest = array('2018-09-16', '2018-09-17','2018-09-18','2018-09-19','2018-09-20','2018-09-21','2018-09-22');
        foreach( $datesToTest as $toTest ) {
            populate_gaps( $toTest );
        }
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
        
        $gapsQuery = "DELETE FROM gaps";
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
        $query = "SELECT * FROM tasks ORDER BY priority DESC";
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
    function get_all_events_and_tasks() {
        $query = "SELECT * FROM tasks ORDER BY start_date ASC";
        $query2 = "SELECT * FROM events ORDER BY start_date ASC";
        $result = run_query($query);
        $result2 = run_query($query2);
        if (mysqli_num_rows($result) == 0 && mysqli_num_rows($result2) == 0) {
            return 0;
        } else {
            $rows = array();
            while($row = $result->fetch_assoc()) {
                $rows[] = $row;
            } while ($row = $result2->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
    }

    function get_day_gaps($date) {
        $query = "SELECT * FROM gaps WHERE start_date='$date'";
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

    //fill tasks in gaps
    function suggest_tasks() {
        $tasks = get_all_tasks();
        $datesToTest = array('2018-09-16', '2018-09-17','2018-09-18','2018-09-19','2018-09-20','2018-09-21','2018-09-22');
        $i = 0;
        foreach ($tasks as $task) {
            if ($task['locked'] == 0) {    
                if (get_day_gaps($datesToTest[$i]) != 0) {
                    fill_time_slot($datesToTest[0], $task['id']);
                } else {
                    $i++;
                }
                if ($i > count($datesToTest)) {
                    return 0;
                }
            }
        }
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

        $task_name = $task['name'];
        $task_location = $task['location'];
        $slot_start_date = $slot['start_date'];
        $slot_start_time = $slot['start_time'];
        $slot_end_date = $slot['start_date'];
        $slot_end_time = get_new_end_time($slot['start_time'], $task['duration']);
        
        $query2 = "INSERT INTO events (name, start_date, start_time, end_date, end_time, location) VALUES ('$task_name', '$slot_start_date', '$slot_start_time', '$slot_end_date', '$slot_end_time', '$task_location') ";

        run_query($query2);

        $query3 = "UPDATE tasks SET start_date = '$slot_start_date', start_time = '$slot_start_time', end_date = '$slot_end_date', end_time = '$slot_end_time', locked=1 WHERE id = '$task_id'";

        run_query($query3);

        $task = get_task($task_id);
        if ($task['duration'] == $slot['duration']) {
            $slot_id = $slot['id'];
            $query3 = "DELETE FROM slots WHERE id='$slot_id'";
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

    // format string duration
    function format_duration( $string ) {
        $formatArr = explode(',', $string);
        $formatArr[0] = str_replace(array('hours', 'minutes', ' ', ','), '',$formatArr[0]);
        $formatArr[1] = str_replace(array('hours', 'minutes', ' ', ','), '',$formatArr[1]);
        $timeInt = (intval($formatArr[0]) * 60) + intval($formatArr[1]);
        
        return $timeInt;
    }

    // add time
    function add_time( $t1, $t2 ) {
        $t1Minutes = $t1 % 100;
        $t2Minutes = $t2 % 100;
        $t1Hours =(($t1 - $t1Minutes)/100);
        $t2Hours =(($t2 - $t2Minutes)/100);
        
        $sum = (($t1Hours + $t2Hours)*100) + ($t1Minutes + $t2Minutes);
        
        return $sum;
    }

    //get end time from duration and start of slot 
    function get_new_end_time( $slot_start, $task_duration ) {
        $minutesToAdd = $task_duration % 60;
        $minutesInSlot = $slot_start % 100;
        $task_duration -= $minutesToAdd;
        $hoursToAdd = $task_duration / 60;
        $hoursInSlot = ( $slot_start - $minutesInSlot ) / 100;
        $minutesTogether = $minutesToAdd + $minutesInSlot;
        
        if ( $minutesTogether >= 60 ) {
            $hoursToAdd += 1;
            $minutesTogether = $minutesTogether % 60;
        }
        
        $newEnd = ( ( $hoursInSlot * 100 ) + ($hoursToAdd * 100)) + $minutesTogether;
        
        return $newEnd;
    }