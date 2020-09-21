<?php
    $sr_no=0;
    if($_REQUEST['page']>1)
        $sr_no=(($_SESSION['show_records']*($_REQUEST['page']-1))+$bgColorCounter);
    else
        $sr_no=($bgColorCounter);
    ?>