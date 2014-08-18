<div class="wrap">
      <h1>Players</h1>
      <p>The following players have been registered to the site, but have not yet filled in a membership form.</p>
    <?php 
    $playersTable = new Players_No_Mem_form(); 
    $playersTable->prepare_items();
    $playersTable->display(); 
      ?>
</div>