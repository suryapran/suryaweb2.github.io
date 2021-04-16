<?php

/**
 * Class wp_megamenu
 */
if ( ! class_exists('Cool_Megamenu_Widgets')) {

    class Cool_Megamenu_Widgets{

        /**
         * @return Cool_Megamenu_Widgets
         */
        public static function init(){
            $return = new self();
            return $return;
        }

        /**
         * Cool_Megamenu_Widgets constructor.
         */
        public function __construct(){
            add_action('init', array($this, 'crmm_register_sidebar'));           
        }

        /**
         * Register sidebar to call it smartly
         */
        public function crmm_register_sidebar() {
            register_sidebar(
                array(
                    'id' => 'crmm',
                    'name' => __("Cool Megamenu Widgets", 'cool-megamenu'),
                    'description'   => __("This is for programmatically add widget to sidebar. It will be not show in the menu if you add this directly from here. Insted of you have to add any item from the menu ", 'cool-megamenu')
                )
            );
        }

        /**
         * @param $widget_id
         *
         * Generate Widget form.
         *
         * @since v.1.0
         */
        public function show_crmm_widget_form( $widget_id ) {
            global $wp_registered_widget_controls;

            $id_base = $this->crmm_get_id_base_for_widget_id( $widget_id );
            $control = $wp_registered_widget_controls[$widget_id];
            $nonce = wp_create_nonce('megamenu_save_widget_' . $widget_id);
            ?>

            <form method='post'  class="crmm_widget_save_form">
                <input type="hidden" name="widget-id" class="widget-id" value="<?php echo $widget_id ?>" />
                <input type='hidden' name='id_base'   class="id_base" value='<?php echo $id_base; ?>' />
                <input type='hidden' name='widget_id' value='<?php echo $widget_id ?>' />
                <input type='hidden' name='_wpnonce'  value='<?php echo $nonce ?>' />
                <div class='widget-content'>
                    <?php
                    if ( is_callable( $control['callback'] ) ) {
                        call_user_func_array( $control['callback'], $control['params'] );
                    }
                    ?>

                    <div class='widget-controls'>
                        <a class='delete' href='#delete'><?php _e('Delete', 'cool-megamenu'); ?></a> |
                        <a class='close' href='#close'><?php _e('Close', 'cool-megamenu'); ?></a>
                    </div>

                    <?php
                    submit_button( __( 'Save' ), 'button-primary alignright', 'savewidget', false );
                    ?>
                    <div class="clear"></div>
                </div>
            </form>
            <?php
        }

        /**
         * get all registere available widget
         */
        public function get_all_registered_widget(){
            global $wp_widget_factory;

            $widgets = array();
            foreach( $wp_widget_factory->widgets as $widget ) {
                $widgets[] = array(
                    'name' => $widget->name,
                    'id_base' => $widget->id_base
                );
            }
            return $widgets;
        }

        /**
         * @param $id
         * @return string
         *
         * Show a widget output in the menu
         */
        public function show_widget( $id ) {
            global $wp_registered_widgets;
            $params = array_merge(
                array( array_merge( array( 'widget_id' => $id, 'widget_name' => $wp_registered_widgets[$id]['name'] ) ) ),
                (array) $wp_registered_widgets[$id]['params']
            );
            $params[0]['before_title'] = apply_filters( "crmm_before_widget_title", '<h4 class="wpmm-item-title">',
                $wp_registered_widgets[$id] );
            $params[0]['after_title'] = apply_filters( "crmm_after_widget_title", '</h4>', $wp_registered_widgets[$id] );
            $params[0]['before_widget'] = apply_filters( "crmm_before_widget", "", $wp_registered_widgets[$id] );
            $params[0]['after_widget'] = apply_filters( "crmm_after_widget", "", $wp_registered_widgets[$id] );

            $callback = $wp_registered_widgets[$id]['callback'];

            if ( is_callable( $callback ) ) {
                ob_start();
                call_user_func_array( $callback, $params );
                return ob_get_clean();
            }
        }

        /**
         * @return bool | array
         *
         * get wp megamenu sidebar widgets
         */
        public function get_sidebar_widgets() {
            $widget = wp_get_sidebars_widgets();
            if ( ! isset( $widget[ 'crmm'] ) ) {
                return false;
            }
            return $widget[ 'crmm' ];
        }


        /**
         * @param $widgets_array
         *
         * Set widgets to wp megamenu sidebar
         */
        private function set_sidebar_widgets( $widgets_array ) {
            $widgets = wp_get_sidebars_widgets();
            $widgets[ 'crmm' ] = $widgets_array;
            wp_set_sidebars_widgets( $widgets );
        }

        /**
         * @param $new_widget_id
         * @return mixed
         *
         */
        private function add_widget_to_crmm_sidebar( $new_widget_id ) {
            $new_widgets = $this->get_sidebar_widgets();
            $new_widgets[] = $new_widget_id;
            $this->set_sidebar_widgets($new_widgets);
            return $new_widget_id;
        }

        /**
         * @param $widget_id
         * @return bool
         *
         * Get base widget id
         */
        public function crmm_get_id_base_for_widget_id( $widget_id ) {
            global $wp_registered_widget_controls;

            if ( ! isset( $wp_registered_widget_controls[ $widget_id ] ) ) {
                return false;
            }
            $control = $wp_registered_widget_controls[ $widget_id ];
            $id_base = isset( $control['id_base'] ) ? $control['id_base'] : $control['id'];
            return $id_base;
        }

        /**
         * @param $widget_id
         * @return bool|string
         */
        public function crmm_get_widget_class_by_widget_id( $widget_id ) {
            global $wp_registered_widget_controls;

            if ( ! isset( $wp_registered_widget_controls[ $widget_id ] ) ) {
                return false;
            }
            $control = $wp_registered_widget_controls[ $widget_id ];

            $widget_class_name = get_class($control['callback'][0]);
            return $widget_class_name;
        }

        /**
         * @param $widget_id
         * @return bool|string
         */
        public function crmm_get_widget_name_by_widget_id( $widget_id ) {
            global $wp_registered_widget_controls;
            if ( ! isset( $wp_registered_widget_controls[ $widget_id ] ) ) {
                return false;
            }
            $control = $wp_registered_widget_controls[ $widget_id ];
            $name = isset( $control['name'] ) ? $control['name'] : '';
            return $name;
        }

        /**
         * Add widget or item
         */
        public function crmm_add_widget_to_item(){
            require_once( ABSPATH . 'wp-admin/includes/widgets.php' );

            $widget_base_id = sanitize_text_field($_POST['widget_id']);
            $menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);

            $next_id = next_widget_id_number( $widget_base_id );
            $widget_id = $widget_base_id.'-'.$next_id;

            $this->add_widget_to_crmm_sidebar($widget_id);

            //get new widget id
            $get_widget_option = get_option('widget_'.$widget_base_id);
            $get_widget_option[$next_id] = array();
            update_option('widget_'.$widget_base_id, $get_widget_option);

            //Settings in item post meta
            $widget_name = $this->crmm_get_widget_name_by_widget_id($widget_id);
            $widget_class = $this->crmm_get_widget_class_by_widget_id($widget_id);

            //$get_menu_settings = (array) get_post_meta($menu_item_id, 'crmm_layout', true);

            $get_layout = get_post_meta($menu_item_id, 'crmm_layout', true);

            //Setting item settings in the menu
            //$get_menu_settings['items'][] = array( 'item_type' => 'widget', 'widget_class' => $widget_class, 'widget_name' => $widget_name, 'widget_id' => $widget_id, 'options' => array() );
            $get_layout['layout'][0]['row'][0]['items'][] = array( 'item_type' => 'widget', 'widget_class' => $widget_class, 'widget_name' => $widget_name, 'widget_id' => $widget_id, 'options' => array() );

            update_post_meta($menu_item_id, 'crmm_layout', $get_layout);
            //update_post_meta($menu_item_id, 'crmm_layout', $get_menu_settings);

            //Send json success
            wp_send_json_success( array('message' => __('Wiedget added', 'cool-megamenu'), 'widget_id' => $widget_id) );
        }

        /**
         * Call item to panel
         */
        public function crmm_get_widget_to_item(){
            $widget_id = sanitize_text_field($_POST['widget_id']);
            $menu_item_id = (int) sanitize_text_field($_POST['menu_item_id']);

            $get_menu_settings = get_post_meta($menu_item_id, 'crmm_layout', true);
            if ( ! empty($get_menu_settings['items'])){
                foreach ($get_menu_settings['items'] as $key => $value){
                    if ($value['widget_id'] === $widget_id){
                        $this->widget_items($value['widget_id'], $get_menu_settings, $key);
                        die();
                    }
                }
            }
        }

        /**
         * @param $widget_id
         * @param $menu_item_id
         *
         *
         * Get widget item in item settings panel
         */
        public function widget_items($widget_id, $menu_item_id, $widget_key_id = 0){
            ?>
            <div id="widget-<?php echo $widget_id; ?>" class="widget"  data-item-key-id="<?php
            echo $widget_key_id; ?>">
                <div class="widget-top">

                    <div class="widget-title-action">
                        <button type="button" class="widget-action hide-if-no-js widget-form-open" aria-expanded="false">
                            <span class="screen-reader-text"><?php printf( __( 'Edit widget: %s' ), $this->crmm_get_widget_name_by_widget_id($widget_id) ); ?></span>
                            <span class="toggle-indicator" aria-hidden="true"></span>
                        </button>

                    </div>
                    <div class="widget-title">
                        <h3><?php echo $this->crmm_get_widget_name_by_widget_id($widget_id); ?><span class="in-widget-title"></span></h3>
                    </div>
                </div>

                <div class="widget-inner widget-inside">
                    <?php
                    $this->show_crmm_widget_form($widget_id); ?>
                </div>

            </div>
            <?php
        }

            


    }

    Cool_Megamenu_Widgets::init();

    if ( ! function_exists('Cool_Megamenu_Widgets')){
        function Cool_Megamenu_Widgets(){
            return new Cool_Megamenu_Widgets();
        }
    }
}

