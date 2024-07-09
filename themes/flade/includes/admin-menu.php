<?php

namespace fladeTheme\AdminMenu;

function start() {
	$callback = function ( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	// Extend admin menu
	add_action( 'admin_menu', $callback( 'admin_menu' ) );

	// Register ajax
	add_action( 'wp_ajax_flade_find_posts', $callback( 'flade_find_posts' ) );
}

/**
 * Register menu pages.
 *
 * @return void
 */
function admin_menu() {
	add_menu_page(
		__( 'FLADE', 'flade' ),
		__( 'FLADE', 'flade' ),
		'manage_options',
		'flade-settings',
		null,
		'',
		3
	);
	add_submenu_page(
		'flade-settings',
		__( 'FLADE Settings', 'flade' ),
		__( 'Settings', 'flade' ),
		'manage_options',
		'flade-settings',
		__NAMESPACE__ . '\\flade_settings_callback',
	);
	add_submenu_page(
		'flade-settings',
		__( 'All Unique Pages', 'flade' ),
		__( 'All Unique Pages', 'flade' ),
		'manage_options',
		'flade-unique-pages',
		__NAMESPACE__ . '\\flade_unique_pages_callback'
	);
	add_submenu_page(
		'flade-settings',
		__( 'Find Posts', 'flade' ),
		__( 'Find Posts', 'flade' ),
		'manage_options',
		'flade-find-posts',
		__NAMESPACE__ . '\\flade_find_posts_callback'
	);
}

/**
 * Display the "Settings" page.
 *
 * @return void
 */
function flade_settings_callback() {
	?>
	<div class="wrap">
		<div>
			<img
				src="<?php echo esc_url( FLADE_STATIC_URL . 'flade-banner.png' ); ?>"
				alt="FLADE"
				width="1920"
				height="600"
				style="display: block; width: 100%; height: auto;"
			>
		</div>

		<?php
		// ToDo
		/*
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		Some settings here
		*/
		?>
	</div>
	<?php
}

/**
 * Display the "All Unique Pages" admin page.
 *
 * @return void
 */
function flade_unique_pages_callback() {
	// Get all available page templates
	$available_templates = get_page_templates();
	// Add the default template at the beginning
	$available_templates = [ 'Default' => 'default' ] + $available_templates;

	// Get all available public post types
	$available_post_types = get_post_types( [ 'public' => true ], 'objects' );
	asort( $available_post_types );
	$excluded_post_type = [
		'attachment',
		'page',
	];

	// Get all available public taxonomies
	$available_taxonomies = get_taxonomies( [ 'public' => true ], 'objects' );
	asort( $available_taxonomies );
	$excluded_taxonomies = [
		'post_format',
	];

	// Get all available archive pages
	$available_archives = [];
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<h2>
			<?php esc_html_e( 'Page Templates', 'flade' ); ?>
		</h2>
		<table class="widefat fixed striped">
			<thead>
			<tr>
				<th>
					<?php esc_html_e( 'Template Name', 'flade' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Page Title', 'flade' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Permalink', 'flade' ); ?>
				</th>
			</tr>
			</thead>

			<tbody>
			<?php foreach ( $available_templates as $template_name => $template_slug ) : ?>
				<?php
				// Get one published post of the current type
				$post_from_type = get_posts(
					[
						'post_status'    => 'publish',
						'post_type'      => 'page',
						'posts_per_page' => 1,
						'meta_key'       => '_wp_page_template',
						'meta_value'     => $template_slug,
					]
				);

				// Default values
				$_post = null;

				if (
					is_array( $post_from_type )
					&& ! empty( $post_from_type )
					&& ! is_wp_error( $post_from_type )
				) {
					$_post = $post_from_type[0];
				}
				?>
				<tr>
					<td>
						<?php echo esc_html( $template_name ); ?>
					</td>
					<td>
						<?php
						if ( $_post ) {
							?>
							<a href="<?php echo esc_url( get_edit_post_link( $_post ) ); ?>">
								<?php echo esc_html( $_post->post_title ); ?>
							</a>
							<?php
						} else {
							esc_html_e( 'Page not found.', 'flade' );
						}
						?>
					</td>
					<td>
						<?php if ( $_post ) : ?>
							<a href="<?php the_permalink( $_post ); ?>">
								<?php the_permalink( $_post ); ?>
							</a>
						<?php else : ?>
							<span>-</span>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<?php if ( $available_post_types ) : ?>
			<br>
			<h2>
				<?php esc_html_e( 'Post Types', 'flade' ); ?>
			</h2>
			<table class="widefat fixed striped">
				<thead>
				<tr>
					<th>
						<?php esc_html_e( 'Post Type Name', 'flade' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Post Title', 'flade' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Permalink', 'flade' ); ?>
					</th>
				</tr>
				</thead>

				<tbody>
				<?php foreach ( $available_post_types as $post_type ) : ?>
					<?php
					// Skip excluded
					if ( in_array( $post_type->name, $excluded_post_type, true ) ) {
						continue;
					}

					// Get one published post of the current type
					$post_from_type = get_posts(
						[
							'post_status'    => 'publish',
							'post_type'      => $post_type->name,
							'posts_per_page' => 1,
						]
					);

					// Default values
					$_post = null;

					if (
						is_array( $post_from_type )
						&& ! empty( $post_from_type )
						&& ! is_wp_error( $post_from_type )
					) {
						$_post = $post_from_type[0];
					}

					$edit_post_type_link = 'edit.php?post_type=' . $post_type->name;

					// Get archive page
					$archive_link = get_post_type_archive_link( $post_type->name );

					// Don't save homepage as an archive (in case of "Posts page" isn't selected)
					if ( $archive_link === get_home_url() ) {
						$archive_link = '';
					}

					// Save to the archive pages list
					if ( $archive_link ) {
						$available_archives[ $post_type->name ] = [
							'title'     => $post_type->labels->name ?? $post_type->name,
							'edit_link' => $edit_post_type_link,
							'view_link' => $archive_link,
						];
					}
					?>
					<tr>
						<td>
							<a href="<?php echo esc_url( $edit_post_type_link ); ?>">
								<?php echo esc_html( $post_type->labels->singular_name ?? $post_type->name ); ?>
							</a>
						</td>
						<td>
							<?php
							if ( $_post ) {
								?>
								<a href="<?php echo esc_url( get_edit_post_link( $_post ) ); ?>">
									<?php echo esc_html( $_post->post_title ); ?>
								</a>
								<?php
							} else {
								esc_html_e( 'Post not found.', 'flade' );
							}
							?>
						</td>
						<td>
							<?php if ( $_post ) : ?>
								<a href="<?php the_permalink( $_post ); ?>">
									<?php the_permalink( $_post ); ?>
								</a>
							<?php else : ?>
								<span>-</span>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<?php if ( $available_taxonomies ) : ?>
			<br>
			<h2>
				<?php esc_html_e( 'Taxonomies', 'flade' ); ?>
			</h2>
			<table class="widefat fixed striped">
				<thead>
				<tr>
					<th>
						<?php esc_html_e( 'Taxonomy Name', 'flade' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Term Title', 'flade' ); ?>
					</th>
					<th>
						<?php esc_html_e( 'Permalink', 'flade' ); ?>
					</th>
				</tr>
				</thead>

				<tbody>
				<?php foreach ( $available_taxonomies as $taxonomy ) : ?>
					<?php
					// Skip excluded
					if ( in_array( $taxonomy->name, $excluded_taxonomies, true ) ) {
						continue;
					}

					// Get one term of the current taxonomy
					$term_from_tax = get_terms(
						[
							'hierarchical' => false,
							'hide_empty'   => 0,
							'number'       => 1,
							'taxonomy'     => $taxonomy->name,
						]
					);

					// Default values
					$term = null;

					if (
						is_array( $term_from_tax )
						&& ! empty( $term_from_tax )
						&& ! is_wp_error( $term_from_tax )
					) {
						$term = $term_from_tax[0];
					}
					?>
					<tr>
						<td>
							<a href="<?php echo esc_url( 'edit-tags.php?taxonomy=' . $taxonomy->name . '&post_type=' . ( $taxonomy->object_type[0] ?? '' ) ); ?>">
								<?php echo esc_html( $taxonomy->labels->singular_name ?? $taxonomy->name ); ?>
							</a>
						</td>
						<td>
							<?php
							if ( $term ) {
								?>
								<a href="<?php echo esc_url( get_edit_term_link( $term ) ); ?>">
									<?php echo esc_html( $term->name ); ?>
								</a>
								<?php
							} else {
								esc_html_e( 'Term not found.', 'flade' );
							}
							?>
						</td>
						<td>
							<?php if ( $term ) : ?>
								<a href="<?php echo esc_url( get_term_link( $term ) ); ?>">
									<?php echo esc_html( get_term_link( $term ) ); ?>
								</a>
							<?php else : ?>
								<span>-</span>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<?php if ( $available_archives ) : ?>
			<br>
			<h2>
				<?php esc_html_e( 'Archives', 'flade' ); ?>
			</h2>
			<table class="widefat fixed striped">
				<thead>
				<tr>
					<th>
						<?php esc_html_e( 'Archive For', 'flade' ); ?>
					</th>
					<th>
					</th>
					<th>
						<?php esc_html_e( 'Permalink', 'flade' ); ?>
					</th>
				</tr>
				</thead>

				<tbody>
				<?php foreach ( $available_archives as $archive ) : ?>
					<tr>
						<td>
							<a href="<?php echo esc_url( $archive['edit_link'] ); ?>">
								<?php echo esc_html( $archive['title'] ); ?>
							</a>
						</td>
						<td>
						</td>
						<td>
							<a href="<?php echo esc_url( $archive['view_link'] ); ?>">
								<?php echo esc_html( $archive['view_link'] ); ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Display the "Find Posts" page.
 *
 * @return void
 */
function flade_find_posts_callback() {
	global $current_user;

	//phpcs:disable WordPress.Security.NonceVerification

	// Get previous params on page reload, set defaults
	$order                 = ! empty( $_GET['order'] ) ? strtolower( sanitize_text_field( $_GET['order'] ) ) : 'desc';
	$order_by              = ! empty( $_GET['order_by'] ) ? sanitize_text_field( $_GET['order_by'] ) : '';
	$search_by_value       = ! empty( $_GET['flade_search_by'] ) ? sanitize_text_field( $_GET['flade_search_by'] ) : 'post_title';
	$search_value          = ! empty( $_GET['flade_search_value'] ) ? sanitize_text_field( $_GET['flade_search_value'] ) : '';
	$search_post_statuses  = ! empty( $_GET['flade_post_status'] ) ? explode( ',', sanitize_text_field( $_GET['flade_post_status'] ) ) : [];
	$search_per_page_value = ! empty( $_GET['flade_per_page'] )
		? sanitize_text_field( $_GET['flade_per_page'] )
		: ( get_user_meta( $current_user->ID, 'flade_find_posts_per_page', true ) ?: '20' ); //phpcs:ignore

	//phpcs:enable WordPress.Security.NonceVerification
	?>
	<div class="flade-find-posts wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<br>
		<form class="flade-find-posts__form js-flade-find-posts-form" method="post">
			<div class="flade-find-posts__actions">
				<div class="flade-find-posts__settings">
					<div class="flade-find-posts__searchbar">
						<label class="flade-find-posts__label">
							<span><?php esc_html_e( 'Search by:', 'flade' ); ?></span>
							&nbsp;
							<select class="js-flade-find-posts-input" name="flade_search_by">
								<?php
								$search_by_options = [
									'post_title'   => __( 'Title', 'flade' ),
									'post_name'    => __( 'Slug', 'flade' ),
									'ID'           => __( 'ID', 'flade' ),
									'post_content' => __( 'Content', 'flade' ),
								]
								?>
								<?php foreach ( $search_by_options as $option_value => $option_title ) : ?>
									<option
										value="<?php echo esc_attr( $option_value ); ?>"
										<?php selected( $option_value, $search_by_value ); ?>
									>
										<?php echo esc_html( $option_title ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</label>

						<label class="flade-find-posts__label regular-text">
							<input
								class="regular-text"
								type="text"
								name="flade_search_value"
								value="<?php echo esc_attr( $search_value ); ?>"
							>
						</label>
					</div>

					<fieldset class="flade-find-posts__fieldset flade-find-posts__by-post-type">
						<legend><?php esc_html_e( 'Search by post status', 'flade' ); ?></legend>

						<?php
						$post_status_fields = [
							'draft'   => __( 'Draft', 'flade' ),
							'publish' => __( 'Publish', 'flade' ),
							'trash'   => __( 'Trash', 'flade' ),
						];
						?>

						<?php foreach ( $post_status_fields as $field_value => $field_label ) : ?>
							<label>
								<input
									class="js-flade-find-posts-input"
									type="checkbox"
									name="flade_post_status"
									value="<?php echo esc_attr( $field_value ); ?>"
									<?php checked( in_array( $field_value, $search_post_statuses, true ) ); ?>
								>
								<?php echo esc_html( $field_label ); ?>
							</label>
						<?php endforeach; ?>
					</fieldset>
				</div>

				<label class="flade-find-posts__label">
					<span><?php esc_html_e( 'Results per page:', 'flade' ); ?></span>
					&nbsp;
					<select class="js-flade-find-posts-input" name="flade_per_page">
						<?php
						$search_per_page_options = [
							'15'  => '15',
							'20'  => '20',
							'50'  => '50',
							'100' => '100',
							'500' => '500',
						]
						?>
						<?php foreach ( $search_per_page_options as $option_value => $option_title ) : ?>
							<option
								value="<?php echo esc_attr( $option_value ); ?>"
								<?php selected( $option_value, $search_per_page_value ); ?>
							>
								<?php echo esc_html( $option_title ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</label>
			</div>

			<div class="flade-find-posts__results">
				<table class="widefat fixed striped">
					<thead class="js-flade-find-posts-thead">
					<tr>
						<?php
						$head_cols = [
							'ID'            => [
								'title'    => __( 'Post ID', 'flade' ),
								'sortable' => true,
							],
							'actions'       => [
								'title'    => __( 'Actions', 'flade' ),
								'sortable' => false,
							],
							'post_date'     => [
								'title'    => __( 'Publish Date', 'flade' ),
								'sortable' => true,
							],
							'post_modified' => [
								'title'    => __( 'Modified Date', 'flade' ),
								'sortable' => true,
							],
							'post_status'   => [
								'title'    => __( 'Post Status', 'flade' ),
								'sortable' => true,
							],
							'post_type'     => [
								'title'    => __( 'Post Type', 'flade' ),
								'sortable' => true,
							],
							'post_name'     => [
								'title'    => __( 'Post Slug', 'flade' ),
								'sortable' => true,
							],
							'post_title'    => [
								'title'    => __( 'Post Title', 'flade' ),
								'sortable' => true,
							],
						];
						?>
						<?php foreach ( $head_cols as $col_name => $col_data ) : ?>
							<?php
							$col_title   = $col_data['title'] ?? '';
							$is_sortable = $col_data['sortable'] ?? false;
							$is_sorted   = $is_sortable && $order_by === $col_name;

							// Prevent wrong order value
							if ( $order !== 'asc' && $order !== 'desc' ) {
								$order = 'desc';
							}

							// Combine all required classnames for thead cell
							$th_classes = strtolower( sanitize_html_class( "col--$col_name" ) );
							if ( $is_sortable ) {
								$th_classes .= ' sortable';

								if ( $is_sorted ) {
									$th_classes .= " sorted $order";
								} else {
									$th_classes .= ' desc';
								}
							}
							?>
							<th class="<?php echo esc_attr( $th_classes ); ?>">
								<?php if ( $is_sortable ) : ?>
									<a
										class="js-flade-find-posts-sort"
										href="#"
										data-order-by="<?php echo esc_attr( $col_name ); ?>"
									>
									<span>
										<?php echo esc_html( $col_title ); ?>
									</span>
										<span class="sorting-indicator"></span>
									</a>
								<?php else : ?>
									<?php echo esc_html( $col_title ); ?>
								<?php endif; ?>
							</th>
						<?php endforeach; ?>
					</tr>
					</thead>

					<tbody
						class="flade-find-posts__tbody js-flade-find-posts-results"
						data-empty-text="<?php esc_attr_e( 'Type something in the field to start searching.', 'flade' ); ?>"
					>
					<tr>
						<td colspan="<?php echo esc_attr( count( $head_cols ) ); ?>">
							<?php esc_html_e( 'Loading..', 'flade' ); ?>
						</td>
					</tr>
					</tbody>
				</table>

				<div class="tablenav bottom js-flade-find-posts-pagination"></div>
			</div>
		</form>
	</div>
	<?php
}

/**
 * Ajax callback for searched posts.
 *
 * @return void
 */
function flade_find_posts() {
	check_ajax_referer( 'flade-nonce', 'nonce' );

	$current_url     = isset( $_POST['current_url'] ) ? sanitize_text_field( $_POST['current_url'] ) : '';
	$page            = absint( isset( $_POST['paged'] ) ? sanitize_text_field( $_POST['paged'] ) : 1 );
	$per_page_string = isset( $_POST['per_page'] ) ? sanitize_text_field( $_POST['per_page'] ) : '';
	$per_page        = absint( $per_page_string );
	$post_status     = isset( $_POST['post_status'] ) ? sanitize_text_field( $_POST['post_status'] ) : '';
	$order           = isset( $_POST['order'] ) ? strtoupper( sanitize_text_field( $_POST['order'] ) ) : 'DESC';
	$order_by        = isset( $_POST['order_by'] ) ? sanitize_text_field( $_POST['order_by'] ) : '';
	$search_by       = isset( $_POST['search_by'] ) ? sanitize_text_field( $_POST['search_by'] ) : 'post_title';
	$search_val      = isset( $_POST['search_value'] ) ? sanitize_text_field( $_POST['search_value'] ) : '';
	$offset          = ( $page - 1 ) * $per_page;

	global $wpdb;

	// Save "per page" to user meta, so it can be taken by default next time
	if ( $per_page_string ) {
		global $current_user;
		update_user_meta( $current_user->ID, 'flade_find_posts_per_page', $per_page_string );
	}

	// Sanitize "order" value
	if ( strtolower( $order ) !== 'asc' && strtolower( $order ) !== 'desc' ) {
		$order = 'DESC';
	}

	// Sanitize "order by" value
	if (
		! in_array(
			strtolower( $order_by ),
			[
				'id',
				'post_date',
				'post_modified',
				'post_status',
				'post_type',
				'post_name',
				'post_title',
			],
			true
		)
	) {
		$order_by = '';
	}

	// Generate query partials
	$q_post_status = $post_status
		? " AND post_status IN ('" . implode( "','", explode( ',', $post_status ) ) . "')"
		: " AND post_status NOT IN ('auto-draft')";
	$q_order_by    = $order_by ? " ORDER BY `$order_by` $order" : '';
	$q_limit       = $per_page ? ' LIMIT ' . $per_page : '';
	$q_offset      = $offset ? ' OFFSET ' . $offset : '';

	//phpcs:disable
	$results = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT
    			ID,
    			post_date,
    			post_modified,
    			post_status,
    			post_type,
    			post_name,
    			post_title
			FROM $wpdb->posts as p
            WHERE %i LIKE '%s' 
            AND post_type NOT IN ('attachment','customize_changeset','revision')
            $q_post_status
			$q_order_by
			$q_limit
			$q_offset",
			$search_by,
			'%' . $wpdb->esc_like( $search_val ) . '%'
		)
	);

	$total_items = (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*)
			FROM $wpdb->posts 
            WHERE %i LIKE '%s' 
            AND post_type NOT IN ('attachment','customize_changeset','revision')
            $q_post_status",
			$search_by,
			'%' . $wpdb->esc_like( $search_val ) . '%'
		)
	);
	//phpcs:enable

	ob_start();

	if ( is_array( $results ) && ! empty( $results ) ) {
		foreach ( $results as $result ) {
			$post_type   = $result->post_type ?? 'page';
			$post_status = $result->post_status ?? '';
			$post_id     = $result->ID ?? 0;
			$is_trashed  = $post_status === 'trash';
			$is_cf7      = $post_type === 'wpcf7_contact_form';

			echo '<tr>';
			foreach ( $result as $key => $value ) {
				?>
				<td>
					<?php echo esc_html( $value ); ?>
				</td>

				<?php // Add "Actions" column after the "Post ID" column ?>
				<?php if ( $key === 'ID' ) : ?>
					<?php
					$view_link    = is_post_publicly_viewable( $post_id ) && ! $is_trashed ? get_permalink( $post_id ) : '';
					$preview_link = $is_trashed ? '' : get_preview_post_link( $post_id );
					$edit_link    = $is_trashed ? '' : get_edit_post_link( $post_id );
					$trashed_link = $is_trashed ? "edit.php?post_type=$post_type&post_status=trash" : '';

					if ( $is_cf7 ) {
						$edit_link = "admin.php?page=wpcf7&post=$post_id&action=edit";
					}
					?>
					<td>
						<?php if ( $view_link ) : ?>
							<a href="<?php echo esc_url( $view_link ); ?>" target="_blank">
								<?php esc_html_e( 'View', 'flade' ); ?>
							</a>
						<?php elseif ( $preview_link ) : ?>
							<a href="<?php echo esc_url( $preview_link ); ?>" target="_blank">
								<?php esc_html_e( 'Preview', 'flade' ); ?>
							</a>
						<?php endif; ?>

						<?php if ( ( $view_link || $preview_link ) && ( $edit_link || $trashed_link ) ) : ?>
							<span> | </span>
						<?php endif; ?>

						<?php if ( $edit_link ) : ?>
							<a href="<?php echo esc_url( $edit_link ); ?>" target="_blank">
								<?php esc_html_e( 'Edit', 'flade' ); ?>
							</a>
						<?php elseif ( $trashed_link ) : ?>
							<a href="<?php echo esc_url( $trashed_link ); ?>" target="_blank">
								<?php esc_html_e( 'Go to trashed', 'flade' ); ?>
							</a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

				<?php
			}
			echo '</tr>';
		}
	} else {
		?>
		<tr>
			<td colspan="8">
				<?php esc_html_e( 'No posts found.', 'flade' ); ?>
			</td>
		</tr>
		<?php
	}
	$table_html = ob_get_clean();

	// Pagination build
	$pagination_html = get_table_pagination(
		compact(
			'current_url',
			'page',
			'per_page',
			'total_items'
		)
	);

	$response = [
		'table_markup'      => $table_html,
		'pagination_markup' => $pagination_html,
	];

	wp_send_json_success( $response );
}

/**
 * Get WordPress-like pagination markup for a table.
 *
 * @param array $args
 *
 * @return false|string
 */
function get_table_pagination( array $args = [] ) {
	$current_url = $args['current_url'] ?? '';
	$page        = (int) ( $args['page'] ?? 1 );
	$per_page    = (int) ( $args['per_page'] ?? 20 );
	$total_items = (int) ( $args['total_items'] ?? 0 );

	$current_url = remove_query_arg( wp_removable_query_args(), $current_url );
	$total_pages = (int) ( $per_page ? ceil( $total_items / $per_page ) : 1 );

	$disable_first = false;
	$disable_last  = false;
	$disable_prev  = false;
	$disable_next  = false;

	if ( 1 === $page ) {
		$disable_first = true;
		$disable_prev  = true;
	}
	if ( $total_pages <= $page ) {
		$disable_last = true;
		$disable_next = true;
	}

	$page_links = [];
	if ( $disable_first ) {
		$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&laquo;</span>';
	} else {
		$page_links[] = sprintf(
			"<a class='first-page button js-flade-find-posts-paging' href='%s' data-page='1'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
			esc_url( remove_query_arg( 'paged', $current_url ) ),
			/* translators: Hidden accessibility text. */
			__( 'First page' ),
			'&laquo;'
		);
	}

	if ( $disable_prev ) {
		$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&lsaquo;</span>';
	} else {
		$page_links[] = sprintf(
			"<a class='prev-page button js-flade-find-posts-paging' href='%s' data-page='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
			esc_url( add_query_arg( 'paged', max( 1, $page - 1 ), $current_url ) ),
			esc_attr( max( 1, $page - 1 ) ),
			/* translators: Hidden accessibility text. */
			__( 'Previous page' ),
			'&lsaquo;'
		);
	}

	$html_current_page = sprintf(
		"%s<input class='current-page' type='number' name='flade_paged' value='%s' size='%d' min='1' max='%d' aria-describedby='table-paging'/>",
		'<label for="current-page-selector" class="screen-reader-text">' .
		/* translators: Hidden accessibility text. */
		__( 'Current Page' ) .
		'</label>',
		$page,
		strlen( $total_pages ),
		$total_pages
	);

	$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );

	$page_links[] = sprintf(
	    /* translators: 1: Current page, 2: Total pages. */
		_x( '%1$s of %2$s', 'paging' ),
		$html_current_page,
		$html_total_pages
	);

	if ( $disable_next ) {
		$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
	} else {
		$page_links[] = sprintf(
			"<a class='next-page button js-flade-find-posts-paging' href='%s' data-page='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
			esc_url( add_query_arg( 'paged', min( $total_pages, $page + 1 ), $current_url ) ),
			esc_attr( min( $total_pages, $page + 1 ) ),
			/* translators: Hidden accessibility text. */
			__( 'Next page' ),
			'&rsaquo;'
		);
	}

	if ( $disable_last ) {
		$page_links[] = '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&raquo;</span>';
	} else {
		$page_links[] = sprintf(
			"<a class='last-page button js-flade-find-posts-paging' href='%s' data-page='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
			esc_url( add_query_arg( 'paged', $total_pages, $current_url ) ),
			esc_attr( $total_pages ),
			/* translators: Hidden accessibility text. */
			__( 'Last page' ),
			'&raquo;'
		);
	}

	if ( $total_pages ) {
		$page_class = $total_pages < 2 ? 'one-page' : '';
	} else {
		$page_class = 'no-pages';
	}

	ob_start();
	?>
	<div class="tablenav-pages <?php echo esc_attr( $page_class ); ?>">
		<div class="displaying-num">
			<?php
			echo wp_kses_post(
				sprintf(
				    /* translators: %s: Number of items. */
					_n( '%s item', '%s items', $total_items, 'flade' ),
					number_format_i18n( $total_items )
				)
			);
			?>
		</div>
		<div class="pagination-links">
			<?php echo implode( "\n", $page_links ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
