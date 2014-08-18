<header>
    <h2><a href="<?php the_permalink() ?>">The Players</a></h2>
</header>
<?php 
$players = new WP_Query( array( 'post_type' => 'playerprofile', 'nopaging' => 'true'));
if( ! $players->have_posts() ) : ?>
    <p class="infoalert">No player profiles yet - check back later.</p>
<?php else : ?>
<p>Get to know our players below! Click on the photo to learn more about the individual player. Obviously, this isn't a dating website, so we prefer not to give out contact details. But if you think that your face might look good on this page, then why don't you come along to training?</p>
<table class="playerprofiles">
    <tbody>

    <?php while ( $players->have_posts() ) : $players->the_post(); ?>
        <tr>
            <td class="thumbs"><a href="<?php the_permalink(); ?>"><img src='http://online.bisonsrfc.co.uk/wp-content/uploads/2014/04/1975133_10203643495627135_735830353_n-e1397128048215.jpg' alt='david'></img></a></td>
            <td>
                <ul class="metalist">
                    <?php if ( $name = get_post_meta( get_the_id(), 'name', true ) ) { ?><li><strong>Name: </strong><?php echo $name; ?></li><?php } ?>
                    <?php if ( $nickname = get_post_meta( get_the_id(), 'nickname', true ) ) { ?><li><strong>Nickname: </strong><?php echo $nickname; ?></li><?php } ?>
                    <?php if ( $age = get_post_meta( get_the_id(), 'age', true ) ) { ?><li><strong>Age: </strong><?php echo $age; ?></li><?php } ?>
                    <?php if ( $position = get_post_meta( get_the_id(), 'position', true ) ) { ?><li><strong>Position: </strong><?php echo $position; ?></li><?php } ?>
                    <?php if ( $exp = get_post_meta( get_the_id(), 'exp', true ) ) { ?><li><strong>Rugby experience: </strong><?php echo $exp; ?></li><?php } ?>
                    <?php if ( $jexp = get_post_meta( get_the_id(), 'jexp', true ) ) { ?><li><strong>Prior rugby Experience: </strong><?php echo $jexp ?></li> <?php } ?>
                    
                </ul>
                <?php edit_post_link( 'Edit' ); ?> 
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php endif;



