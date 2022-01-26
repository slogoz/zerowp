<?php

class ZeroWP_Submenu
{
    /**
     * Hook in tabs.
     */
    public function __construct()
    {
        // Add menus.
//        add_action( 'admin_menu', array( $this, 'admin_menu' ), 9 );
//        add_action( 'admin_menu', array( $this, 'reports_menu' ), 20 );
//        add_action( 'admin_menu', array( $this, 'settings_menu' ), 50 );
//        add_action( 'admin_menu', array( $this, 'status_menu' ), 60 );

//        if ( apply_filters( 'woocommerce_show_addons_page', true ) ) {
//            add_action( 'admin_menu', array( $this, 'addons_menu' ), 70 );
//        }

//        add_action( 'admin_head', array( $this, 'menu_highlight' ) );
//        add_action( 'admin_head', array( $this, 'menu_order_count' ) );
//        add_filter( 'menu_order', array( $this, 'menu_order' ) );
//        add_filter( 'custom_menu_order', array( $this, 'custom_menu_order' ) );
//        add_filter( 'set-screen-option', array( $this, 'set_screen_option' ), 10, 3 );

        // Add endpoints custom URLs in Appearance > Menus > Pages.
        add_action('admin_head-nav-menus.php', array($this, 'add_nav_menu_meta_boxes'));

        // Admin bar menus.
//        if ( apply_filters( 'woocommerce_show_admin_bar_visit_store', true ) ) {
//            add_action( 'admin_bar_menu', array( $this, 'admin_bar_menus' ), 31 );
//        }

        // Handle saving settings earlier than load-{page} hook to avoid race conditions in conditional menus.
//        add_action( 'wp_loaded', array( $this, 'save_settings' ) );
    }

    public function add_nav_menu_meta_boxes()
    {
        add_meta_box('zwp_submenu_tax', 'Меню таксономии', array($this, 'select_taxonomy'), 'nav-menus', 'side', 'low');
    }

    public function select_taxonomy()
    {
        $taxonomies = [
            'one' => 'first',
            'two' => 'second ',
        ]

        ?>
        <div id="zwp-submenu-taxonomy">
            <div>
                <label class="menu-item-title">
                    Выберите таксономию:<br>
                    <select name="zwp-submenu-taxonomy">
                        <?php
                        $i = -1;
                        foreach ($taxonomies as $key => $value) : ?>
                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php
                            $i--;
                        endforeach; ?>
                    </select>
                    <!--                    <input type="checkbox" class="menu-item-checkbox"-->
                    <!--                           name="menu-item[--><?php //echo esc_attr($i);
                    ?><!--][menu-item-object-id]"-->
                    <!--                           value="--><?php //echo esc_attr($i);
                    ?><!--"/> --><?php //echo esc_html($value);
                    ?>
                </label>
                <input type="hidden" class="menu-item-type"
                       name="menu-item[<?php echo esc_attr($i);
                       ?>][menu-item-type]" value="custom"/>
                <input type="hidden" class="menu-item-title"
                       name="menu-item[<?php echo esc_attr($i);
                       ?>][menu-item-title]"
                       value="<?php echo esc_attr($value);
                       ?>"/>
                <input type="hidden" class="menu-item-url" name="menu-item[
                <?php echo esc_attr($i);
                ?>][menu-item-url]" value="#url"/>
                <input type="hidden" class="menu-item-classes"
                       name="menu-item[<?php echo esc_attr($i);
                       ?>][menu-item-classes]"/>
            </div>
            <p class="button-controls">
                <span class="add-to-menu">
					<button type="submit" class="button-secondary submit-add-to-menu right"
                            value="<?php echo 'Добавить в меню'; ?>"
                            name="add-post-type-menu-item"><?php echo 'Добавить в меню'; ?></button>
					<span class="spinner"></span>
				</span>
            </p>
        </div>
        <?php
    }
}
