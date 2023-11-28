<?php
include("includes/header.php"); 

//PAGE CONTENT
content($page);
$now = date("Y-m-d");
//Past events
if($input->urlSegment1 == 'past') {
    $events=$pages->find("template=event, date2<=$now");
    $subhead = "Showing past events. <a href='./'>See upcoming events.</a>";
    
//Future events
}else{
    $events=$pages->find("template=event, date2>=$now");
    $subhead = "Showing upcoming events. <a href='./past'>See past events.</a>";
}
echo "<h2>$subhead</h2>";

showEvents($events);

include("includes/footer.php"); 
?>