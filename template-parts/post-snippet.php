<div class="snippet-box">
  <div class="snippet-box-inner"><?php
    if (!has_post_thumbnail()) {
      echo '<img src="http://via.placeholder.com/800x600"/>';
    }
    else {
      the_post_thumbnail('full');
    }
    ?><h1 class="title"><?= the_title() ?></h1>
    <div class="excerpt"><?= the_excerpt() ?></div>
    <p><span class="location"><?= the_terms($post->ID, 'location') ?></span> -- <span class="date"><?= get_the_date() ?></span></p>
  </div>
</div>
