<!DOCTYPE html>
<html>
    <?php
        include "config.php";
    ?>
    <head>
        <title>Idle Hands</title>
        <link href="<?php echo $GLOBALS['idle-hands-home']; ?>/dist/style.css" rel="stylesheet" type="text/css">
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
                <button>
                    Add task
                </button>
            </nav>
        </header>