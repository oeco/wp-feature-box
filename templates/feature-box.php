<?php
$description = get_feature_box_description();
$image = get_feature_box_image();
$main_link = get_feature_box_main_link();
$links = get_feature_box_links();
?>
<section id="wp-feature-box-<?php the_ID(); ?>" class="wp-feature-box" <?php if($image) echo 'style="background-image:url(' . $image['url'] . ')";'; ?> data-itemid="<?php the_ID(); ?>">
	
	<div class="wp-feature-box-container">
	
		<div class="wp-feature-box-gradient"></div>
	
		<div class="wp-feature-box-content">
			<header class="wp-feature-box-header">
				<h2><?php the_title(); ?></h2>
				<?php if($description) : ?>
					<p><?php echo $description; ?></p>
				<?php endif; ?>
			</header>
			
			<?php if($main_link) : ?>
				<a class="wp-feature-box-main-link" title="<?php the_title(); ?>" href="<?php echo $main_link['url']; ?>" <?php if(!$link['external']) echo 'target="_blank" rel="external"'; ?>></a>
			<?php endif; ?>
			
			<?php if($links) : ?>
				<section id="wp-feature-box-<?php the_ID(); ?>-links" class="wp-feature-box-links">
					<?php foreach($links as $link_group) : ?>
						<div class="wp-feature-box-link-group">
							<h3><?php echo $link_group['title']; ?></h3>
							<ul class="group-links">
								<?php foreach($link_group['links'] as $link) : ?>
									<li class="group-link">
										<a href="<?php echo $link['url']; ?>" <?php if(!$link['external']) echo 'target="_blank" rel="external"'; ?>><?php echo $link['title']; ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</section>
			<?php endif; ?>
		</div>

	</div>

</section>