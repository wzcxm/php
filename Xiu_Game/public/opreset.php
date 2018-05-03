<?php
    try{
        opcache_reset();
        var_dump('OK');
    }catch (\Exception $e){
        var_dump($e->getMessage());
    }


?>