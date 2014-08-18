<footer>
    <div id="col">This website is powered by <a href="http://wordpress.com/">Wordpress</a>. Layout and theme &copy; Ben Wainwright 2014, all images and content &copy; Bristol Bisons RFC 2014. To get in touch with the team, click <a href='<?php echo $GLOBALS['blog_info']['url'] ?>/about-us/#getintouch'>here</a>.
</footer>
<?php 
global $timer;
$timer->record_execution_time('End of footer.php') ?>
<?php $timer->print_execution_times() ?>
<?php wp_footer() ?>
</body></html>