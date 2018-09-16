<?php
    include 'header.php';
?>
<main id="main">
    <div class="calendar-weekly">
        <div class="calendar-weekly-fixed">
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
        </div>
        <?php
        $dayscount = 0;
        while ( $dayscount < 7 ) {
            $dayscount++;
            $time = (rand(0,24) * 100) + rand(0,60);
            $title = "Event title";
        ?>
        <div class="calendar-weekly-day">
            <div class="event" data-title="<?php echo $title; ?>" data-time="<?php echo $time; ?>" data-duration="90" data-starred="false">
                <h4>
                    <svg class="pentagram inactive" tabindex="0">
                        <use xlink:href="<?php echo $GLOBALS['idle-hands-home']; ?>/img/symbols.svg#pentagram"></use>
                    </svg>
                    <?php echo $title; ?>
                </h4>
                <p>Event description</p>
            </div>
        </div>
        <?php
        }
        ?>
    </div>
</main>
<?php
    include 'footer.php';
?>