<?php while ( have_posts() ) : the_post(); ?>

    <h1 class="entryTitle"><?php the_title(); ?></h1>
    <div class="entryContent"><?php the_content(); ?></div>

<?php endwhile; // end of the loop. ?>

