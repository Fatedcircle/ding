<?php

	$args = [
		'post_type' => 'Boek',
	];

	$query = new WP_Query($args);

	if ($query->have_posts()) { ?>
		<h1><?php echo esc_html($attributes['headingText']) ?></h1>
		<?php while ($query->have_posts()) {
			$query->the_post(); ?>
			<div class="program-cards">
			<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
			<?php if ($attributes['showContent']) {
                the_content();
            } ?>
			</div>
		<?php }
	}
    ?>
