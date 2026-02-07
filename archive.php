<?php
/**
 * Archive (category, tag, date, author).
 *
 * @package JagaWarta
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$obj      = get_queried_object();
$cat_name = is_category() ? single_cat_title( '', false ) : get_the_archive_title();
set_query_var( 'breadcrumb_context', 'archive' );
?>

<main id="main" class="site-main layout-content max-w-page-max flex flex-col text-on-surface pt-spacing-12">
	<?php get_template_part( 'template-parts/breadcrumb' ); ?>
	<header class="flex flex-col mt-spacing-4 max-w-content-max pt-0 pb-spacing-10">
		<div class="mb-spacing-2">
			<?php the_archive_title( '<h1 class="text-display-small md:text-display-medium font-bold tracking-tight text-on-surface">', '</h1>' ); ?>
		</div>
		
		<?php if ( get_the_archive_description() ) : ?>
			<div class="text-body-large text-on-surface-variant max-w-content-max">
				<?php the_archive_description(); ?>
			</div>
		<?php else : ?>
			<p class="text-body-large text-on-surface-variant max-w-content-max">
				<?php printf( esc_html__( 'Latest news and updates about %s at JagaWarta.', 'jagawarta' ), esc_html( $cat_name ) ); ?>
			</p>
		<?php endif; ?>
	</header>

	<?php if ( have_posts() ) : ?>
		
		<?php
		if ( have_posts() ) {
			the_post(); // Hero
			?>
			<div class="mb-spacing-10">
				<?php get_template_part( 'template-parts/hero/hero-category' ); ?>
			</div>
			<?php
		}


		$more_ids = array();
		while ( count( $more_ids ) < 3 && have_posts() ) {
			the_post();
			$more_ids[] = get_the_ID();
		}
		if ( ! empty( $more_ids ) ) {
			get_template_part( 'template-parts/category-3up', null, array( 'post_ids' => $more_ids ) );
		}
		?>

		<div class="flex items-center gap-spacing-4 pt-spacing-16 pb-spacing-6">
			<h2 class="text-headline-medium md:text-headline-large font-bold text-on-surface">
				<?php printf( esc_html__( 'Latest %s news', 'jagawarta' ), esc_html( $cat_name ) ); ?>
			</h2>
			<div class="h-px bg-outline-variant flex-grow"></div>
		</div>

		<ul id="ajax-post-container" class="grid gap-spacing-4 list-none m-0 p-0 sm:grid-cols-2 lg:grid-cols-3">
			<?php
			while ( have_posts() ) {
				the_post();
				?>
				<li class="flex h-full"><?php get_template_part( 'template-parts/cards/card-categories' ); ?></li>
				<?php
			}
			?>
		</ul>

		<?php
		$load_more_html = '<span class="text-primary">Load more news</span>';
		if ( get_next_posts_link() ) :
			?>
			<div class="flex justify-center pt-spacing-16 pb-spacing-12">
				<button id="load-more-btn"
						type="button"
						class="group inline-flex items-center justify-center px-spacing-6 py-spacing-3 text-label-large font-medium text-on-surface bg-surface border border-outline-variant rounded-full transition-all duration-short ease-standard hover:bg-surface-high hover:shadow-elevation-2 hover:border-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 active:scale-95"
						data-page="2"
						data-archive="<?php echo esc_attr( wp_json_encode( $wp_query->query_vars ) ); ?>"
						data-max="<?php echo esc_attr( $wp_query->max_num_pages ); ?>"
						data-initial-html="<?php echo esc_attr( $load_more_html ); ?>"
						aria-live="polite">
					<span class="text-on-surface group-hover:text-primary transition-colors duration-short">Load more news</span>
				</button>
			</div>
		<?php endif; ?>

	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</main>

<?php
get_footer();
