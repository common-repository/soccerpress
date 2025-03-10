<?php

/*
  ======================================================
  Custom Post Type - Teams
  ======================================================
*/

class TeamList
{
    public function __construct()
    {
        $this->register_teams();
        add_action('add_meta_boxes', array($this, 'add_fupa_team_id'));
        add_action('save_post', array($this, 'save_values'));

        // Team columns
        add_filter('manage_teams_posts_columns', array($this, 'edit_columns'));
        add_action('manage_teams_posts_custom_column', array($this, 'custom_columns'), 2, 2);
    }

    public function register_teams()
    {
        $labels = array(
            'name'                  => 'FuPa Teams',
            'singular_name'         => 'Sponsor',
            'add_new'               => 'Erstellen',
            'all_items'             => 'Fupa Teams',
            'edit_item'             => 'Mannschaft bearbeiten',
            'new item'              => 'New Item',
            'view_item'             => 'View Item',
            'search_item'           => 'Suche Sponsor',
            'not found'             => 'No items found',
            'not_found_in trash'    => 'No items found in trash',
            'parent_item_colon'     => 'Parent Item'
        );

        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'has_archive'           => true,
            'menu_icon'             => 'dashicons-shield-alt',
            'publicly_queryable'    => true,
            'query_var'             => true,
            'capability_type'       => 'post',
            'has_archive'           => true,
            'supports'              => array('title'),
            'menu_position'         => 25,
            'register_meta_box_cb'  => array($this, 'add_fupa_team_id')
        );

        register_post_type('teams', $args);
    }

    /**
     * Metabox
     */
    public function add_fupa_team_id()
    {
        add_meta_box(
            'fupa_team_id',                         // Unique id
            'FuPa',                                 // Box title
            array($this, 'fupa_team_id_callback'),  // Content callback
            'teams',                                // Post type
            'normal',                               // context (normal, side)
            'high'                                  // priority (high, low, default)
        );
    }

    /**
     * Load the html into the metabox
     */
    public function fupa_team_id_callback($post)
    {
        $fupa_team_id = get_post_meta($post->ID, '_fupa_team_id', true);
        ?>

        <!-- Team id -->
        <div class="row">
            <div class="col-6">
                <label>Team ID</label>
            </div>
            <div class="col-6">
                <input type="text" name="fupa_team_id" class="form-control" value="<?php echo $fupa_team_id ?>" placeholder="Fupa Team ID">
            </div>
        </div>
    <?php
        }

        /**
         * Save POST values
         */
        public function save_values($post_id)
        {
            // Return if the user doesn't have edit permissions.
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }

            // Send save user data
            if (array_key_exists('fupa_team_id', $_POST)) {
                // Save team id
                $team_id = sanitize_text_field($_POST['fupa_team_id']);
                update_post_meta($post_id, '_fupa_team_id',  $team_id);
            }
        }


        /**
         * Teams custom columns
         */
        public function edit_columns($columns)
        {
            unset($columns['author']);
            $columns['fupa_team_id'] = 'FuPa Team ID';
            $columns['shortcode'] = "Shortcode";

            return $columns;
        }

        /**
         * Show value of custom columns
         */
        public function custom_columns($column, $post_id)
        {
            switch ($column) {
                case 'fupa_team_id':
                    $team_id = get_post_meta($post_id, '_fupa_team_id', true);
                    echo $team_id ? esc_html($team_id) : '&mdash;';
                    break;
                case 'shortcode':
                    echo '[table id=' . $post_id . ']';
                    break;
            }
        }
    }

    new TeamList();
