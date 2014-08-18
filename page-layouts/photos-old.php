<?php 
$flikr = new Flikr ( $GLOBALS['api_settings'] );
$options = get_option('social-media-settings-page');
$userid = $flikr->peopleFindByUsername ( $options['flickr-username'] )->user->nsid;
$userInfo = $flikr->peopleGetInfo( $userid );
$photosurl = $userInfo->person->photosurl->_content;
$gallery = $wp_query->query_vars['gallery'];
if ( $gallery ) : 
$photos = $flikr->photosetsGetPhotos ( $gallery, 'url_q,url_z,' )->photoset->photo;
$photoinfo = $flikr->photosetsGetInfo( $gallery )->photoset;
$title = $photoinfo->title->_content;
$description = $photoinfo->description->_content;
$created = date ( 'jS \o\f F Y' , $photoinfo->date_create );
$updated = date ( 'jS \o\f F Y' , $photoinfo->date_update );

?>
<header>
    <h2><a href="<?php the_permalink() ?><?php echo $gallery ?>"><?php echo $title ?></a></h2>
    <p>Album created on the <?php echo $created ?><?php if ( $created != $updated ) { ?> and last updated on the <?php echo $updated; } ?></p>
</header>
<div class="pagecontent">
<p>Click photos below to view. To download the photos at their original resolutions, have a look at <a href='<?php echo $photosurl.'sets/'.$gallery ?>' title='<?php echo $userid ?> on Flickr'>our Flickr page</a>.</p>
</div>
<table class="photogallery">
    <tbody>
        
<?php 

$cols = 5;
$i = 0;
foreach ( $photos as $photo ) :
    

    if ($i == 0 ) echo "<tr>";
    echo "<td><a class='fancybox' rel='gallery' href='$photo->url_z'><img src='$photo->url_q' /></a></td>";
    if ($i == $cols - 1) : echo "</tr>"; $i = 0;
    else : $i++; 
    endif;
endforeach;

if ( $i != 0 ) echo "</tr>"; ?>
    </tbody>
</table>
<?php else : ?>
<header>
    <h2><a href="<?php the_permalink() ?>">Photo Albums</a></h2>
    <p>Courtesy of <a href='http://www.flickr.com/'>Flickr</a></p>
</header>

<table class="photosets">
    <tbody>
        
<?php
$photosets = $flikr->photosetsGetList ( $userid, false, false, 'url_q' )->photosets->photoset;

foreach ( $photosets as $set ) :

    $id = $set->id;
    $title = $set->title->_content;
    $description = $set->description->_content;
    $created = date ( 'jS \o\f F Y' , $set->date_create );
    $modified = date (  'jS \o\f F Y', $set->date_update );
    $primary_src = $set->primary_photo_extras->url_q; ?>
<tr>
    <td class="photosetsThumbs"><a href='<?php echo $id ?>'><img src='<?php echo $primary_src ?>' /></a></td>
    <td><h3><a href='<?php echo $id ?>'><?php echo $title ?></a></h3>
        <ul class="metalist">
            <?php if ($description) : ?><li><strong>Description</strong><br /><?php echo $description ?></li><?php endif ?>
            <li><strong>Date Created</strong><br /><?php echo $created ?></li>
        </li>
    </td>
</tr>    

    
<?php endforeach; ?>

</tbody>
</table>
<?php endif ?>
