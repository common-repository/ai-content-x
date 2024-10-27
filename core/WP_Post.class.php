<?php
if (!class_exists('Jodacame_WP_Post'))
{
    class Jodacame_WP_Post
    {
        private static $_instance = null;
        private $post_id = null;

        /** 
         * Constructor
         * @return void
         * @since 1.0.0
         */
        public static function instance($post_id = null)
        {
            if (is_null(self::$_instance))
            {
                self::$_instance = new self();
            }
            self::$_instance->post_id = $post_id;
            return self::$_instance;
        }

        /**
         * Retrieves all of the WordPress supported post statuses.
         * @return array List of post statuses.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/get_post_statuses/
         */

        public function statuses()
        {
            return get_post_statuses();
        }

        /**
         * Retrieves all of the WordPress supported post types.
         * @param array $args An array of arguments. See get_post_types() for information on accepted arguments.
         * @param string $output Optional. The type of output to return, either post type 'names' or 'objects'. Default 'names'.
         * @param string $operator Optional. The logical operation to perform. 'or' means only one element from the array needs to match; 'and' means all elements must match. The default is 'and'.
         * @return array List of post types.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/get_post_types/
         */
        public function types($args = array(), $output = 'names', $operator = 'and')
        {
            return get_post_types($args, $output, $operator);
        }

        /**
         * Retrieves all posts matching the given query.
         * @param array $args An array of arguments. See get_posts() for information on accepted arguments.
         * @return array List of posts.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/get_posts/
         */

        public function get($args = array())
        {


            if ($this->post_id !== null && count($args) == 0)
            {
                $post = get_post($this->post_id);
                return $post ? $post : null;
            }

            return get_posts($args);
        }

        /**
         * Retrieves the first post matching the given query.
         * @param array $args An array of arguments. See get_posts() for information on accepted arguments.
         * @return array List of posts.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/get_posts/
         */

        public function getOne($args = array())
        {
            $args['numberposts'] = 1;

            $posts = $this->get($args);
            if ($posts)
            {
                return $posts[0] ? $posts[0] : null;
            }
            return null;
        }

        /**
         * Retrieves the post's meta field for the given key.
         * @param int $post_id Post ID.
         * @param string $key Optional. The meta key to retrieve. By default, returns data for all keys. Default empty.
         * @param bool $single Optional. Whether to return a single value. Default false.
         * @return mixed Will be an array if $single is false. Will be value of meta data field if $single is true.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/get_post_meta/
         */

        public function getMeta($key = '', $single = false)
        {
            if (!$this->post_id) return null;

            return get_post_meta($this->post_id, $key, $single);
        }

        /**
         * Adds a meta data field to a post.
         * @param int $post_id Post ID.
         * @param string $key Metadata name.
         * @param mixed $value Metadata value.
         * @param bool $unique Optional, default is false. Whether the same key should not be added.
         * @return int|bool Meta ID if the key didn't exist, true on successful update, false on failure.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/add_post_meta/
         */

        public function setMeta($key, $value)
        {
            if (!$this->post_id) return null;

            return update_post_meta($this->post_id, $key, $value);
        }

        public function insert($args = array())
        {

            return wp_insert_post($args);
        }

        /**
         * Update status of a post.
         * @param int $post_id Post ID.
         * @param string $status The new post status.
         * @return int|WP_Error The value 0 or WP_Error on failure. The post ID on success.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/wp_update_post/
         */

        public function setStatus($status)
        {
            if (!$this->post_id) return null;

            return wp_update_post(array(
                'ID' => $this->post_id,
                'post_status' => $status
            ));
        }


        /**
         * Retrieves the post's thumbnail url
         * @param string $size Optional. Image size. Defaults to 'post-thumbnail'.
         * @param string|array $attr Optional. Query string or array of attributes.
         * @return string HTML img element or empty string on failure.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/get_the_post_thumbnail/
         */

        public function thumbnail($size = 'post-thumbnail')
        {
            if (!$this->post_id) return null;

            return get_the_post_thumbnail_url($this->post_id, $size);
        }

        /** 
         * Retrieves the post's edit link.
         * @param string $type Optional. The type of link to retrieve. Accepts 'edit' or 'view'. Default 'edit'.
         * @return string The edit link URL for the given post.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/get_edit_post_link/
         */

        public function link($type = 'edit')
        {
            if (!$this->post_id) return null;
            if ($type == 'edit')
            {
                return get_edit_post_link($this->post_id);
            }
            return get_permalink($this->post_id);
        }

        /**
         * Get the post type.
         * @return string The post type.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/get_post_type/
         */

        public function getType()
        {
            if (!$this->post_id)
            {
                $this->post_id = get_the_ID();
            }
            return get_post_type($this->post_id);
        }

        /**
         * Register new post type.
         * @param string $post_type Post type key, must not exceed 20 characters.
         * @param array $args 
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/register_post_type/
         */

        public function register_post_type($post_type, $args = array())
        {
            register_post_type($post_type, $args);
        }

        public function update($post)
        {
            if (is_array($post))
            {
                $post = (object) $post;
            }
            if (isset($post->ID))
            {
                $this->post_id = $post->ID;
            }
            return wp_update_post($post);
        }
    }
}
