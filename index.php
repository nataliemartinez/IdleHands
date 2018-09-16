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
            ?>
            <div class="calendar-weekly-day-header">
                <h6><?php echo $month . "/" . $day; ?></h6>
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
            ?>
            <div class="event" data-title="<?php echo $title; ?>" data-time="<?php echo $time; ?>" data-date="<?php echo $date; ?>" data-end-time="<?php echo $end_time; ?>" data-duration="90" data-starred="false">
                <h4>
                    <svg class="pentagram inactive" tabindex="0">
                        <use xlink:href="<?php echo $GLOBALS['idle-hands-home']; ?>/img/symbols.svg#pentagram"></use>
                    </svg>
                    <span class="event-title" tabindex="0"><?php echo $title; ?></span>
                </h4>
                <p>Event description</p>
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
<!--<div class="overlay" style="display: block;"></div>
<div class="event-modal">
    <button id="close-event-modal" class="close-button">
        ×
    </button>
    <h2>Event title</h2>
    <p>Event description</p>
    <form>
        <label></label>
    </form>
</div>-->
<?php
    include 'footer.php';
?>