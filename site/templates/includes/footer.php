<?php namespace ProcessWire;
    //Listing of events tagged with this page
    $events=$page->references("template=event");
    showEvents($events);
?>

</main>

<?php
$page = $pages->get(1107);
$scroll = $config->urls->templates;
echo "<footer class='bg-{$page->colour->name} p-5 mt-4'>";
include("fields/blocks.php"); 
echo "</footer>";
//var_dump($scroll);
?>
 <!--   
<script  src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="crossorigin="anonymous"></script>

<script  src="https://code.jquery.com/jquery-3.1.0.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="crossorigin="anonymous"></script>
-->

<script src="<?php echo $config->urls->templates;?>scripts/jquery-3.1.0.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="<?php echo $config->urls->templates;?>scripts/scrolltotop.js"></script>
<script src="<?php echo $config->urls->templates;?>scripts/main.js"></script>

</body>
</html>
