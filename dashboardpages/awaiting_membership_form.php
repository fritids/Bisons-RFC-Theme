<div class="wrap">
      <h2>Players (Awaiting Club Membership) <a class='add-new-h2' href='<?php echo admin_url( 'admin.php?page=add-player' ) ?>'>Add Player</a></h2>
      <p>The following players have been registered to the site, but have not yet filled in a membership form. You can use the 'bulk actions' dropdown box below to perform a number of tasks for groups of users. Note that resending the welcome email will also reset that user's password.</p>
    <form method="post">
    <?php 
    $playersTable = new Players_No_Mem_form(); 
    $playersTable->prepare_items();
    $playersTable->display(); 
      ?>
   </form>
</div>