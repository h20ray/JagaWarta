<?php
/**
 * Home category section with vertical tabs.
 *
 * Expects:
 * - $args['categories'] array<WP_Term>
 * - $args['post_count'] int
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$raw_categories = isset( $args['categories'] ) && is_array( $args['categories'] ) ? $args['categories'] : array();
$categories = array_values(
	array_filter(
		$raw_categories,
		static function ( $term ) {
			return $term instanceof WP_Term;
		}
	)
);

if ( empty( $categories ) ) {
	return;
}

$post_count = isset( $args['post_count'] ) ? (int) $args['post_count'] : 10;
$post_count = max( 1, min( $post_count, 30 ) );

$panels = array();

foreach ( $categories as $category ) {
	$posts = jagawarta_get_posts_by_category( (int) $category->term_id, $post_count );
	$category_url = get_category_link( $category->term_id );
	if ( is_wp_error( $category_url ) ) {
		$category_url = jagawarta_get_posts_page_url();
	}
	$panels[] = array(
		'id'    => (string) $category->term_id,
		'label' => (string) $category->name,
		'url'   => $category_url,
		'posts' => array_map( 'intval', $posts ),
	);
}

if ( empty( $panels ) ) {
	return;
}
?>
<section class="jw-home-category-tabs bg-surface py-spacing-10 md:py-spacing-12" aria-label="<?php esc_attr_e( 'Category sections', 'jagawarta' ); ?>">
	<div class="layout-content">
		<div class="jw-home-category-tabs-shell" data-jw-category-tabs>
			<div class="jw-home-category-tabs-nav-wrap">
				<div class="jw-home-category-mobile-picker">
					<label for="jw-home-category-select" class="screen-reader-text"><?php esc_html_e( 'Choose category', 'jagawarta' ); ?></label>
					<select id="jw-home-category-select" class="jw-home-category-select" data-jw-tab-select>
						<?php foreach ( $panels as $index => $panel ) : ?>
							<option value="<?php echo esc_attr( (string) $index ); ?>" <?php selected( 0 === $index ); ?>>
								<?php echo esc_html( $panel['label'] ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="jw-home-category-tabs-nav" role="tablist" aria-orientation="vertical">
					<?php foreach ( $panels as $index => $panel ) : ?>
						<?php
						$is_active = 0 === $index;
						$tab_id = 'jw-home-cat-tab-' . $panel['id'];
						$panel_id = 'jw-home-cat-panel-' . $panel['id'];
						?>
						<button
							type="button"
							id="<?php echo esc_attr( $tab_id ); ?>"
							role="tab"
							class="jw-home-category-tab<?php echo $is_active ? ' is-active' : ''; ?>"
							aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
							aria-controls="<?php echo esc_attr( $panel_id ); ?>"
							tabindex="<?php echo $is_active ? '0' : '-1'; ?>"
							data-jw-tab>
							<span class="jw-home-category-tab-label"><?php echo esc_html( $panel['label'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="jw-home-category-tabs-content">
				<?php foreach ( $panels as $index => $panel ) : ?>
					<?php
					$is_active = 0 === $index;
					$tab_id = 'jw-home-cat-tab-' . $panel['id'];
					$panel_id = 'jw-home-cat-panel-' . $panel['id'];
					$posts = is_array( $panel['posts'] ) ? $panel['posts'] : array();
					?>
					<div
						id="<?php echo esc_attr( $panel_id ); ?>"
						role="tabpanel"
						class="jw-home-category-panel"
						aria-labelledby="<?php echo esc_attr( $tab_id ); ?>"
						<?php if ( ! $is_active ) : ?>hidden<?php endif; ?>
						data-jw-panel>
						<?php if ( ! empty( $posts ) ) : ?>
							<ol class="jw-home-category-post-list" role="list">
								<?php foreach ( $posts as $post_id ) : ?>
									<?php
									$post = get_post( $post_id );
									if ( ! $post ) {
										continue;
									}
									$title = wp_strip_all_tags( get_the_title( $post_id ) );
									?>
									<li class="jw-home-category-post-item">
										<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="jw-home-category-post-link">
											<span class="jw-home-category-post-title"><?php echo esc_html( $title ); ?></span>
										</a>
									</li>
								<?php endforeach; ?>
							</ol>
							<div class="jw-home-category-panel-footer">
								<a href="<?php echo esc_url( $panel['url'] ); ?>" class="btn-tonal">
									<?php esc_html_e( 'View all', 'jagawarta' ); ?>
								</a>
							</div>
						<?php else : ?>
							<p class="text-body-medium text-on-surface-variant"><?php esc_html_e( 'No posts found in this category.', 'jagawarta' ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
