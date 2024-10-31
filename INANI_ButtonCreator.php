<?php


class INANI_ButtonCreator
{

    /**
     * Execute!
     *
     */
    public function run()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_box']);
        add_action('save_post', [$this, 'save_post_timer_option']);
    }

    /**
     * Add a new option in the post
     *
     */
    public function add_meta_box()
    {
        add_meta_box(
            'custom-box-for-check-timer',
            'Timer option',
            [$this, 'post_timer_option_display'],
            'post',
            'side',
            'high'
        );
    }

    /**
     * The block to render
     *
     */
    public function post_timer_option_display()
    {
        require_once(
            plugin_dir_path(__FILE__). 'views/checkbox.php'
        );
    }

    /**
     * Callback function for saving custom field
     *
     * @param $post_id
     */
    public function save_post_timer_option($post_id)
    {
        $post_type = get_post_type($post_id);

        // Exclude not allowed Post types
        if($post_type != 'revision' && $post_type != 'post')
            return ;

        if(! $this->user_can_save($post_id)){
            return ;
        }

        $post_timer = $_POST['show_timer'];
        $affect = !is_null($post_timer)? 1 : 0;
        update_post_meta($post_id, 'inani_show_timer', $affect);
    }

    /**
     * Check if the user can save
     *
     * @param $post_id
     * @return bool
     */
    public function user_can_save($post_id)
    {
        $autosave = wp_is_post_autosave($post_id);
        $revision = wp_is_post_revision($post_id);
        return !($autosave || $revision);
    }
}

(new INANI_ButtonCreator())->run();