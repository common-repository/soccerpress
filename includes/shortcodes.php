<?php

class ShortcodeManager
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_shortcode('table', array($this, 'create_shortcode'));
    }

    /**
     * Add shortcodes
     */

    public function create_shortcode($atts)
    {
        if (empty($atts)) {
            echo 'Der Shortcode existiert nicht.';
            return;
        }

        // Get post types
        $args = array(
            'post_type'     => 'teams',
            'post_status'   => 'publish'
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $fupa_team_id = get_post_meta($atts['id'], '_fupa_team_id', true);

            if (empty($fupa_team_id)) {
                echo 'Es wurde keine FuPa Team Id eingetragen.';
                return;
            }

            ?>
            <!-- Show FuPa Widget -->
            <div id="widget_<?php echo $fupa_team_id ?>">
                Laden...
                <script type="text/javascript">
                    ! function(i) {
                        window.setTimeout(function() {
                            "undefined" === typeof fupa_widget_domain ? --i.t > 0 && window.setTimeout(arguments.callee, i.i) : i.f()
                        }, i.i)
                    }({
                        i: 20,
                        t: 100,
                        f: function() {
                            team_widget(<?php echo $fupa_team_id ?>, {
                                act: "tabelle",
                                hea: 0,
                                nav: 0,
                                div: "widget_<?php echo $fupa_team_id ?>",
                                mod: "0"
                            })
                        }
                    });
                </script>
            </div>
<?php

        }
        wp_reset_postdata();
    }
}

new ShortcodeManager();
