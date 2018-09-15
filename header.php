<!DOCTYPE html>
<html>
    <?php
        include "config.php";
    ?>
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
                        <a href="">
                            Item
                        </a>
                    </li>
                </ul>
                <button id="add-task-button" tabindex="0">
                    Add task
                </button>
            </nav>
        </header>
        <aside id="add-task-modal">
            <button id="close-task-modal">
                ×
            </button>
            <form id="add-task-form">
                <h2>Add task</h2>
                <label>Name:</label>
                <input type="text" name="name" id="add-task-name">
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
                    <input type="text" name="duration" id="add-task-duration">
                    <label>Deadline:</label>
                    <input type="text" name="deadline" id="add-task-deadline">
                    <label>Location:</label>
                    <input type="text" name="location" id="add-task-location">
                    <label>Priority:</label>
                    <select id="add-task-priority" name="add-task-priority">
                        <option value="-1">No priority</option>
                        <option value="0">Low priority</option>
                        <option value="1">Medium priority</option>
                        <option value="2">High priority</option>
                    </select>
                    <button type="submit">Add task</button>
                </div>
            </form>
            <form id="add-event-form">
                <div class="hidden-fields">
                    <label>Name:</label>
                    <input type="text" name="name" id="add-event-name">
                </div>
                <div class="unique-fields">
                    <label>Start Date:</label>
                    <input type="text" name="start-date" id="add-event-end-date">
                    <label>End Date:</label>
                    <input type="text" name="end-date" id="add-event-end-date">
                    <label>Location:</label>
                    <input type="text" name="location" id="add-event-location">
                    <button type="submit">Add task</button>
                </div>
            </form>
        </aside>