<?php 
    include "utils.php";
    include "config.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Idle Hands</title>
        <link href="<?php echo $GLOBALS['idle-hands-home']; ?>/dist/style.css" rel="stylesheet" type="text/css">
        <script src="<?php echo $GLOBALS['idle-hands-home']; ?>/js/jquery-3.3.1.min.js"></script>
        <script src="<?php echo $GLOBALS['idle-hands-home']; ?>/js/jquery-duration-picker-master/duration-picker.js"></script>
        <script src="<?php echo $GLOBALS['idle-hands-home']; ?>/js/jquery-ui-1.12.1/jquery-ui.js"></script>
    </head>
    <body>
        <header>
            <nav id="main-navigation" role="navigation" aria-label="Main Navigation">
                <?php
                //echo get_duration(1230, 2359);
                    fill_time_slot('2018-09-16', 2);
                    //populate_gaps('2018-09-18');
                    if ( isset( $_POST['is-event'] ) ) {
                        $name = $_POST['name'];
                        $start = $_POST['start-date'];
                        $start_time = $_POST['start-time'];
                        $end = $_POST['end-date'];
                        $end_time = $_POST['end-time'];
                        $location = $_POST['location'];
                        
                        add_event($name, $start, $start_time, $end, $end_time, $location);
                        //echo 'EVENT SUBMITTED';
                    } else if ( isset( $_POST['is-task'] ) ) {
                        $name = $_POST['name'];
                        $duration = $_POST['duration'];
                        $deadline = $_POST['deadline'];
                        $location = $_POST['location'];
                        $priority = $_POST['priority'];
                        
                        add_task($name, $duration, $deadline, $location, $priority);
                        //echo 'TASK SUBMITTED';
                    } else if ( isset( $_POST['is-update'] ) ) {
                        $name = $_POST['name'];
                        $id = $_POST['id'];
                        $name = $_POST['name'];
                        $start =  $_POST['start-date'];
                        $start_time = $_POST['start-time'];
                        $end = $_POST['end-date'];
                        $end_time = $_POST['end-time'];
                        $location = $_POST['location'];
                        
                        edit_event($id, array('name', 'start_date', 'start_time', 'end_date', 'end_time', 'location'), array($name, $start, $start_time, $end, $end_time, $location));
                        //echo 'UPDATED!';
                    }
                ?>
                <a href="<?php echo $GLOBALS['idle-hands-home']; ?>" id="idle-hands-logo">
                    <svg>
                        <use xlink:href="<?php echo $GLOBALS['idle-hands-home']; ?>/img/idlehands.svg#idle-hands-logo"></use>
                    </svg>
                </a>
                <ul>
                    <li>
                        <a href="">
                            Item
                        </a>
                    </li>
                    <li>
                        <a href="">
                            Item
                        </a>
                    </li>
                    <li>
                        <a id="view-tasks">
                            Tasks
                        </a>
                    </li>
                </ul>
                <button id="add-task-button" tabindex="0">
                    Add task
                </button>
            </nav>
        </header>
        <aside id="add-task-modal">
            <button id="close-task-modal" class="close-button">
                ×
            </button>
            <form id="add-task-form" method="post">
                <h2>Add task</h2>
                <label>Name:</label>
                <input type="text" name="name" id="add-task-name" required>
                <input type="checkbox" name="is-task" class="hidden-for-screen-readers" checked>
                <label>Is this task scheduled?</label>
                <input type="checkbox" name="scheduled-checkbox" id="scheduled-checkbox">
                <div class="styled-checkbox" id="styled-scheduled-checkbox" tabindex="0">
                    <div class="styled-checkbox-toggle">
                        <svg class="pentagram inactive">
                            <use xlink:href="<?php echo $GLOBALS['idle-hands-home']; ?>/img/symbols.svg#pentagram"></use>
                        </svg>
                    </div>
                </div>
                <div class="unique-fields">
                    <label>Duration:</label>
                    <input type="text" name="duration" id="add-task-duration" required>
                    <label>Deadline:</label>
                    <input type="text" name="deadline" id="add-task-deadline" required>
                    <label>Location:</label>
                    <input type="text" name="location" id="add-task-location" required>
                    <label>Priority:</label>
                    <select id="add-task-priority" name="priority">
                        <option value="-1" selected>No priority</option>
                        <option value="0">Low priority</option>
                        <option value="1">Medium priority</option>
                        <option value="2">High priority</option>
                    </select>
                    <button type="submit">Add task</button>
                </div>
            </form>
            <form id="add-event-form" method="post">
                <div class="hidden-fields">
                    <label>Name:</label>
                    <input type="text" name="name" id="add-event-name" required>
                    <input type="checkbox" name="is-event" class="hidden-for-screen-readers" checked>
                </div>
                <div class="unique-fields">
                    <div class="field-pairs">
                        <div class="pair-fill">
                            <label>Start Date:</label>
                            <input type="text" name="start-date" id="add-event-start-date" required>
                        </div>
                        <div class="pair-auto">
                            <label>Start Time:</label>
                            <input type="text" name="start-time" id="add-event-start-time" required>
                        </div>
                    </div>
                    <div class="field-pairs">
                        <div class="pair-fill">
                            <label>End Date:</label>
                            <input type="text" name="end-date" id="add-event-end-date" required>
                        </div>
                        <div class="pair-auto">
                            <label>End Time:</label>
                            <input type="text" name="end-time" id="add-event-end-time" required>
                        </div>
                    </div>
                    <label>Location:</label>
                    <input type="text" name="location" id="add-event-location">
                    <button type="submit">Add task</button>
                </div>
            </form>
        </aside>
        <aside id="view-task-modal">
            <button id="close-view-modal" class="close-button">
                ×
            </button>
            <?php
                $tasks = get_all_tasks();
                if ( $tasks != 0 ) {
            ?>
            <h2>Tasks list</h2>
            <?php 
                $unassigned = array();
                $assigned = array();
                foreach( $tasks as $task ) { 
                    if ( $task['start_date'] == '' ) { 
                        $unassigned[] = $task;
                    } else {
                        $assigned[] = $task;
                    }
                }
            
                if ( count($unassigned) > 0 && $unassigned[0] != '' ) {
            ?>
            <h4>Unassigned</h4>
            <table>
            <?php 
                foreach( $unassigned as $task ) { 
                    //print_r($task);
            ?>
                <tr>
                    <td>
                        <?php echo $task['name']; ?>
                    </td>
                    <td>
                        <?php echo $task['duration']; ?>
                    </td>
                </tr>
            <?php 
                } 
            ?>
            </table>
            <?php
                }
            ?>
            <h4>Assigned</h4>
            <table>
            <?php 
                foreach( $assigned as $task ) { 
                    //print_r($task);
            ?>
                <tr>
                    <td>
                        <?php echo $task['name']; ?>
                    </td>
                    <td>
                        <?php echo $task['start_date']; ?>
                    </td>
                </tr>
            <?php 
                } 
            ?>
            </table>
            <?php } else { ?>
            <h2>Sorry, there are no tasks to show.</h2>
            <?php } ?>
        </aside>