<?php

# header
get_header();


# structuring: left column, inner-column, right column.

?><div class="inner-column"><?php

# header: slideshow
get_template_part('template-parts/slideshow-content', get_post_format());

# post snippets listing
?><div id="snippets-listing"><?php

# the loop
if (have_posts()) {
  $i = 0;
  while (have_posts()) {
    $i = $i + 1;

    // if this is a snippet on the right (even index), insert a placeholder to shift the snippet down a bit
    if ($i % 2 == 0)
    echo '<div class="buffer"></div>';

    the_post();
    // render the post-snippet
    get_template_part('template-parts/post-snippet', get_post_format());

    // if this is the second post-snippet, insert the igwidget here
    if ($i == 2)
    {
       echo '<div style="clear:both" />';
       echo '<div class="igfeed-widget"><ul id="igfeed-list"/></div>';
    }
  }
#  echo '<script>console.log(' . json_encode($wp_query) . ');</script>';
  if ($wp_query->max_num_pages > 1) {
    echo '<div class="loadmore-container"><a class="loadmore-button">More</a></div>';
  }
}

?></div><?php
# the left column: for the menu button
?></div>
<div id="left-column">
  <div class="menu-label">Menu</div>
</div>
<div id="right-column">
  <div class="search-box">
    <img src="<?= get_theme_file_uri('assets/search.png') ?>" class="search">
  </div>
</div><?php


# footer
get_footer();

