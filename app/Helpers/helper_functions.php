<?php

function escape($data)
{
    $data = trim($data);        //remove space, backspace
    $data = stripslashes($data);    //remove ‘/’
    $data = htmlspecialchars($data);    //avoid xss attack
    return $data;
}


?>
