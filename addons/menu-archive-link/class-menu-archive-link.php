<?php

class Menu_Archive_Link {

	public static function load() {
		add_action( 'admin_init', array( __CLASS__, 'add_meta_box' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'metabox_script' ) );
		add_action( 'wp_ajax_menu-archive-links', array( __CLASS__, 'ajax_add_post_type' ) );
		add_filter( 'wp_setup_nav_menu_item', array( __CLASS__, 'setup_archive_item' ) );
		add_filter( 'wp_nav_menu_objects', array( __CLASS__, 'maybe_make_current' ) );
	}

	public static function add_meta_box() {
		add_meta_box(
			'post-type-archives',
			'Типы записей',
			array( __CLASS__, 'metabox' ),
			'nav-menus',
			'side',
			'low'
		);
	}

	public static function metabox() {
		global $nav_menu_selected_id;

		$post_types = get_post_types( array(
			'public'   => true,
			'_builtin' => true
		), 'object' );

		unset($post_types['page']);
		unset($post_types['attachment']);

		?>

        <!— Post type checkbox list —>
        <ul id="post-type-archive-checklist">
			<?php foreach ( $post_types as $type ): ?>
                <li>
                    <label>
                        <input type="checkbox" value="<?php echo esc_attr( $type->name ); ?>"/>
						<?php echo esc_attr( $type->labels->name ); ?>
                    </label>
                </li>
			<?php endforeach; ?>
        </ul><!— /#post-type-archive-checklist —>

        <!— ‘Add to Menu’ button —>
        <p class="button-controls">
        <span class="add-to-menu">
            <input type="submit"
                   id="submit-post-type-archives"
                   class="button submit-add-to-menu right"
                   value="Добавить в меню"
                <?php disabled( $nav_menu_selected_id, 0 ); ?>>
        </span>
        </p>
		<?php
	}

	public static function metabox_script( $hook ) {
		if ( 'nav-menus.php' != $hook ) {
			return;
		}

		wp_enqueue_script( 'menu-archive-links_metabox', thisisurl( dirname(__FILE__) ) . '/menu-archive-link-metabox.js', array( 'jquery' ), ZERO_VERSION );

		wp_localize_script(
			'menu-archive-links_metabox',
			'MenuArchiveLink',
			array( 'nonce' => wp_create_nonce( 'menu-archive-links' ) )
		);
	}

	public static function ajax_add_post_type() {

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			die( '-1' );
		}

		check_ajax_referer( 'menu-archive-links', 'posttypearchive_nonce' );

		require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

		if ( empty( $_POST['post_types'] ) ) {
			exit;
		}

		$item_ids = array();
		foreach ( (array) $_POST['post_types'] as $post_type ) {
			$post_type_obj = get_post_type_object( $post_type );

			if ( ! $post_type_obj ) {
				continue;
			}

			$menu_item_data = array(
				'menu-item-title'  => esc_attr( $post_type_obj->labels->name ),
				'menu-item-type'   => 'post_type_archive',
				'menu-item-object' => esc_attr( $post_type ),
				'menu-item-url'    => get_post_type_archive_link( $post_type )
			);

			$item_ids[] = wp_update_nav_menu_item( 0, 0, $menu_item_data );
		}

		if ( is_wp_error( $item_ids ) ) {
			die( '-1' );
		}

		foreach ( (array) $item_ids as $menu_item_id ) {
			$menu_obj = get_post( $menu_item_id );
			if ( ! empty( $menu_obj->ID ) ) {
				$menu_obj        = wp_setup_nav_menu_item( $menu_obj );
				$menu_obj->label = $menu_obj->title;
				$menu_items[]    = $menu_obj;
			}
		}

		if ( ! empty( $menu_items ) ) {
			$args = array(
				'after'       => '',
				'before'      => '',
				'link_after'  => '',
				'link_before' => '',
				'walker'      => new Walker_Nav_Menu_Edit
			);
			echo walk_nav_menu_tree( $menu_items, 0, (object) $args );
		}

		exit;
	}

	public static function setup_archive_item( $menu_item ) {
		if ( $menu_item->type != 'post_type_archive' ) {
			return $menu_item;
		}

		$post_type      = $menu_item->object;
		$menu_item->url = get_post_type_archive_link( $post_type );

		return $menu_item;
	}

	public static function maybe_make_current( $items ) {
		foreach ( $items as $item ) {
			if ( 'post_type_archive' != $item->type ) {
				continue;
			}

			$post_type = $item->object;
			if ( ! is_post_type_archive( $post_type ) && ! is_singular( $post_type ) ) {
				continue;
			}

			$item->current   = true;
			$item->classes[] = 'current-menu-item';

			$_anc_id                  = (int) $item->db_id;
			$active_ancestor_item_ids = array();

			while ( ( $_anc_id = get_post_meta( $_anc_id, '_menu_item_menu_item_parent', true ) ) && ! in_array( $_anc_id, $active_ancestor_item_ids ) ) {
				$active_ancestor_item_ids[] = $_anc_id;
			}

			foreach ( $items as $key => $parent_item ) {
				$classes = (array) $parent_item->classes;

				if ( $parent_item->db_id == $item->menu_item_parent ) {
					$classes[]                          = 'current-menu-parent';
					$items[ $key ]->current_item_parent = true;
				}

				if ( in_array( intval( $parent_item->db_id ), $active_ancestor_item_ids ) ) {
					$classes[]                            = 'current-menu-ancestor';
					$items[ $key ]->current_item_ancestor = true;
				}

				$items[ $key ]->classes = array_unique( $classes );
			}
		}

		return $items;
	}
}

Menu_Archive_Link::load();


