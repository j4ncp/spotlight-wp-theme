<div id="slideshow-overlay">
  <div id="slideshow-container">
    <?php 
    $categories = get_categories();
    foreach($categories as $category) {
      $args = array(
        'numberposts' => 1,
        'category' => $category->term_id
      );
      $cat_posts = get_posts($args);
      foreach($cat_posts as $cat_post) {
        ?><div class="slide">
          <a href="<?=the_permalink($cat_post)?>"><?php
          if (!has_post_thumbnail($cat_post)) {
            echo '<img src="http://via.placeholder.com/800x600"/>';
          }
          else {
            echo get_the_post_thumbnail($cat_post, 'full');
          }
          ?><div class="desc">
            <h2 class="cat"><?= $category->name ?></h2>
            <h1 class="title"><?=get_the_title($cat_post)?></h1>
            <p><span class="location"><?= the_terms($cat_post->ID, 'location') ?></span> -- <span class="date"><?=get_the_date('', $cat_post)?></span></p>
          </div>
          </a>
        </div><?php
      }
    }
    ?>
  </div>  
  <div class="logo-container">
    <img src="<?= get_theme_file_uri('assets/logo.png') ?>" class="logo">
  </div>
  <div id="slideshow-control">
    <div id="dots-list">
    </div>
  </div>
</div>