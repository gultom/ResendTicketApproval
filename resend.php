<?php

require ('config/main.php');

$ReSender = new ReSender();
$ReSender->resendMail(new Ticket(), new Mail());

?>
