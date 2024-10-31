<?php

class Inani_TimerPost extends WP_Widget
{
    protected $min = 150;
    protected $max = 400;
    protected $title = "Timer";

    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        $widget_ops = [
            'classname' => 'my_custom_timer_post',
            'description' => 'A widget to show Time reading for specific post',
        ];
        parent::__construct('my_custom_timer_post', 'Timer Post', $widget_ops);
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {

        if(is_single() && $this->timerWidgetAllowed(get_the_ID()))
        {
            extract($args);
            extract($instance);
            echo $before_widget;
                echo $before_title;
                    echo $title;
                echo $after_title;
                echo $this->read_time(get_the_title(), get_the_content(), $count_words);
            echo $after_widget;
        }
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     * @return string|void
     */
    public function form($instance)
    {
        $instance = $this->update($instance, []);
        extract($instance);
        ?>
        <p>
            <label for="<?php echo ($titleID =$this->get_field_id('title')); ?>">Title: </label>
            <input
                type="text"
                class="widefat"
                id="<?php echo $titleID; ?>"
                name="<?php echo $this->get_field_name('title'); ?>"
                value="<?php echo !isset($title)?"": esc_attr($title); ?>"
            />
        </p>
        <p>
            <label for="<?php echo ($countWordsID =$this->get_field_id('count_words')); ?>">How many words per minute: </label>
            <input
                type="number"
                min="<?php echo $this->min; ?>"
                max="<?php echo $this->max; ?>"
                step="1"
                id="<?php echo $countWordsID; ?>"
                name="<?php echo $this->get_field_name('count_words'); ?>"
                value="<?php echo !isset($count_words)? 200 : esc_attr($count_words); ?>"
            />
        </p>
        <?php
    }

    /**
     * Calculate The time needed to read the post
     *
     * @param $title
     * @param $content
     * @param $count_words
     * @return string
     */
    private function read_time($title, $content, $count_words){
        $words = str_word_count(strip_tags($content));
        $words  = $words + str_word_count(strip_tags($title));
        $min = ceil($words / $count_words);
        return $min . ' min read';
    }


    /**
     * Update the form fields
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $new_instance['count_words'] = $new_instance['count_words'] > $this->max? $this->max : $new_instance['count_words'];
        $new_instance['count_words'] = $new_instance['count_words'] < $this->min? $this->min : $new_instance['count_words'];
        $new_instance['title'] = empty($new_instance['title'])? $this->title : $new_instance['title'];
        return $new_instance;
    }

    /**
     * Check if its allowed to display the widget
     *
     * @param $post_id
     * @return bool
     */
    private function timerWidgetAllowed($post_id)
    {
        return (int)get_post_meta($post_id, 'inani_show_timer', true) == 1;
    }
}

// Register TimerPost widget
function post_timer_estimator_register_timer_post_inani()
{
    register_widget('Inani_TimerPost');
}

add_action('widgets_init', 'post_timer_estimator_register_timer_post_inani');