<header>
<h2>Player Management</h2>    
</header>
<p>Please find below personal details of all members. Note that this information is strictly <strong>confidential.</strong></p>
<?php
    global $wp_roles;

    $roles = $wp_roles->roles;
    foreach ( $roles as $key => $array ) {
        $users = get_users( array ( 'role' => $key ) );
        if ( sizeof ( $users ) )
        { 
            ?>
        <h3><?php echo  $array['name'] ?></h3>
        <table class='playermanagement'>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Contact Details</th>
                        <th>Medical Information</th>
                    <th>Next of Kin</th>
                    <th>Experience and Fitness</th>
                    <th>How did you hear</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user) :
                      $current_form = new WP_Query ( array (
                         'post_type' => 'membership_form',
                         'max_num_pages' => 1,
                         'orderby'   => 'date',
                         'author'   => $user->data->ID
                         ) );
                         
                if ( ! $current_form->have_posts() ) : ?>
                <tr>
                    <td><strong><?php echo $user->data->display_name ?></strong></td>
                    <td colspan="7">No membership form submitted.</td>
                </tr>
                <?php else : ?>
                    <?php while ( $current_form->have_posts() ) : $current_form->the_post();
                        $dob = get_post_meta(get_the_id(), 'dob', true); 
                     ?> 
                        <tr>
                            <td><strong><?php echo get_post_meta(get_the_id(), 'firstname', true) ?> <?php echo get_post_meta(get_the_id(), 'surname', true) ?></strong></td>
                            <td><?php echo reformat_date( $dob, 'd/m/Y') ?> (<?php echo getage($dob) ?>)</td>
                            <td>
                                <ul>
                                    <li><a href='mailto:<?php echo get_post_meta(get_the_id(), 'email_addy', true) ?>'><?php echo get_post_meta(get_the_id(), 'email_addy', true) ?></a></li>
                                    <li><?php echo get_post_meta(get_the_id(), 'contact_number', true) ?></li>
                                    <li><?php echo get_post_meta(get_the_id(), 'streetaddy', true) ?><br /><?php echo get_post_meta(get_the_id(), 'postcode', true) ?></li>
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    <li><strong>Allergies</strong><br /><?php echo get_post_meta(get_the_id(), 'allergies', true) ?></li>
                                    <li><strong>Medication</strong><br /><?php echo get_post_meta(get_the_id(), 'medication', true) ?></li>
                                    <li><strong>Other</strong><br /><?php echo get_post_meta(get_the_id(), 'othermedicalstuff', true) ?></li>
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    <li><?php echo get_post_meta(get_the_id(), 'nokfirstname', true) ?> <?php echo get_post_meta(get_the_id(), 'noksurname', true) ?> (<?php echo get_post_meta(get_the_id(), 'nokrelationship', true) ?>)</li>
                                    <li><?php echo get_post_meta(get_the_id(), 'nokcontactnumber', true) ?></li>
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    <li><strong>Experience</strong><br /><?php echo get_post_meta(get_the_id(), 'prevexperience', true) ?></li>
                                    <li><strong>Other fitness</strong><br /><?php echo get_post_meta(get_the_id(), 'otherfitness', true) ?></li>
                                </ul>
                            </td>
                            <td><?php echo get_post_meta(get_the_id(), 'howdidyouhear', true) ?></td>
                        </tr>
                    <?php endwhile ?>
                <?php endif ?>
            <?php endforeach ?>    
            </tbody>
        </table>
         
  <?php }
    }
?>


