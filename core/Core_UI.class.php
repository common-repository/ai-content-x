<?php
if (!class_exists('Core_UI'))
{
    class Core_UI
    {
        private static $_instance = null;

        /** 
         * Constructor
         * @return void
         * @since 1.0.0
         */
        public static function instance()
        {
            if (is_null(self::$_instance))
            {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /** 
         * Create a link tag with attributes
         * @param string $href link url
         * @param string $text link text also can be html
         * @param array $attributes extra attributes for link
         * @return string link tag 
         * @since 1.0.0
         * @access public
         */

        public function link($text, $href,   $attributes = array())
        {
            return sprintf('<a href="%s" %s>%s</a>', esc_url($href), $this->attributes($attributes), ($text));
        }

        /** 
         * Create a textarea tag with attributes
         * @param string $name input name
         * @param string $value input value
         * @param array $attributes extra attributes
         * @return string textarea tag 
         * @since 1.0.0
         * @access public
         */

        public function textarea($name, $value, $attributes = array())
        {
            return sprintf('<textarea name="%s" %s>%s</textarea>', esc_attr($name), $this->attributes($attributes), esc_textarea($value));
        }

        /** 
         * Create a input tag with attributes
         * @param string $type input type
         * @param string $name input name
         * @param string $value input value
         * @param array $attributes extra attributes
         * @return string input tag 
         * @since 1.0.0
         * @access public
         */

        public function input($type, $name = '', $value = '', $attributes = array())
        {
            // check if type is array
            if (is_array($type))
            {
                $attributes = $type;
            }
            else
            {
                $attributes['type'] = $type;
                $attributes['name'] = $name;
                $attributes['value'] = $value;
            }
            return sprintf('<input %s>', $this->attributes($attributes));
        }

        /** 
         * Create a select tag with attributes
         * @param string $name input name
         * @param array $options options for select
         * @param string $selected selected option
         * @param array $attributes extra attributes
         * @return string select tag 
         * @since 1.0.0
         * @access public
         */

        public function select($name, $options = array(), $selected = null, $attributes = array())
        {
            if (is_array($name))
            {
                $attributes = $name;
                $name = isset($attributes['name']) ? $attributes['name'] : null;
                unset($attributes['name']);
                $options = isset($attributes['options']) ? $attributes['options'] : null;
                unset($attributes['options']);
                $selected = isset($attributes['selected']) ? $attributes['selected'] : null;
                unset($attributes['selected']);
            }
            $result = sprintf('<select name="%s" %s>', esc_attr($name), $this->attributes($attributes));
            if (isset($attributes['title']))
            {
                $result .= sprintf('<option disabled value="">%s</option>', esc_html($attributes['title']));
            }
            foreach ($options as $key => $value)
            {
                $result .= sprintf('<option value="%s" %s>%s</option>', esc_attr($key), selected($key, $selected, false), esc_html($value));
            }
            $result .= '</select>';
            return $result;
        }

        /** 
         * Create a checkbox tag with attributes
         * @param string $name input name
         * @param string $value input value
         * @param string $checked checked option
         * @param array $attributes extra attributes
         * @return string checkbox tag 
         * @since 1.0.0
         * @access public
         */

        public function checkbox($name, $value, $checked, $attributes = array())
        {
            if (!isset($attributes['id']))
            {
                $attributes['id'] = esc_attr($name);
            }
            return sprintf('<label for="%s" %s><input type="checkbox" name="%s" value="%s" %s %s/><i></i></label>', esc_attr($attributes['id']), $this->attributes($attributes, array('id')), esc_attr($name), esc_attr($value), checked($value, $checked, false), $this->attributes($attributes, array('class')));
        }

        /** 
         * Create paragraph tag
         * @param string $text paragraph text
         * @param array $attributes extra attributes
         * @return string paragraph tag
         * @since 1.0.0
         * @access public
         */

        public function paragraph($text, $attributes = array())
        {
            return sprintf('<p %s>%s</p>', $this->attributes($attributes), $text);
        }

        /** 
         * Create wordpress description tag (p tag with class description)
         * @param string $text description text
         * @param array $attributes extra attributes
         * @return string description tag
         * @since 1.0.0
         * @access public
         */

        public function description($text, $attributes = array())
        {
            $attributes['class'] = isset($attributes['class']) ? $attributes['class'] . ' description' : 'description';
            return $this->paragraph($text, $attributes);
        }


        /**
         * Convert extra attributes to string
         * @param array $attributes extra attributes
         * @param array $exclude exclude attributes
         * @return string
         * @since 1.0.0
         * @access private
         * 
         */
        private function attributes($attributes, $exclude = array())
        {
            $result = '';
            foreach ($attributes as $key => $value)
            {
                if (in_array($key, $exclude))
                {
                    continue;
                }
                $result .= sprintf('%s="%s" ', esc_attr($key), esc_attr($value));
            }
            return $result;
        }

        /**
         * Create a button
         * @param string $text button text
         * @param string $type button type
         * @param array $attributes extra attributes
         * @return string button tag
         * @since 1.0.0
         * @access public
         */
        public function button($text, $attributes = array())
        {
            if (is_array($text))
            {
                $attributes = $text;
                $text = isset($attributes['text']) ? $attributes['text'] : '';
                unset($attributes['text']);
            }
            $attributes['type'] = isset($attributes['type']) ? $attributes['type'] : 'button';
            return sprintf('<button %s>%s</button>', $this->attributes($attributes), $text);
        }

        /**
         * Create a image
         * @param string $src image src
         * @param array $attributes extra attributes
         * @return string image tag
         * @since 1.0.0
         * @access public
         */
        public function image($attributes = array(), $src = '')
        {
            if (!empty($src))
            {
                $attributes['src'] = $src;
            }
            return sprintf('<img %s/>', $this->attributes($attributes));
        }

        /**
         * Create tag container
         * @param string $tag tag name
         * @param string $content text also can be html
         * @param array $attributes extra attributes
         * @return string string tag
         * @since 1.0.0
         */
        public function tag($tag, $content,  $attributes = array())
        {
            return sprintf('<%s %s>%s</%s>', esc_attr($tag), $this->attributes($attributes), $content, esc_attr($tag));
        }

        /**
         * Create form container
         * @param string $content text
         * @param array $attributes extra attributes
         * @return string Form tag
         * @since 1.0.0
         * @access public
         */
        public function form($content, $attributes = array())
        {
            return sprintf('<form %s>%s</form>', $this->attributes($attributes), $content);
        }

        /**
         * Create wrap container
         * @param string $tag tag name
         * @param string $content  text
         * @param array $attributes extra attributes
         * @return string wrap tag
         * @since 1.0.0
         * @access public
         */

        public function wrap($tag, $content, $attributes = array())
        {
            return sprintf('<%s %s>%s</%s>', esc_attr($tag), $this->attributes($attributes), $content, esc_attr($tag));
        }

        /**
         * Create form open tag
         * @param array $attributes extra attributes
         * @return string form open tag
         * @since 1.0.0
         * @access public
         */

        public function form_open($attributes = array())
        {
            return sprintf('<form %s>', $this->attributes($attributes));
        }

        /**
         * Create form close tag
         * @return string form close tag
         */
        public function form_close()
        {
            return '</form>';
        }

        /**
         * Determines the difference between two timestamps.
         * @param int $from Unix timestamp from which the difference begins.
         * @param int $to Optional. Unix timestamp to end the time difference. Default false.
         * @return string Human readable time difference.
         * @since 1.0.0
         * @access public
         * @link https://developer.wordpress.org/reference/functions/human_time_diff/
         */

        public function human_time_diff($from, $to)
        {
            return human_time_diff($from, $to);
        }

        /**
         * Convert date to wordpress date format
         * @param string $format date format
         * @param int|string $timestamp Unix timestamp or date string
         * @param bool $translate Optional. Whether to translate the date. Default true.
         * @return string
         */
        public function date($timestamp = false, $timezone = true)
        {

            if (is_numeric($timestamp))
            {
                $timestamp = (int) $timestamp;
            }
            else
            {
                $timestamp = strtotime($timestamp);
            }
            $format = get_option('date_format') . ' ' . get_option('time_format');
            return wp_date($format, $timestamp);
        }

        public function premium($str = 'Only Pro Version', $link = true, $class = '')
        {
            if ($link)
                return sprintf('<a href="%s" target="_blank" class="premium">%s</a>', esc_url('https://jodacame.dev/ai-content-x/free-vs-pro/'), esc_html__($str, 'ai-content-x'));
            else
                return sprintf('<span class="premium ' . $class . '">%s</span>', esc_html__($str, 'ai-content-x'));
        }
    }
}
