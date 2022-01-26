<?php

function nav_menu_metabox( $object ){

    global $nav_menu_selected_id;
    $nm      = __( 'Cart', 'my');
    $login   = __( 'Login / Logout', 'my');
    $profile = __( 'My office', 'my');
    $elems   = array(
        '#cart#'        => $nm,
        '#loginlogout#' => $login,
        '#account#'     => $profile
    );

    class addMyCustomLinks {
        public $db_id = 0;
        public $object = 'add_menu_item';
        public $object_id;
        public $menu_item_parent = 0;
        public $type = 'custom';
        public $title;
        public $url;
        public $target = '';
        public $attr_title = '';
        public $classes = array();
        public $xfn = '';
    }

    $elems_obj = array();
    foreach ( $elems as $value => $title ) {
        $elems_obj[$title] = new addMyCustomLinks();
        $obj = &$elems_obj[$title];
        $obj->object_id = esc_attr( $value );
        if(empty($obj->title)) $obj->title = esc_attr( $title );
        $obj->label = esc_attr( $title );
        $obj->url = esc_attr( $value );
    }

    $walker = new Walker_Nav_Menu_Checklist();
    ?>
    <div id="my" class="mydiv">
        <div id="tabs-panel-my-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
            <ul id="mychecklist" class="categorychecklist form-no-clear">
                <?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $elems_obj ), 0, (object)array( 'walker' => $walker ) ) ?>
            </ul>
        </div>
        <span class="list-controls hide-if-no-js">
		<a href="javascript:void(0);" class="help" onclick="jQuery( '#my-help' ).toggle();"><?php _e( 'Help', 'my') ?></a>
		<span class="hide-if-js" id="my-help">
			<p>
				<a name="my-help"></a>
				<?php echo __('For work plugin, please do not change the value of hashtags', 'my'); ?>
			</p>
		</span>
	</span>
        <p class="button-controls">
		<span class="add-to-menu">
			<input type="submit"<?php disabled( $nav_menu_selected_id, 0 ) ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add', 'my') ?>" name="add-my-menu-item" id="submit-my" />
			<span class="spinner"></span>
		</span>
        </p>
    </div>
    <?php
}

function add_nav_menu_metabox(){
    add_meta_box( 'add-custom-links', __( 'My Links', 'my'), 'nav_menu_metabox', 'nav-menus', 'side', 'default' );
}
//add_action( 'admin_head-nav-menus.php', 'add_nav_menu_metabox' );
