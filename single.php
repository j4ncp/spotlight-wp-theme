<?php

# template for single post page

# header
get_header();

# structuring: left column, inner-column, right column.

# prepare the post.
if (have_posts()):
the_post();

?><div class="inner-column">
<div class="block logo-block">
	<img src="<?= get_theme_file_uri('assets/logo.png') ?>" class="logo">
</div>
<div class="block title-block">
	<h1 class="title"><?= the_title() ?></h1>
	<p><span class="location"><?= the_terms($post->ID, 'location') ?></span> -- <span class="date"><?= get_the_date() ?></span></p>
</div>
<div class="block title-image">
	<?=the_post_thumbnail('full');?>
	<div class="post-category-container">
		<div class="post-category-display"><?= the_category(', '); ?></div>
	</div>
</div>
<div class="block content-block">
	<?php the_content(); ?>
</div>

</div><?php

else:
  echo '<p>' . _e('Sorry, no posts matched your criteria.') . '</p>';
endif;

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
