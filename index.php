<?php
    include 'header.php';
?>
<main id="main">
    <div class="calendar-weekly">
        <?php
            $startdate = 15;
            $month = 9;
            $year = 2018;
        ?>
        <aside class="calendar-weekly-fixed">
            <div class="hourly-template">
                <?php
                    $hourscount = 0;
                    while ( $hourscount < 24 ) {
                        
                ?>
                <div class="hour">
                    <h6>
                        <?php echo sprintf('%02d', ($hourscount % 12) + 1); ?>:00
                        <?php if ( $hourscount < 11 ) { ?>
                            AM
                        <?php } else { ?>
                            PM
                        <?php } ?>
                    </h6>
                </div>
                <?php
                        $hourscount++;
                    }
                ?>
            </div>
        </aside>
        <nav class="calendar-weekly-days-nav">
            <?php 
                $dayscount = 0;
                while ( $dayscount < 7 ) {
                    $dayscount++;
                    $day = $startdate + $dayscount;
                    $date = $year . "-" . sprintf('%02d', $month) . "-" . sprintf('%02d', $day);
                    $dateObj = new DateTime($date);
                    $weekday = $dateObj->format('l');
            ?>
            <div class="calendar-weekly-day-header">
                <h5><?php echo $weekday; ?></h5>
                <h6><?php echo $date; ?></h6>
            </div>
            <?php      
                }
            ?>
        </nav>
        <?php
            $dayscount = 0;
            while ( $dayscount < 7 ) {
                $dayscount++;
                $day = $startdate + $dayscount;
                $date = $year . "-" . sprintf('%02d', $month) . "-" . sprintf('%02d', $day);
        ?>
        <div class="calendar-weekly-day" data-date="<?php echo $date; ?>">
            <div class="hourly-template">
                <?php
                    $hourscount = 0;
                    while ( $hourscount < 24 ) {
                        
                ?>
                <div class="hour">
                    
                </div>
                <?php
                        $hourscount++;
                    }
                ?>
            </div>
            <?php
                $curEvents = 0;
                $curEvents = get_day_schedule($date);
                if ( $curEvents != 0 ) {
                    //print_r($curEvents);
                    foreach( $curEvents as $event ) { 
                        //print_r($event);
                        $title = $event['name'];
                        $timeBuff = explode(',', $event['start_time']);
                        $time = "";
                        foreach( $timeBuff as $timeEl ) { 
                            $time = $time . str_replace(' ', '', $timeEl);
                        }
                        
                        $endTimeBuff = explode(',', $event['end_time']);
                        $end_time = "";
                        foreach( $endTimeBuff as $timeEl ) { 
                            $end_time = $end_time . str_replace(' ', '', $timeEl);
                        }
                        
                        $location = $event['location'];
                        $id = $event['id'];
                        $duration = get_duration( $event['start_time'], $event['end_time'] );
                        
                        $eventClasses = "";
                        
                        if ( $event['confirmed'] == 0 ) {
                            $eventClasses .= " to-confirm";
                        }
                        
            ?>
            <div class="event<?php echo $eventClasses; ?>" data-title="<?php echo $title; ?>" data-time="<?php echo $time; ?>" data-date="<?php echo $date; ?>" data-end-time="<?php echo $end_time; ?>" data-end-date="<?php echo $date; ?>" data-duration="<?php echo $duration; ?>" data-starred="false" data-location="<?php echo $location; ?>" data-id="<?php echo $id; ?>" data-confirmed="<?php echo $event['confirmed']; ?>">
                <h4>
                    <svg class="pentagram" tabindex="0">
                        <use xlink:href="<?php echo $GLOBALS['idle-hands-home']; ?>/img/symbols.svg#pentagram"></use>
                    </svg>
                    <span class="event-title" tabindex="0"><?php echo $title; ?></span>
                </h4>
            </div>
            <?php
                    }
                } 
            ?>
        </div>
        <?php
        }
        ?>
    </div>
</main>
<div class="event-modal" style="display: none;">
    <button id="close-event-modal" class="close-button">
        ×
    </button>
    <form id="update-information" method="post">
        <input type="checkbox" name="is-update" id="update-identifier" class="hidden-for-screen-readers">
        <input type="text" name="id" id="update-id" class="hidden-for-screen-readers">
        <h2><svg class="pentagram inactive" tabindex="0"><use xlink:href="<?php echo $GLOBALS['idle-hands-home']; ?>/img/symbols.svg#pentagram"></use></svg><input type="text" name="name" id="update-name"></h2>
        <p>Event description</p>
        <table>
            <tr>
                <td>
                    <label>Start Date</label>
                </td>
                <td>
                    <input type="text" name="start-date" id="update-start-date">
                </td>
            </tr>
            <tr>
                <td>
                    <label>Start Time</label>
                </td>
                <td>
                    <input type="text" name="start-time" id="update-start-time">
                </td>
            </tr>
            <tr>
                <td>
                    <label>End Date</label>
                </td>
                <td>
                    <input type="text" name="end-date" id="update-end-date">
                </td>
            </tr>
            <tr>
                <td>
                    <label>End Time</label>
                </td>
                <td>
                    <input type="text" name="end-time" id="update-end-time">
                </td>
            </tr>
            <tr>
                <td>
                    <label>Location</label>
                </td>
                <td>
                    <input type="text" name="location" id="update-location">
                </td>
            </tr>
        </table>
        <button type="submit" style="float:left">update</button>
    </form>
    <form id="confirm-event" method="post">
        <input type="checkbox" id="is-confirm" name="is-confirm" class="hidden-for-screen-readers" checked>
        <input type="text" name="id" id="confirm-id" class="hidden-for-screen-readers">
        <button type="submit" id="delete-event"style="float:left">confirm</button>
    </form>
    <form id="delete-event" method="post">
        <input type="checkbox" id="is-delete" name="is-delete" class="hidden-for-screen-readers" checked>
        <input type="text" name="id" id="delete-id" class="hidden-for-screen-readers">
        <button type="submit" id="delete-event"style="float:left">delete</button>
    </form>
</div>
<?php
    include 'footer.php';
?>