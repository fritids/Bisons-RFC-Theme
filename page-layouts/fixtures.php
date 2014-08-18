
<header>
    <h2><?php the_title(); ?></h2>
    <?php if ( current_user_can('edit_post') ) { ?><p><a href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=fixture'>Add new fixture</a></p><?php } ?>
</header>
<?php 



 $cuid = get_current_user_id();

$taxonomy = get_terms ( array ( 'seasons' ) );
foreach ($taxonomy as $tax) $taxeslight[] = $tax->name;

$getfixturequery = new WP_Query(array(
'post_type' => 'fixture',
'nopaging' => 'true',
'orderby'   => 'meta_value',
'meta_key'  => 'fixture-date',
'order'     => 'ASC',
'tax_query' => array(
    array(
        'taxonomy' => 'seasons',
        'field'    => 'slug',
        'terms'    => $taxeslight,
        'operator' => 'NOT IN'
    )
)

));
$fixtures = array();
$past_fixtures = array();
$future_fixtures = array();
$results = array();
// Handle a lack of fixtures

if(!$getfixturequery->have_posts()) : ?>
    <p>Normally this page contains the details of all the upcoming fixtures for this season. It looks like the committee haven't uploaded them yet, try back later. Alternatively, check the <a href="#">fixture archive</a>.</p>
<?php endif;

// Loop over fixtures
while($getfixturequery->have_posts()) : $getfixturequery->the_post();

    // Reformat date and convert the date and time combined into a unix time
    $unixdate = get_post_meta( get_the_id(), 'fixture-date', true );

    $printdate = date( 'jS \o\f F Y' , $unixdate );
    $time = get_post_meta( get_the_id(), 'fixture-kickoff-time', true );
    $datetime = date( 'Y:m:d' , $unixdate ). ' '.$time.':00';
    $datetimeunix = strtotime($datetime);



    // Prepare fixtures array
    $fixture = array(
        'id' => get_the_id(),
        'date' => get_post_meta( get_the_id(), 'fixture-date', true ) ? $printdate : 'Date TBC',
        'textdate' => get_post_meta( get_the_id(), 'text-date', true ),
        'kickoff' => get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) ? date("g:ia", strtotime(get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) )) : 'TBC',
        'playtime' => get_post_meta( get_the_id(), 'fixture-player-arrival-time', true ) ? date("g:ia", strtotime(get_post_meta( get_the_id(), 'fixture-player-arrival-time', true ))) : false,
        'address' => get_post_meta( get_the_id(), 'fixture-address', true ) ? get_post_meta( get_the_id(), 'fixture-address', true ) : 'TBC',
        'opposing' => get_post_meta( get_the_id(), 'fixture-opposing-team', true ) ? get_post_meta( get_the_id(), 'fixture-opposing-team', true ) : 'TBC',
        'page' => get_permalink(),
        'gmap' => get_post_meta( get_the_id(), 'fixture-gmap', true ) ? get_post_meta( get_the_id(), 'fixture-gmap', true ) : false,
        'teamurl' => get_post_meta( get_the_id(), 'fixture-opposing-team-website-url', true ) ? get_post_meta( get_the_id(), 'fixture-opposing-team-website-url', true ) : false,
        'edit_link' => '<a class="editsmall" href="'.get_edit_post_link( get_the_id() ).'">Edit fixture</a>',
        'homeaway' => get_post_meta(get_the_id(), 'fixture-home-away', true)
    );
if( $datetimeunix > time() ) $future_fixtures[] = $fixture;
    else $past_fixtures[] = $fixture;

    // Otherwise push it into the $past_fixtures array

endwhile;

// Move the next fixture from the $future_fixtures array into its own variable
$first_fixture = $future_fixtures[0];
unset($future_fixtures[0]);
if( count($future_fixtures) > 0 ) $future_fixtures = array_values($future_fixtures);

// If there is a 'Next Event' show the relevant HTML
if( $first_fixture ) : ?>
<h3>Next Fixture</h3>
<p>Friends and family are always welcome at our matches. If there are any remaining fixtures or we have already played fixtures this season, scroll down the page to find the details and match results. </p>
<section class="centeralign">

<ul class='metalist'>
    <li class="date"><?php echo $first_fixture['textdate'] ? $first_fixture['textdate'] : $first_fixture['date'] ?></li>
    <li class="info">Kickoff at <?php echo $first_fixture['kickoff']; ?><?php if($first_fixture['playtime']) : ?>, players please arrive for <?php echo $first_fixture['playtime']; ?><?php endif; ?></li>
    <li class="info">Playing against <?php echo link_if_avail($first_fixture['opposing'], $first_fixture['teamurl']); ?></li>
    <li class="address"><span class="gmap-address map-1"><?php echo $first_fixture['address']; ?></span></li>
    <li><div class="gmap-canvas" id="map-1"></div></li>
    <?php if(get_edit_post_link( get_the_id() )) : ?><li><?php echo $fixture['edit_link'] ?></li><?php endif; ?>
</ul>

<?php else: ?>
    <p>It looks like there isn't any more fixtures coming up this season, or the committee have not yet updated the website - try checking back later. In the meantime, checkout the results for the fixtures we have played so far below.</p>
<?php endif; ?>
</section>
<?php if( $future_fixtures ) : ?>
    <section class="clearsection">
    <h3>Upcoming Fixtures</h3>
    <table class="center">
        <thead>
    <tr><th>Date</th><th>Kickoff time</th><th>Location</th><th>Opposing team</th></tr>
        </thead>
        <tbody>
    <?php foreach($future_fixtures as $future_fixture) : ?>
        <tr><td><a href="<?php echo $future_fixture['page']; ?>"><?php echo $future_fixture['textdate'] ? $future_fixture['textdate'] : $future_fixture['date'] ?></a></td><td><?php echo $future_fixture['kickoff']; ?></td><td><?php echo $future_fixture['address']; ?></td><td><?php echo link_if_avail($future_fixture['opposing'], $future_fixture['teamurl']); ?></td></tr>
    <?php endforeach; ?>
        </tbody>
    </table>
    </section>
<?php endif; ?>
<?php if( $past_fixtures ) : ?>
<section class="clearsection">
<h3>Fixture Results</h3>
    <table class="resultstable center">
    <?php


    // Create match results query
    $getresultsquery = new WP_Query(array(
    'post_type' => 'result',
'nopaging' => 'true'
    ));
    // Loop over results, store in an array
    while($getresultsquery->have_posts()) : $getresultsquery->the_post();
        $results[] = array(
                        'parent-fixture' => get_post_meta(get_the_id(), 'parent-fixture', true),
                        'their-score'    => get_post_meta(get_the_id(), 'their-score', true),
                        'our-score'      => get_post_meta(get_the_id(), 'our-score', true),
                        'edit-result-link'      => "<a class='editsmall' href='".get_edit_post_link( get_the_id() )."'>Edit result</a>"
        );
    endwhile;

    // Create match reports query;
    $linked_posts_query = new WP_Query(array(
        'post_type' => 'post',
        'nopaging' => 'true', 'meta_query' => array ( 
            'relation' => 'AND',
            array(
                'key' => 'fixture_id',
                'compare' => 'EXISTS' ),
            array(
                'key' => 'fixture_id',
                'compare' => '!=' ,
                'value' => '0') )
    ));

    // Loop over reports, store in an array
    while($linked_posts_query->have_posts()) : $linked_posts_query->the_post();
        $linked_posts[] = array(
            'id' => get_the_id(),
            'parent-fixture' => get_post_meta(get_the_id(), 'fixture_id', true),
            'link' => get_permalink(get_the_id()),
            'title' => get_the_title(get_the_id())
        );
    endwhile;

    /* Loop through fixtures array and change the unix times back to a date string.
     * Also if any of the results or reports parent_fixture matches the id, insert results/reports into the details of the old fixture
     */
    $match_report_col_on = false;
    $edit_col_on = false; 
    foreach($past_fixtures as &$past_fixture) :

        foreach($results as $result) :
            if($past_fixture['id'] == $result['parent-fixture']) :
                $past_fixture['their-score'] = $result['their-score'];
                $past_fixture['our-score'] = $result['our-score'];
                $past_fixture['edit-result-link'] = $result['edit-result-link'];
            endif;
        endforeach;

        foreach($linked_posts as $linked_post) :
            if($past_fixture['id'] == $linked_post['parent-fixture']) :
                $past_fixture['linked_posts'][] = $linked_post;
                $linked_posts_col_on = true;
            endif;
        endforeach;
        if(get_edit_post_link( $past_fixture['id'] ) ) $edit_col_on = true; 
    endforeach;
    foreach($past_fixtures as $past_fixture_print) :
        $fixdate = $past_fixture_print['date'];
        $ourscore = $past_fixture_print['our-score'] ? $past_fixture_print['our-score'] : "TBC";
        $theirscore = isset($past_fixture_print['their-score']) ? $past_fixture_print['their-score'] : "TBC";
        $opposing = $past_fixture_print['opposing'];
        $oppurl = $past_fixture_print['teamurl'];
        $linkedposts = $past_fixture_print['linked_posts'];
        
        ?>
        <tr>
                <td class="date-col"><?php echo  $fixdate; ?></td>
                <?php if ($past_fixture_print['homeaway'] == "Home") : ?>
                <td class="hometeam-col"><span class="homeawaylabel">Home</span>Bristol Bisons RFC</td>
                <td class="scorecell"><?php echo $ourscore; ?></td>
                <td class="scorecell"><?php echo $theirscore; ?></td>
                <td class="oppteam-col"><span class="homeawaylabel">Away</span><?php echo team_link($opposing, $oppurl); ?></td>
                <?php else : ?>
                <td class="hometeam-col"><span class="homeawaylabel">Home</span><?php echo team_link($opposing, $oppurl); ?></td>
                <td class="scorecell"><?php echo $theirscore; ?></td>
                <td class="scorecell"><?php echo $ourscore; ?></td>
                <td class="oppteam-col"><span class="homeawaylabel">Away</span>Bristol Bisons RFC</td>
                <?php endif ?>
                <?php if($linked_posts_col_on) : ?>
                <td class="linkedposts-col<?php if(!$linkedposts) echo " nolinkedposts"; ?>">
                    <?php if($linkedposts) : ?>
                    <ul class='resultslinks'> 
                        <?php foreach ($linkedposts as $post ) : ?>
                        <li><a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a></li>
                    <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>

                </td>
                <?php endif; ?>
                <?php if ($edit_col_on) :
                $past_fixture_print['edit-result-link'] = $past_fixture_print['edit-result-link'] 
                ? $past_fixture_print['edit-result-link'] 
                : "<a class='editsmall' href='/wp-admin/post-new.php?post_type=result&parent_post=".$past_fixture_print['id']."'>Add result</a>";
                 ?>
                    <td class="resultslinks-col">
                        <?php if($past_fixture_print['edit_link']) : ?>
                    <ul class='resultslinks'>
                       <li><?php echo $past_fixture_print['edit_link']; ?></li>
                       <li><?php echo $past_fixture_print['edit-result-link']; ?></li>
                    </ul>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </table>
    </section>
<?php endif; ?>



