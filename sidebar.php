<?php global $timer; $timer->record_execution_time('Start of sidebar.php'); ?>

<div id="sidebar">
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home right sidebar') ) : ?>
<?php endif; ?>
</div>