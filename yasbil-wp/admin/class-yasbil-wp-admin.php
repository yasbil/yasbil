<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    YASBIL_WP
 * @subpackage YASBIL_WP/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    YASBIL_WP
 * @subpackage YASBIL_WP/admin
 * @author     Your Name <email@example.com>
 */
class YASBIL_WP_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $yasbil_wp    The ID of this plugin.
	 */
	private $yasbil_wp;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $yasbil_wp       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $yasbil_wp, $version ) {

		$this->yasbil_wp = $yasbil_wp;
		$this->version = $version;

	}


    /**
     * Register the REST API endpoints for uploading YASBIL data
     *
     * @since    1.0.0
     */
    public function yasbil_register_api_endpoints()
    {
        //POST:  https://volt.ischool.utexas.edu/wp/wp-json/yasbil/v2_0_0/sync_table

        register_rest_route('yasbil/v2_0_0', 'check_connection', [
            'methods'             => WP_REST_Server::READABLE, //GET
            'callback'            => array($this, 'yasbil_sync_check_connection'),
            'permission_callback' => array($this, 'yasbil_sync_check_permission'),
        ]);

        register_rest_route('yasbil/v2_0_0', 'sync_table', [
            'methods'             => WP_REST_Server::CREATABLE, //POST
            'callback'            => array($this, 'yasbil_sync_table'),
            'permission_callback' => array($this, 'yasbil_sync_check_permission'),
        ]);

        /*register_rest_route('yasbil/v1', 'sync_sessions', [
            // By using this constant we ensure that when the WP_REST_Server
            // changes our readable endpoints will work as intended.
            'methods'             => WP_REST_Server::CREATABLE, //POST
            // Here we register our callback. The callback is fired when this
            // endpoint is matched by the WP_REST_Server class.
            'callback'            => array($this, 'yasbil_sync_sessions_table'),
            // Here we register our permissions callback. The callback is fired
            // before the main callback to check if the current user can access the endpoint.
            'permission_callback' => array($this, 'yasbil_sync_check_permission'),
        ]);*/

        //POST:  https://volt.ischool.utexas.edu/wp/wp-json/yasbil/v1/sync_pagevisits

        /*register_rest_route('yasbil/v1', 'sync_pagevisits', [
            'methods'             => WP_REST_Server::CREATABLE, //POST
            'callback'            => array($this, 'yasbil_sync_pagevisits_table'),
            'permission_callback' => array($this, 'yasbil_sync_check_permission'),
        ]);*/

        // multiple endpoints can be registered in one function..
//        register_rest_route('yasbil/v1', 'posts', [
//            'methods' => 'GET',
//            'callback' => 'wl_posts',
//        ]);

        // register_rest_route() handles more arguments but we are going to stick to the basics for now.
//        register_rest_route( 'my-plugin/v1', '/private-data', array(
//            // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
//            'methods'  => WP_REST_Server::READABLE, //GET
//            // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
//            'callback' => 'prefix_get_private_data',
//            // Here we register our permissions callback. The callback is fired before the main callback to check if the current user can access the endpoint.
//            'permission_callback' => 'prefix_get_private_data_permissions_check',
//        ) );

    }



    /**
     * Register custom taxonomy -- YASBIL Projects -- to categoize users
     *
     * @since    1.0.0
     */
    public function yasbil_register_taxonomy_yasbil_projects()
    {
        $labels = array(
            'name'                       => _x( 'YASBIL Projects', 'YASBIL Projects Name', 'text_domain' ),
            'singular_name'              => _x( 'YASBIL Project', 'YASBIL Project Name', 'text_domain' ),
            'menu_name'                  => __( 'YASBIL Projects', 'text_domain' ),
            'all_items'                  => __( 'All YASBIL Projects', 'text_domain' ),
            'parent_item'                => __( 'Parent YASBIL Project', 'text_domain' ),
            'parent_item_colon'          => __( 'Parent YASBIL Project:', 'text_domain' ),
            'new_item_name'              => __( 'New YASBIL Project Name', 'text_domain' ),
            'add_new_item'               => __( 'Add New YASBIL Project', 'text_domain' ),
            'edit_item'                  => __( 'Edit YASBIL Project', 'text_domain' ),
            'update_item'                => __( 'Update YASBIL Project', 'text_domain' ),
            'view_item'                  => __( 'View YASBIL Project', 'text_domain' ),
            'separate_items_with_commas' => __( 'Separate YASBIL Projects with commas', 'text_domain' ),
            'add_or_remove_items'        => __( 'Add or remove YASBIL Projects', 'text_domain' ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
            'popular_items'              => __( 'Popular YASBIL Projects', 'text_domain' ),
            'search_items'               => __( 'Search YASBIL Projects', 'text_domain' ),
            'not_found'                  => __( 'Not Found', 'text_domain' ),
            'no_terms'                   => __( 'No YASBIL Projects', 'text_domain' ),
            'items_list'                 => __( 'YASBIL Projects list', 'text_domain' ),
            'items_list_navigation'      => __( 'YASBIL Projects list navigation', 'text_domain' ),
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => false, // NOPE! do not make it hierarchical (like categories)
            //'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            //'show_tagcloud'              => true,
            'query_var'                 => true,
            'rewrite'                   => [ 'slug' => 'yasbil_project' ],
        );
        register_taxonomy( 'yasbil_projects', 'user', $args );
    }





    /**
     * Adds admin page for
     * 'YASBIL Projects' taxonomy
     * Viz Pages
     *
     * @since    1.0.0
     */
    public function yasbil_add_admin_pages()
    {
        // redirects to per user activity
        add_menu_page(
            'YASBIL WP: View Synced Data', //Page Title
            'YASBIL WP', //Menu Title
            'read', //Capability: all reg users
            'yasbil_wp', //Page slug
            array($this, 'yasbil_html_per_user_data'), //Callback to print html
            //'dashicons-palmtree' //icon url
            plugins_url( 'yasbil-wp/icon/yasbil-icon-20.png' )
            // 6, // https://developer.wordpress.org/reference/functions/add_menu_page/#menu-structure
        );

        //overall summary
        add_submenu_page(
            'yasbil_wp', //parent slug
            'Overall Summary', //Page Title
            'Overall Summary', //Menu Title
            'administrator', //Capability: Only admins
            'yasbil_wp-summary', //menu slug
            array($this, 'yasbil_html_admin_summary_data') //Callback to print html
        );

        // per user activity
        add_submenu_page(
            'yasbil_wp', //parent slug
            'View Synced Data', //Page Title
            'View Synced Data', //Menu Title
            'read', //Capability: all reg users
            'yasbil_wp', //menu slug
            array($this, 'yasbil_html_per_user_data') //Callback to print html
        );

        $tax = get_taxonomy( 'yasbil_projects' );

        add_submenu_page(
            'yasbil_wp', //parent slug
            esc_attr( $tax->labels->menu_name ), //page title
            esc_attr( $tax->labels->menu_name ), //menu title
            'administrator', //Capability: only admins
            'edit-tags.php?taxonomy=' . $tax->name //menu slug
        );
    }


    /**
     * Update parent file name to fix the selected menu issue
     */
    function yasbil_fix_menu_items($parent_file)
    {
        global $submenu_file;
        if (
            isset($_GET['taxonomy']) &&
            $_GET['taxonomy'] == 'yasbil_projects' &&
            $submenu_file == 'edit-tags.php?taxonomy=yasbil_projects'
        ) {
            //TODO: fix this
            //$parent_file = 'users.php';
            $parent_file = 'admin.php?page=yasbil_wp';
            //$parent_file = 'admin.php';
        }
        return $parent_file;
    }


    /**
     * Add an additional settings section on the new/edit user profile page in the admin.
     * This section allows users to select a YASBIL Project from a set of radio button of terms
     * from the "YASBIL Projects" taxonomy.
     *
     * This is just one example of many ways this can be handled.
     * Another is multi-select
     *
     * @param object $user The user object currently being edited.
     *
     * @since    1.0.0
     */
    public function yasbil_admin_edit_user_screen($user)
    {
        // show only to administrators (i.e. not to individual participants)
        if(!current_user_can( 'administrator' ))
            return;

        // Make sure the user can assign terms of the YASBIL Projects taxonomy before proceeding.
        // if ( !current_user_can( $tax->cap->assign_terms ) )
        //    return;

        global $pagenow;
        $tax = get_taxonomy( 'yasbil_projects' );

        // Get the terms of the 'yasbil_projects' taxonomy.
        $terms = get_terms( 'yasbil_projects', array( 'hide_empty' => false ) );
?>

        <hr style="border: 1px solid #aaa; margin: 20px 0px 10px;">

        <h1 style="text-align: center">
            <?php _e( 'YASBIL Project' ); ?>
        </h1>
        <p style="font-size: 16px;">
            <b>WARNING:</b>
            Changing the YASBIL project will be reflected in subsequent data uploads only.
            Data uploaded in the past will NOT get modified.
            <br/><br/>
            <b><i>Do not alter the assigned YASBIL project if a participant has already uploaded some data.</i></b>
            <br/>
            In that case, it is better to create a new user and assign them to the new YASBIL project.
        </p>
        <table class="form-table">
            <tr>
                <th>
                    <label for='yasbil_projects'>
                        <?php _e( 'Assigned to YASBIL Project' ); ?>
                    </label>
                </th>
                <td>
<?php
                    // If there are any YASBIL Project terms, loop through them and display radio buttons.
                    // only one YASBIL project per user
                    if ( !empty( $terms ) )
                    {
                        $userAssigned = 0;

                        foreach ( $terms as $term )
                        {
                            if(is_object_in_term( $user->ID, 'yasbil_projects', $term->slug )){
                                $userAssigned += 1; //not using true / false as this will loop again
                            }
?>
                            <label for="yasbil_projects-<?php echo esc_attr( $term->slug ); ?>">
                                <input type="radio"
                                    name='yasbil_projects'
                                    id="yasbil_projects-<?php echo esc_attr( $term->slug ); ?>"
                                    value="<?php echo $term->slug; ?>"
                                    <?php if ( $pagenow !== 'user-new.php' ) checked( true, is_object_in_term( $user->ID, 'yasbil_projects', $term->slug ) ); ?>
                                >
                                <?php echo $term->name; ?>
                            </label>
                            <br/>
<?php

                            if($pagenow !== 'user-new.php' && $userAssigned > 0)
                            {
?>                              <script>jQuery('input[name=yasbil_projects]').attr('disabled',true);</script>
<?php
                            }

                        }
                    }
                    // If there are no YASBIL Projects, display a message.
                    else {
                        _e( 'No YASBIL Projects are available. Create some projects from the YASBIL WP Menu.' );
                    }
?>
                </td>
            </tr>
        </table>

        <hr style="border: 1px solid #aaa; margin: 10px 0px 20px;">

<?php
    }




    /**
     * Saves the term selected on the new or edit user profile page in the admin.
     * Sets yasbil_user_status meta key = ACTIVE (i.e user can immediately start syncing data)
     * This function is triggered when the page is updated. We just grab the posted data
     * and use wp_set_object_terms() to save it.
     *
     * @param int $user_id The ID of the user to save the terms for.
     *
     * @since    1.0.0
     */
    public function yasbil_save_user_yasbil_project( $user_id )
    {
        // allow only for administrators
        // i.e. do not allow participants to change their projects
        if(!current_user_can( 'administrator' ))
            return;

        // Make sure the current user can edit the user and assign terms before proceeding.
        // if ( !current_user_can( 'edit_user', $user_id ) && current_user_can( $tax->cap->assign_terms ) )
        //    return false;

        $term = $_POST['yasbil_projects'];
        // Sets the terms (we're just using a single term) for the user.
        wp_set_object_terms( $user_id, $term, 'yasbil_projects', false);

        //enables this user to sync data
        $this->yasbil_set_user_enabled($user_id);

        clean_object_term_cache( $user_id, 'yasbil_projects' );
    }



    /**
     * @param string $username The username of the user before registration is complete.
     */
    public function yasbil_sanitize_username( $username )
    {
        if ( 'yasbil_projects' === $username )
            $username = '';

        return $username;
    }







//-------------------------- START: HTML Render Functions --------------------------------


    /**
     * Renders HTML to view Summary Data
     * Only Visible To admins.
     * Provides URLs to view per-user data
     */
    public function yasbil_html_admin_summary_data()
    {
        //page is only for admins
        // redundant - probably
        if(!current_user_can('administrator')) {
            return;
        }

        global $wpdb;

        // activate / deactivate participants
        if(isset($_POST['yasbil_select_users']) && is_array($_POST['yasbil_select_users']))
        {
            $arr_user_ids = $_POST['yasbil_select_users'];

            foreach($arr_user_ids as $user_id)
            {
                if(isset($_POST['yasbil_set_user_enabled']))
                    $this->yasbil_set_user_enabled($user_id);
                elseif (isset($_POST['yasbil_set_user_disabled']))
                    $this->yasbil_set_user_disabled($user_id);
            }
        }

        ?>

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

        <div class="wrap">
            <h1>YASBIL Data Collection Summary</h1>

            <p>All timestamps are in participant's local time.</p>

            <style>
                .badge-enabled {
                    color: #fff;
                    background-color: #007bff;
                }

                .badge-disabled {
                    color: #fff;
                    background-color: #aaa;
                }

                .badge {
                    display: inline-block;
                    padding: .25em .4em;
                    font-size: 100%;
                    font-weight: 700;
                    line-height: 1;
                    text-align: center;
                    white-space: nowrap;
                    vertical-align: baseline;
                    border-radius: .25rem;
                }
            </style>


            <?php

            // Get the terms (project names) of the 'yasbil_projects' taxonomy.
            $arr_yasbil_projects = get_terms( 'yasbil_projects', array( 'hide_empty' => false ) );
            if ( !empty( $arr_yasbil_projects ) )
            {
                foreach ($arr_yasbil_projects as $proj)
                {
                    $project_id = $proj->term_id; // constant; use Select distinct on this
                    $project_name = strtoupper($proj->slug) ; // should be constant; can be varied;
                    $project_nice_name = $proj->name;
                    $project_desc = $proj->description;

                    ?>
                    <hr style="border: 1px solid #aaa; margin: 20px 0px 10px;">

                    <h1>
                        Project <?=$project_id?>: <?=$project_name?>
                    </h1>

                    <p style="font-size:16px">
                        <b>Full Name:</b>
                        <?=$project_nice_name?>
                        &nbsp; &bull; &nbsp;
                        <b>Description:</b>
                        <?=$project_desc?>

                        <br/><br/>

                        List of Participants:
                    </p>

                <?php
                $arr_participants = get_objects_in_term($project_id, 'yasbil_projects');

                // not WP_Error and not empty array
                if(!is_wp_error( $arr_participants ) && !empty($arr_participants))
                {
                ?>
                    <form method="post">
                    <p class="submit">
                        <input type="submit" name="yasbil_set_user_enabled" id="submit-enable"
                               class="button button-primary" value="Enable Users">
                        &nbsp;&nbsp;
                        <input type="submit" name="yasbil_set_user_disabled" id="submit-deactivate"
                               class="button button-secondary" value="Disable Users">
                    </p>




                    <div class="table-wrapper"
                         style="padding: 10px; background: white"
                    >

                        <table id="table_project_<?=$project_name?>" class="display">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Total Sessions</th>
                                <th>Total Page Visits</th>
                                <th>Avg Session Duration</th>
                                <th>Page Visits per Session</th>
                                <th>Last Browsing Activity</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($arr_participants as $user_id)
                            {
                                $user_info = get_userdata($user_id);
                                $user_status = $this->yasbil_get_user_status($user_id);
                                $user_status_badge = "<span class='badge badge-enabled'>ENABLED</span>";

                                if($user_status === "DISABLED")
                                    $user_status_badge = "<span class='badge badge-disabled'>DISABLED</span>";

                                $user_data_url = admin_url('admin.php?page=yasbil_wp&user_id='.$user_id, 'https');
                                $user_name = $user_info->user_login;
                                $user_name_link = '<a title="View Uploaded Data" target="_blank" href="'.$user_data_url.'">'
                                    .$user_name
                                    .'</a>';

                                $tbl_sessions = $wpdb->prefix . "yasbil_sessions";
                                $tbl_pagevisits = $wpdb->prefix . "yasbil_session_pagevisits";

                                //fix: added IF() for when session_end_ts = 0 (not recorded)

                                $sql_select_summary_stats = "
                            SELECT COUNT(DISTINCT s.session_guid) session_ct
                                , COUNT(DISTINCT pv.hist_ts) pv_ct
                                , AVG(distinct(IF(s.session_end_ts<>0, s.session_end_ts, s.session_start_ts) - s.session_start_ts)) avg_session_dur_ms
                                , round(COUNT(DISTINCT pv.hist_ts) / COUNT(DISTINCT s.session_guid), 1) pv_per_session
                                , MAX(pv.pv_ts) last_activity
                                , s.session_tz_offset
                            FROM $tbl_sessions s,
                                $tbl_pagevisits pv
                            WHERE 1=1 
                            AND s.session_guid = pv.session_guid
                            AND s.user_id = %s
                        ";

                                $row_stats = $wpdb->get_row(
                                    $wpdb->prepare($sql_select_summary_stats, $user_id),
                                    ARRAY_A
                                );

                                $tz_off = $row_stats['session_tz_offset'];

                                ?>
                                <tr>
                                    <td>
                                        <label for="yasbil_select_user_<?=$user_id?>">
                                            <input type="checkbox"
                                                   name='yasbil_select_users[]'
                                                   id="yasbil_select_user_<?=$user_id?>"
                                                   title="Select User <?=$user_id?> (<?=$user_name?>) to activate / deactivate"
                                                   value="<?=$user_id?>">
                                            <?=$user_id?>
                                            &nbsp;
                                            <?=$user_status_badge?>
                                        </label>

                                    </td>
                                    <td><?=$user_name_link?></td>
                                    <td><?=$row_stats['session_ct']?></td>
                                    <td><?=$row_stats['pv_ct']?></td>
                                    <td><?=$this->yasbil_display_dur($row_stats['avg_session_dur_ms'])?></td>
                                    <td><?=$row_stats['pv_per_session']?></td>
                                    <td><?=$this->yasbil_milli_to_str($row_stats['last_activity'], $tz_off)?></td>
                                </tr>

                                <?php
                            } // --------- end participants loop -------------
                            ?>
                            </tbody>

                        </table>


                    </div> <!-- table wrapper -->

                    <p class="submit">
                        <input type="submit" name="yasbil_set_user_enabled" id="submit-enable"
                               class="button button-primary" value="Enable Users">
                        &nbsp;&nbsp;
                        <input type="submit" name="yasbil_set_user_disabled" id="submit-deactivate"
                               class="button button-secondary" value="Disable Users">
                    </p>
                    </form>

                    <script>
                        jQuery('#table_project_<?=$project_name?>').DataTable({
                            pageLength: 100
                        });
                    </script>

                    <?php
                } else {
                    _e( 'No participants in this YASBIL Project. 
                    Assign Participants to projects by editing their User Profiles.' );
                } // end if of WP Users

                } // end: project loop
            }
            // If there are no YASBIL Projects, display a message.
            else{
                _e( 'No YASBIL Projects are available. Create some from the YASBIL-WP Menu.' );
            }

            ?>


        </div> <!-- end div.wrap-->








        <?php

    } // summary render html function




    /**
     * Renders HTML to view synced data
     * Participants: can only view their own data
     * Admins: checks key for user_id;
     * TODO: if not, should redirects to summary page
     */
    public function yasbil_html_per_user_data()
    {
        $user_id = 0;

        $user_data = null;
        if ( current_user_can('administrator'))
        {
            if(isset($_GET['user_id']))
            {
                $user_id = $this->yasbil_nvl($_GET['user_id'], 0);
                $user_data = get_userdata($user_id);
            }
            else
            {
                // if no user-id is set, render the summary page
                //redirect to summary page

                $redirect_url = admin_url('admin.php?page=yasbil_wp-summary', 'https');
                nocache_headers();
                if ( wp_redirect( $redirect_url, 302 ) ) //temporary redirect
                    exit;
            }
        }
        elseif (current_user_can('read'))
        {
            if(isset($_GET['user_id']))
            {
                // if non-admins try to pass user_id param,
                // redirect to main plugin page
                $redirect_url = admin_url('admin.php?page=yasbil_wp', 'https');
                nocache_headers();
                if ( wp_redirect( $redirect_url, 302 ) ) //temporary redirect
                    exit;
            }

            $user_data = wp_get_current_user();
            $user_id = $user_data->ID;
        }
        else
        {
            return;
        }

        // search query params
        //st date, end date

        global $wpdb;

        $project_detail = $this->yasbil_get_user_project($user_id);
        $project_name = $project_detail[1];
        $user_name = $user_data->user_login;

?>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
        <!-- datatables  buttons-->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
        <!-- export -->
        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

        <div class="wrap">

<?php

        if ( current_user_can('administrator'))
        {
            //show participant details only to administrator
?>
            <h1>Participant Details</h1>

            <p style="font-size:16px">
                <b>Project:</b>
                <?=$project_name?>

                &nbsp; &bull; &nbsp;

                <b>User ID:</b>
                <?=$user_id?>
                &nbsp; &bull; &nbsp;

                <b>User Name:</b>
                <?=$user_name?>
            </p>
<?php   }
        else
        {
?>
            <h1>Your Uploaded Data</h1>
            <p style="font-size:16px">
                This is the data you have uploaded
                using the YASBIL Browser Extension.
                <br/>
                You can download copies of your data in various formats by clicking the appropriate buttons.
                <br/>
                If you have any concerns regarding this uploaded
                data, please get in touch with us.
                We are always ready to help mitigate your concerns.
            </p>
<?php   }
?>

            <p>All timestamps are in participant's local time.</p>
<?php
        $tbl_sessions = $wpdb->prefix . "yasbil_sessions";

        $sql_select_sessions = "
            SELECT *
            FROM $tbl_sessions s
            WHERE 1=1
            and s.user_id = %s
            ORDER BY s.session_start_ts desc
        ";

        $db_res_sessions = $wpdb->get_results(
            $wpdb->prepare($sql_select_sessions, $user_id),
            ARRAY_A
        );

        // -------- start sessions loop ------------
        foreach ($db_res_sessions as $row_s)
        {
            $session_guid = $row_s['session_guid'];
            $tz_off = $row_s['session_tz_offset'];
?>
            <hr style="border: 1px solid #aaa; margin: 20px 0px 10px;">

            <h1>
                Session ID:
                <?=$row_s['session_id']?>
                (<?=$this->yasbil_truncate_str($row_s['session_guid'],9)?>)
            </h1>

            <p style="font-size:16px">
                <b>Start Time:</b>
                <?=$this->yasbil_milli_to_str($row_s['session_start_ts'], $tz_off, true)?>
                &nbsp; &bull; &nbsp;
                <b>End Time:</b>
                <?=$this->yasbil_milli_to_str($row_s['session_end_ts'], $tz_off, true)?>
                &nbsp; &bull; &nbsp;
                <b>Duration:</b>
                <?=$this->yasbil_display_dur_diff($row_s['session_start_ts'], $row_s['session_end_ts'])?>

                <br/>

                <b>Platform:</b>
                <?="{$row_s['platform_os']} {$row_s['platform_arch']} {$row_s['platform_nacl_arch']}"?>
                &nbsp; &bull; &nbsp;
                <b>Browser:</b>
                <?="{$row_s['browser_vendor']} {$row_s['browser_name']} {$row_s['browser_version']}"?>
                &nbsp; &bull; &nbsp;
                <b>Synced:</b>
                <?=$this->yasbil_milli_to_str($row_s['sync_ts'], $tz_off, true)?>


                <br/><br/>

                Page Visits:
            </p>

<?php
            $tbl_pagevisits = $wpdb->prefix . "yasbil_session_pagevisits";

            $sql_select_pv = "
                    SELECT *
                    FROM $tbl_pagevisits pv
                    WHERE 1=1
                    and pv.session_guid = %s
                    group by pv.hist_ts
                    ORDER BY pv.pv_ts asc
                ";

            $db_res_pv =  $wpdb->get_results(
                $wpdb->prepare($sql_select_pv, $session_guid),
                ARRAY_A
            );

?>

            <div class="table-wrapper" 
                 style="padding: 10px; background: white"
            >

                <table id="table_pagevisits_<?=$row_s['session_id']?>" class="display">
                    <thead>
                        <tr>
                            <!-- hidden. for export -->
                            <th>Sync Time</th>
                            <th>Full Title</th>
                            <th>Full URL</th>

                            <!-- visible-->
                            <th>Timestamp</th>
                            <th>Window# | Tab#</th>
                            <th>URL</th>
                            <th>Navigation Event</th>
                            <th>Page Title</th>
                            <th>
                                <a target="_blank"
                                   href="https://developer.mozilla.org/en-US/docs/Mozilla/Add-ons/WebExtensions/API/history/TransitionType"
                                >Transition</a>
                            </th>
                            <th>Search Engine, Search Query</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
            // --------- start pagevisit loop - for rows of table -------------

            // for displaying window and tab numbers as 1,2...
            $win_num = 1;
            $tab_num = 1;
            $arr_win = array();
            $arr_tab = array();

            foreach ($db_res_pv as $row_pv)
            {
                if(!array_key_exists($row_pv['win_id'], $arr_win)) {
                    $arr_win[$row_pv['win_id']] = $win_num++;
                }

                if(!array_key_exists($row_pv['tab_id'], $arr_tab)) {
                    $arr_tab[$row_pv['tab_id']] = $tab_num++;
                }

                $time = $this->yasbil_milli_to_str($row_pv['pv_ts'], $tz_off);

                $window = $arr_win[$row_pv['win_id']];
                $tab = $arr_tab[$row_pv['tab_id']];

                //$transition_type = $row_pv['pv_transition_type'];

                $url_host = '<a target="_blank" href="'. esc_url($row_pv['pv_url']) . '">'
                    . $row_pv['pv_hostname']
                    . '</a>';

?>
                    <tr>
                        <!-- hidden; for export-->
                        <td><?=$this->yasbil_milli_to_str($row_pv['sync_ts'])?></td>
                        <td><?=$row_pv['pv_title']?></td>
                        <td><?=$row_pv['pv_url']?></td>

                        <!--visible-->
                        <td><?=$time?></td>
                        <td>
                            <?=$window?> | <?=$tab?>
                        </td>
                        <td><?=$url_host?></td>
                        <td><?=str_replace('.', ' ', $row_pv['pv_event'])?></td>
                        <td>
                            <?=str_replace(
                                ['.',  '+',  '?',  '/',  '='],
                                ['. ', '+ ', '? ', '/ ', '= '],
                                $row_pv['pv_title']
                            )?>
                        </td>
                        <td><?=str_ireplace('YASBIL_TAB_SWITCH', 'TAB_SWITCH', $row_pv['pv_transition_type'])?></td>
                        <td><?="<b>{$row_pv['pv_search_engine']}</b><br/>{$row_pv['pv_search_query']}"?></td>
                    </tr>
<?php
            } // --------- end pagevisit loop -------------
?>
                    </tbody>
                </table>
            </div> <!-- table wrapper -->

            <script>
                jQuery('#table_pagevisits_<?=$row_s['session_id']?>').DataTable({
                    columnDefs: [{
                        targets: [0, 1, 2],
                        visible: false,
                        searchable: false
                    }],
                    pageLength: 100,
                    dom: 'Blfritip', //https://datatables.net/reference/option/dom
                    buttons: ['copy', 'csv', 'excel'], //'pdf', 'print'],
                    // hide the last few columns, but include in data export

                });
            </script>
<?php

        } // --------- end session loop -------------

?>
        </div> <!--  end .wrap: html render -->
<?php

    } // end render function










//-------------------------- END: HTML Renders --------------------------------







//-------------------------- START: Data Sync Functions --------------------------------


    /**
     * Check if a given request has access to sync yasbil data:
     * check authentication; check if user is active, and assigned to project
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    function yasbil_sync_check_permission( $request )
    {
        /**
         * Restrict YASBIL endpoints to only users who have the read capability (subscriber and above).
         */
        // https://developer.wordpress.org/rest-api/extending-the-rest-api/routes-and-endpoints/#permissions-callback

        $result = false;

        if(current_user_can( 'read' ))
        {
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;

            $user_status = $this->yasbil_get_user_status($user_id);

            //if user is enabled to sync data
            if($user_status === "ENABLED")
            {
                //if user is assigned to a project
                $project_detail = $this->yasbil_get_user_project($user_id);

                // project_id is not the default project (0)
                // ie user is actually assigned to a project
                if($project_detail[0] !== 0)
                    $result = true;
            }
        }


        return $result;
        //or check current user is in term: + taxonomy new permission: 'participant?'

        /***
        if ( ! current_user_can( 'read' ) )
        {
        return new WP_Error( 'rest_forbidden', esc_html__( 'OMG you can not view private data.', 'my-text-domain' ), array( 'status' => 401 ) );
        }

        // This is a black-listing approach. You could alternatively do this via white-listing,
        // by returning false here and changing the permissions check.
        return true;
         *****/
    }



    function yasbil_sync_check_connection( $request )
    {
        $response_body = [];
        $response_body['code'] = 'yasbil_connection_success';

        $response = new WP_REST_Response( $response_body );
        $response->set_status( 201 );

        return $response;
    }




    // single function to sync all tables
    public function yasbil_sync_table( $request )
    {
        /***
        JSON body format:
        {
        'table_name': 'yasbil_session_webnav',
        'client_pk_col': 'webnav_guid',
        'num_rows': 23, (not required)
        'data_rows': [ {row 1 obj}, {row 2 obj}, ... {row n obj}, ]
        }
         */

        try
        {
            //TODO: check authentication (whether participant is active)

            global $wpdb;

            $json_body = $request->get_json_params();

            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            $user_name = $current_user->user_login;

            // sanitize_key(): Keys are used as internal identifiers. Lowercase
            // alphanumeric characters, dashes, and underscores are allowed.
            $tbl_name = $wpdb->prefix . sanitize_text_field($json_body['table_name']);
            $client_pk_col = sanitize_text_field($json_body['client_pk_col']);

            $project_detail = $this->yasbil_get_user_project($user_id);
            $project_id = $project_detail[0];
            $project_name = $project_detail[1];

            $sync_ts = $this->yasbil_get_millitime();
            $data_rows = $json_body['data_rows'];

            // auto increment columns; need not be inserted
            $arr_auto_cols = [
                "session_id",
                "pv_id",
                "m_id",
                "webnav_id",
            ];

            // comma separated list of all columns
            $arr_col_names = $wpdb->get_col("DESC {$tbl_name}", 0);
            $sql_col_csv = ""; //implode( ', ', $arr_col_names );
            $sql_placeholder_csv = "";

            // loop over columns
            foreach ( $arr_col_names as $col_name )
            {
                if(!in_array($col_name, $arr_auto_cols))
                {
                    $sql_col_csv .= "$col_name,";
                    $sql_placeholder_csv .= "%s,";
                }
            }

            //remove last comma(s)
            $sql_col_csv = rtrim($sql_col_csv, ', ');
            $sql_placeholder_csv = rtrim($sql_placeholder_csv, ', ');

            // sql insert statement
            $sql_insert = "INSERT INTO $tbl_name ($sql_col_csv) VALUES";

            $values = array();
            $place_holders = array();

            foreach ( $data_rows as $row )
            {
                // loop over columns
                foreach ( $arr_col_names as $col_name )
                {
                    if(!in_array($col_name, $arr_auto_cols))
                    {
                        switch ($col_name)
                        {
                            case "project_id":
                                $values[] = $project_id;
                                break;
                            case "project_name":
                                $values[] = $project_name;
                                break;
                            case "user_id":
                                $values[] = $user_id;
                                break;
                            case "user_name":
                                $values[] = $user_name;
                                break;
                            case "sync_ts":
                                $values[] = $sync_ts;
                                break;
                            default:
                                //options for sanitizing
                                // sanitize_text_field
                                // sanitize_textarea_field
                                // mysqli_real_escape_string
                                // htmlentities
                                /*function test_input($data) {
                                    $data = trim($data);
                                    $data = stripslashes($data);
                                    $data = htmlspecialchars($data);
                                    return $data;
                                }*/
                                // using "prepare" so perhaps not required
                                $values[] = $row[$col_name];
                                break;
                        }
                    }
                } // end: loop over cols
                $place_holders[] = "($sql_placeholder_csv)";
            } //end: loop over data rows

            $sql_insert .= implode( ', ', $place_holders );
            if( false === $wpdb->query( $wpdb->prepare( "$sql_insert ", $values ) ))
            {
                return new WP_Error('db_query_error', $wpdb->last_error, array('status' => 400));
            }

            $arr_synced_rows = $wpdb->get_results( $wpdb->prepare("
                    SELECT $client_pk_col 
                    FROM $tbl_name 
                    WHERE sync_ts = %s",
                $sync_ts
            ), ARRAY_A);

            $arr_synced_pks = array();

            foreach ($arr_synced_rows as $row) {
                $arr_synced_pks[] = $row[$client_pk_col];
            }

            $return_obj = array();
            $return_obj['sync_ts'] = $sync_ts;
            $return_obj['guids'] = $arr_synced_pks;

            $response = new WP_REST_Response( $return_obj );
            $response->set_status( 201 );

            return $response;

        }
        catch (Exception $e)
        {
            return new rest_ensure_response(WP_Error('wp_exception', $e->getMessage(), array('status' => 400)));
        }
    }
























// ----------- start: getter and setter functions -----------

    // gets user status: ACTIVE / INACTIVE
    function yasbil_get_user_status( $user_id )
    {
        $yasbil_user_status = get_user_meta($user_id, 'yasbil_user_status', true);

        // if meta key is empty, user is active
        if( empty($yasbil_user_status)) {
            return "ENABLED";
        }

        return $yasbil_user_status;
    }

    // sets the user-status as active
    function yasbil_set_user_enabled( $user_id )
    {
        update_user_meta( $user_id, 'yasbil_user_status', 'ENABLED');
    }

    // sets the user-status as active
    function yasbil_set_user_disabled( $user_id )
    {
        update_user_meta( $user_id, 'yasbil_user_status', 'DISABLED');
    }


    // Takes a user_id and returns the [term-id, term-slug]
    // for the single YASBIL project (custom taxonomy)
    function yasbil_get_user_project( $user_id )
    {
        $arr_yasbil_projects = wp_get_object_terms( $user_id,  'yasbil_projects' );

        if ( ! empty( $arr_yasbil_projects ) )
        {
            if ( ! is_wp_error( $arr_yasbil_projects ) )
            {
                $term = $arr_yasbil_projects[0];
                //available properties:
                // // https://developer.wordpress.org/reference/classes/wp_term/
                return array($term->term_id, strtoupper($term->slug));
            }
        }

        return array(0, "DEFAULT_PROJECT");
    }






// ----------- end: getter and setter functions -----------



// ----------- start: utility functions  -----------

    // get current time in milliseconds
    // https://stackoverflow.com/a/3656934
    public function yasbil_get_millitime()
    {
        $microtime = microtime();
        $comps = explode(' ', $microtime);

        // Note: Using a string here to prevent loss of precision
        // in case of "overflow" (PHP converts it to a double)
        return sprintf('%d%03d', $comps[1], $comps[0] * 1000);
    }


    public function yasbil_milli_to_str($milli_time, $tz_offset_mins=0, $incl_day=false)
    {
        //$sec_time = $milli_time / 1000;
        $sec_time = $milli_time / 1000 + $tz_offset_mins*60;

        if($sec_time <= 0)
            return "-";

        if($incl_day)
            return strtoupper(date('Y-m-d, D, H:i:s', $sec_time));

        return date('Y-m-d H:i:s', $sec_time);
    }



    public function yasbil_truncate_str($long_str, $thresh = 30)
    {
        if (strlen($long_str) >= $thresh) {
            return substr($long_str, 0, $thresh-3). "..."; // . substr($long_str, -5);
        }
        else {
            return $long_str;
        }
    }





    //Takes a variable and returns sanitized output / default value
    public function yasbil_nvl($test_val, $default_val)
    {
        if(!isset($test_val) || trim($test_val)==='')
            return $default_val;

        return sanitize_text_field( $test_val );
    }







    // displays difference between two milliseconds in appropriate units
    function yasbil_display_dur_diff($milli_st, $milli_end)
    {
        return $this->yasbil_display_dur($milli_end - $milli_st);

    }


    function yasbil_display_dur($diff_ms)
    {
        $diff_s = $diff_ms / 1000.0;
        $diff_min = $diff_ms / (60 * 1000.0);

        $return_val = "";

        if($diff_s < 60)
            $return_val = sprintf('%.2f sec', $diff_s);
        elseif($diff_s >=60 && $diff_min < 60)
            $return_val = sprintf('%d min %d sec',
                (int)($diff_s) / 60,
                (int)($diff_s) % 60
            );
        else
            $return_val = sprintf('%d hr %d min',
                (int)($diff_min) / 60,
                (int)($diff_min) % 60
            );

        return $return_val;
    }








	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in YASBIL_WP_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The YASBIL_WP_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->yasbil_wp, plugin_dir_url( __FILE__ ) . 'css/yasbil-wp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in YASBIL_WP_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The YASBIL_WP_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->yasbil_wp, plugin_dir_url( __FILE__ ) . 'js/yasbil-wp-admin.js', array( 'jquery' ), $this->version, false );

	}













    /**
     * Unsets the 'posts' page and adds the 'users' page as the parent
     * on the manage YASBIL Projects admin page.
     *
     * from: https://www.nopio.com/blog/add-user-taxonomy-wordpress/
     * if previous fn doesn't work, try this
     * @since    1.0.0
     */
    /*public function yasbil_set_yasbil_projects_submenu_active2( $submenu_file )
    {
        global $parent_file;
        if( 'edit-tags.php?taxonomy=yasbil_projects' == $submenu_file ) {
            $parent_file = 'users.php';
        }
        return $submenu_file;
    }*/






//-------------------------- SESSIONS Sync --------------------------------
//    public function yasbil_sync_sessions_table( $request )
//    {
//        /***
//        JSON body format:
//        {
//        'num_rows': 23, (not required)
//        'data_rows': [ {row 1 obj}, {row 2 obj}, ... {row n obj}, ]
//        }
//         */
//
//        try
//        {
//            $json_body = $request->get_json_params();
//
//            $current_user = wp_get_current_user();
//            $user_id = $current_user->ID;
//            $user_name = $current_user->user_login;
//
//            $project_detail = $this->yasbil_get_user_project($user_id);
//            $project_id = $project_detail[0];
//            $project_name = $project_detail[1];
//
//            $sync_ts = $this->yasbil_get_millitime();
//
//            // $num_rows = $json_body['num_rows'];
//            $data_rows = $json_body['data_rows']; //array
//
//            global $wpdb;
//
//            // to insert multiple rows
//            // https://stackoverflow.com/a/12374838
//            // https://wordpress.stackexchange.com/a/328037
//
//            $tbl_sessions = $wpdb->prefix . "yasbil_sessions";
//            $sql_insert_session = "
//                INSERT INTO $tbl_sessions (
//                    session_guid,
//                    project_id,
//                    project_name,
//                    wp_user_id,
//                    participant_name,
//                    platform_os,
//                    platform_arch,
//                    platform_nacl_arch,
//                    browser_name,
//                    browser_vendor,
//                    browser_version,
//                    browser_build_id,
//                    session_tz_str,
//                    session_tz_offset,
//                    session_start_ts,
//                    session_end_ts,
//                    sync_ts
//                ) VALUES ";
//
//            $values = array();
//            $place_holders = array();
//
//            foreach ( $data_rows as $row )
//            {
//                array_push(
//                    $values,
//                    sanitize_text_field($row['session_guid']),
//                    $project_id,
//                    $project_name,
//                    $user_id,
//                    $user_name,
//                    sanitize_text_field($row['platform_os']),
//                    sanitize_text_field($row['platform_arch']),
//                    sanitize_text_field($row['platform_nacl_arch']),
//                    sanitize_text_field($row['browser_name']),
//                    sanitize_text_field($row['browser_vendor']),
//                    sanitize_text_field($row['browser_version']),
//                    sanitize_text_field($row['browser_build_id']),
//                    sanitize_text_field($row['session_tz_str']),
//                    sanitize_text_field($row['session_tz_offset']),
//                    sanitize_text_field($row['session_start_ts']),
//                    sanitize_text_field($row['session_end_ts']),
//                    $sync_ts
//                );
//                $place_holders[] = "(
//                    %s, %s, %s, %s, %s,
//                    %s, %s, %s, %s, %s,
//                    %s, %s, %s, %s, %s,
//                    %s, %s
//                )";
//            }
//
//            $sql_insert_session .= implode( ', ', $place_holders );
//            if( false === $wpdb->query( $wpdb->prepare( "$sql_insert_session ", $values ) ))
//            {
//                return new WP_Error('db_query_error', $wpdb->last_error, array('status' => 400));
//            }
//
//            $synced_sessions = $wpdb->get_results( $wpdb->prepare("
//                SELECT session_guid
//                FROM $tbl_sessions
//                WHERE sync_ts = %s",
//                $sync_ts
//            ));
//
//            $arr_synced_session_guids = array();
//
//            foreach ($synced_sessions as $row_sess) {
//                $arr_synced_session_guids[] = $row_sess->session_guid;
//            }
//
//            $return_obj = array();
//            $return_obj['sync_ts'] = $sync_ts;
//            $return_obj['guids'] = $arr_synced_session_guids;
//
//            $response = new WP_REST_Response( $return_obj );
//            $response->set_status( 201 );
//
//            return $response;
//        }
//        catch (Exception $e)
//        {
//            return new WP_Error('wp_exception', $e->getMessage(), array('status' => 400));
//        }
//    }








//-------------------------- PAGEVISITS Sync --------------------------------
//    public function yasbil_sync_pagevisits_table( $request )
//    {
//        /***
//         JSON body format:
//         {
//            'num_rows': 23, (not required)
//            'data_rows': [ {row 1 obj}, {row 2 obj}, ... {row n obj}, ]
//         }
//        */
//
//        try
//        {
//            $json_body = $request->get_json_params();
//
//            $current_user = wp_get_current_user();
//            $user_id = $current_user->ID;
//            $user_name = $current_user->user_login;
//
//            $project_detail = $this->yasbil_get_user_project($user_id);
//            $project_id = $project_detail[0];
//            $project_name = $project_detail[1];
//
//            $sync_ts = $this->yasbil_get_millitime();
//
//            // $num_rows = $json_body['num_rows'];
//            $data_rows = $json_body['data_rows']; //array
//
//            global $wpdb;
//
//            // to insert multiple rows
//            // https://stackoverflow.com/a/12374838
//            // https://wordpress.stackexchange.com/a/328037
//
//
//            $tbl_pagevisits = $wpdb->prefix . "yasbil_session_pagevisits";
//            $sql_insert_pv = "
//                INSERT INTO $tbl_pagevisits (
//                    pv_guid,
//                    session_guid,
//                    project_id,
//                    project_name,
//                    wp_user_id,
//                    participant_name,
//                    win_id,
//                    win_guid,
//                    tab_id,
//                    tab_guid,
//                    pv_ts,
//                    pv_url,
//                    pv_title,
//                    title_upd,
//                    pv_hostname,
//                    pv_rev_hostname,
//                    pv_transition_type,
//                    pv_transition_qualifier,
//                    pv_srch_engine,
//                    pv_srch_qry,
//                    sync_ts
//                ) VALUES ";
//
//            $values = array();
//            $place_holders = array();
//
//            foreach ( $data_rows as $row )
//            {
//                array_push(
//                    $values,
//                    sanitize_text_field($row['pv_guid']),
//                    sanitize_text_field($row['session_guid']),
//                    $project_id,
//                    $project_name,
//                    $user_id,
//                    $user_name,
//                    sanitize_text_field($row['win_id']),
//                    sanitize_text_field($row['win_guid']),
//                    sanitize_text_field($row['tab_id']),
//                    sanitize_text_field($row['tab_guid']),
//                    sanitize_text_field($row['pv_ts']),
//                    esc_url_raw($row['pv_url']),
//                    sanitize_text_field($row['pv_title']),
//                    sanitize_text_field($row['title_upd']),
//                    sanitize_text_field($row['pv_hostname']),
//                    sanitize_text_field($row['pv_rev_hostname']),
//                    sanitize_text_field($row['pv_transition_type']),
//                    sanitize_text_field($row['pv_transition_qualifier']),
//                    sanitize_text_field($row['pv_srch_engine']),
//                    sanitize_text_field($row['pv_srch_qry']),
//                    $sync_ts
//                );
//                $place_holders[] = "(
//                    %s, %s, %s, %s, %s,
//                    %s, %s, %s, %s, %s,
//                    %s, %s, %s, %s, %s,
//                    %s, %s, %s, %s, %s,
//                    %s
//                )";
//            }
//
//            $sql_insert_pv .= implode( ', ', $place_holders );
//            if( false === $wpdb->query( $wpdb->prepare( "$sql_insert_pv ", $values ) ))
//            {
//                return new WP_Error('db_query_error', $wpdb->last_error, array('status' => 400));
//            }
//
//            $synced_sessions = $wpdb->get_results( $wpdb->prepare("
//                SELECT pv_guid
//                FROM $tbl_pagevisits
//                WHERE sync_ts = %s",
//                $sync_ts
//            ));
//
//            $arr_synced_pv_guids = array();
//
//            foreach ($synced_sessions as $row_sess) {
//                $arr_synced_pv_guids[] = $row_sess->pv_guid;
//            }
//
//            $return_obj = array();
//            $return_obj['sync_ts'] = $sync_ts;
//            $return_obj['guids'] = $arr_synced_pv_guids;
//
//            $response = new WP_REST_Response( $return_obj );
//            $response->set_status( 201 );
//
//            return $response;
//        }
//        catch (Exception $e)
//        {
//            return new WP_Error('wp_exception', $e->getMessage(), array('status' => 400));
//        }
//    }





}
