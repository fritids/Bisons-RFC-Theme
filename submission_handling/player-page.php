<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}
update_post_meta($post, 'description',  $_POST['description'] );
update_post_meta($post, 'link', esc_attr ( $_POST['link']) );
