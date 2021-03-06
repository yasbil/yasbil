<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    YASBIL_WP
 * @subpackage YASBIL_WP/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    YASBIL_WP
 * @subpackage YASBIL_WP/includes
 * @author     Nilavra Bhattacharya <nilavra@ieee.org>
 */


class YASBIL_WP_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
    {
        //-------------- create db tables -----------------
        global $wpdb;
        global $yasbil_wp_db_version;

        $tbl_sessions = $wpdb->prefix . "yasbil_sessions";
        $tbl_pagevisits = $wpdb->prefix . "yasbil_session_pagevisits";
        $tbl_mouse = $wpdb->prefix . "yasbil_session_mouse";
        $tbl_serp = $wpdb->prefix . "yasbil_session_serp";
        $tbl_webnav = $wpdb->prefix . "yasbil_session_webnav";
        $tbl_largestring = $wpdb->prefix . "yasbil_largestring";

        $charset_collate = $wpdb->get_charset_collate();

        // no comments allowed by dbdelta -__-

        $sql_create_tbl_sessions = "CREATE TABLE $tbl_sessions (
            session_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            session_guid varchar(50) NOT NULL,
            project_id int(11) NULL DEFAULT NULL,
            project_name varchar(50) NULL DEFAULT NULL,
            user_id bigint(20) unsigned NOT NULL,
           	user_name varchar(50) NOT NULL,
            platform_os varchar(50) NULL DEFAULT NULL,
            platform_arch varchar(50) NULL DEFAULT NULL,
            platform_nacl_arch varchar(50) NULL DEFAULT NULL,
            browser_name varchar(50) NULL DEFAULT NULL,
            browser_vendor varchar(50) NULL DEFAULT NULL,
            browser_version varchar(50) NULL DEFAULT NULL,
            browser_build_id varchar(50) NULL DEFAULT NULL,
            session_tz_str varchar(50) NULL DEFAULT NULL,
            session_tz_offset smallint(6) NULL DEFAULT NULL,
            session_start_ts bigint(20) unsigned NOT NULL,
            session_end_ts bigint(20) unsigned NULL,
            sync_ts bigint(20) unsigned NOT NULL,
            PRIMARY KEY  (session_id),
            KEY session_guid (session_guid),
            KEY project_id (project_id),
            KEY project_name (project_name),
	        KEY user_id (user_id),
	        KEY user_name (user_name)
        ) $charset_collate;";


        $sql_create_tbl_pagevisits = "CREATE TABLE $tbl_pagevisits (
            pv_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            pv_guid varchar(50) NOT NULL,
            session_guid varchar(50) NOT NULL,
            
            project_id int(11) NULL DEFAULT NULL,
            project_name varchar(50) NULL DEFAULT NULL,
            user_id bigint(20) unsigned NOT NULL,
           	user_name varchar(50) NOT NULL,
            
            win_id int(11) NULL DEFAULT NULL,
            win_guid varchar(50) NULL DEFAULT NULL,
            tab_id int(11) NULL DEFAULT NULL,
            tab_guid varchar(50) NULL DEFAULT NULL,
            tab_width int(11) NULL DEFAULT NULL,
            tab_height int(11) NULL DEFAULT NULL,
            
            pv_ts bigint(20) unsigned NOT NULL,
            pv_event varchar(100) NULL DEFAULT NULL,
            pv_url varchar(500) NULL DEFAULT NULL,
            pv_title varchar(500) NULL DEFAULT NULL,
            pv_hostname varchar(100) NULL DEFAULT NULL,
            pv_rev_hostname varchar(100) NULL DEFAULT NULL,
            pv_transition_type varchar(50) NULL DEFAULT NULL,
            pv_page_text longtext NULL DEFAULT NULL,
            pv_page_html longtext NULL DEFAULT NULL,
            hist_ts bigint(20) unsigned NOT NULL,
            hist_visit_ct int(11) NULL DEFAULT NULL,
            pv_search_engine varchar(50) NULL DEFAULT NULL,
            pv_search_query varchar(500) NULL DEFAULT NULL,
            sync_ts bigint(20) unsigned NOT NULL,            
            
            PRIMARY KEY  (pv_id),
            KEY pv_guid (pv_guid),
            KEY session_guid (session_guid),
            KEY project_id (project_id),
            KEY project_name (project_name),
            KEY user_id (user_id),
	        KEY user_name (user_name),
            KEY win_guid (win_guid),
            KEY tab_guid (tab_guid),
            KEY pv_event (pv_event),
            KEY pv_hostname (pv_hostname),
	        KEY pv_transition_type (pv_transition_type)
        ) $charset_collate;";


        $sql_create_tbl_mouse = "CREATE TABLE $tbl_mouse (
            m_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            m_guid varchar(50) NOT NULL,
            session_guid varchar(50) NOT NULL,
            
            project_id int(11) NULL DEFAULT NULL,
            project_name varchar(50) NULL DEFAULT NULL,
            user_id bigint(20) unsigned NOT NULL,
           	user_name varchar(50) NOT NULL,
            
            win_id int(11) NULL DEFAULT NULL,
            win_guid varchar(50) NULL DEFAULT NULL,
            tab_id int(11) NULL DEFAULT NULL,
            tab_guid varchar(50) NULL DEFAULT NULL,
            
            m_ts bigint(20) unsigned NOT NULL,
            m_event varchar(100) NULL DEFAULT NULL,
            m_url varchar(500) NULL DEFAULT NULL,
            
            zoom float(10,4) NULL DEFAULT NULL,
            page_w int(11) NULL DEFAULT NULL,
            page_h int(11) NULL DEFAULT NULL,
            viewport_w int(11) NULL DEFAULT NULL,
            viewport_h int(11) NULL DEFAULT NULL,
            browser_w int(11) NULL DEFAULT NULL,
            browser_h int(11) NULL DEFAULT NULL,
            page_scrolled_x int(11) NULL DEFAULT NULL,
            page_scrolled_y int(11) NULL DEFAULT NULL,
            
            mouse_x int(11) NULL DEFAULT NULL,
            mouse_y int(11) NULL DEFAULT NULL,
            hover_dur int(11) NULL DEFAULT NULL,
            
            dom_path text NULL DEFAULT NULL,
            target_text longtext NULL DEFAULT NULL,
            target_html longtext NULL DEFAULT NULL,
            target_width int(11) NULL DEFAULT NULL,
            target_height int(11) NULL DEFAULT NULL,
            
            closest_a_text longtext NULL DEFAULT NULL,
            closest_a_html longtext NULL DEFAULT NULL,
            closest_a_width int(11) NULL DEFAULT NULL,
            closest_a_height int(11) NULL DEFAULT NULL,
            
            scroll_x_delta int(11) NULL DEFAULT NULL,
            scroll_y_delta int(11) NULL DEFAULT NULL,
            
            sync_ts bigint(20) unsigned NOT NULL,
                        
            PRIMARY KEY  (m_id),
            KEY m_guid (m_guid),
            KEY session_guid (session_guid),
            KEY project_id (project_id),
            KEY project_name (project_name),
            KEY user_id (user_id),
	        KEY user_name (user_name),
            KEY win_guid (win_guid),
            KEY tab_guid (tab_guid),
            KEY m_event (m_event)
        ) $charset_collate;";


        $sql_create_tbl_serp = "CREATE TABLE $tbl_serp (
            serp_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            serp_guid varchar(50) NOT NULL,
            session_guid varchar(50) NOT NULL,
            
            project_id int(11) NULL DEFAULT NULL,
            project_name varchar(50) NULL DEFAULT NULL,
            user_id bigint(20) unsigned NOT NULL,
           	user_name varchar(50) NOT NULL,
            
            win_id int(11) NULL DEFAULT NULL,
            win_guid varchar(50) NULL DEFAULT NULL,
            tab_id int(11) NULL DEFAULT NULL,
            tab_guid varchar(50) NULL DEFAULT NULL,
            
            serp_ts bigint(20) unsigned NOT NULL,
            serp_url varchar(500) NULL DEFAULT NULL,
            search_engine varchar(50) NULL DEFAULT NULL,
            search_query varchar(500) NULL DEFAULT NULL,
            serp_offset int(11) NULL DEFAULT NULL,
            
            scraped_json_arr longtext NULL DEFAULT NULL,
            
            zoom float(10,4) NULL DEFAULT NULL,
            page_w int(11) NULL DEFAULT NULL,
            page_h int(11) NULL DEFAULT NULL,
            viewport_w int(11) NULL DEFAULT NULL,
            viewport_h int(11) NULL DEFAULT NULL,
            browser_w int(11) NULL DEFAULT NULL,
            browser_h int(11) NULL DEFAULT NULL,
            page_scrolled_x int(11) NULL DEFAULT NULL,
            page_scrolled_y int(11) NULL DEFAULT NULL,
            
            sync_ts bigint(20) unsigned NOT NULL,            
            
            PRIMARY KEY  (serp_id),
            KEY serp_guid (serp_guid),
            KEY session_guid (session_guid),
            KEY project_id (project_id),
            KEY project_name (project_name),
            KEY user_id (user_id),
	        KEY user_name (user_name),
            KEY win_guid (win_guid),
            KEY tab_guid (tab_guid)
        ) $charset_collate;";


        $sql_create_tbl_webnav = "CREATE TABLE $tbl_webnav (
            webnav_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            webnav_guid varchar(50) NOT NULL,
            session_guid varchar(50) NOT NULL,
            
            project_id int(11) NULL DEFAULT NULL,
            project_name varchar(50) NULL DEFAULT NULL,
            user_id bigint(20) unsigned NOT NULL,
           	user_name varchar(50) NOT NULL,
            
            tab_id int(11) NULL DEFAULT NULL,
            tab_guid varchar(50) NULL DEFAULT NULL,
            
            webnav_ts bigint(20) unsigned NOT NULL,
            webnav_event varchar(100) NULL DEFAULT NULL,
            webnav_url varchar(500) NULL DEFAULT NULL,
            webnav_transition_type varchar(50) NULL DEFAULT NULL,
            webnav_transition_qual varchar(50) NULL DEFAULT NULL,
            
            sync_ts bigint(20) unsigned NOT NULL,            
            
            PRIMARY KEY  (webnav_id),
            KEY webnav_guid (webnav_guid),
            KEY session_guid (session_guid),
            KEY project_id (project_id),
            KEY project_name (project_name),
            KEY user_id (user_id),
	        KEY user_name (user_name),
            KEY tab_guid (tab_guid),
            KEY webnav_event (webnav_event),
            KEY webnav_transition_type (webnav_transition_type)
        ) $charset_collate;";


        $sql_create_tbl_largestring = "CREATE TABLE $tbl_largestring (
            string_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            string_guid varchar(50) NOT NULL,
            
            project_id int(11) NULL DEFAULT NULL,
            project_name varchar(50) NULL DEFAULT NULL,
            user_id bigint(20) unsigned NULL DEFAULT NULL,
           	user_name varchar(50) NULL DEFAULT NULL,
            
            src_url varchar(500) NULL DEFAULT NULL,
            string_body longtext NULL DEFAULT NULL,
            sync_ts bigint(20) unsigned NOT NULL,            
            
            PRIMARY KEY  (string_id),
            KEY string_guid (string_guid),
            KEY project_id (project_id),
            KEY project_name (project_name),
            KEY user_id (user_id),
	        KEY user_name (user_name)
        ) $charset_collate;";





        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_create_tbl_sessions );
        dbDelta( $sql_create_tbl_pagevisits );
        dbDelta( $sql_create_tbl_mouse );
        dbDelta( $sql_create_tbl_webnav );
        dbDelta( $sql_create_tbl_serp );
        dbDelta( $sql_create_tbl_largestring );

        add_option( 'yasbil_wp_db_version', $yasbil_wp_db_version );
    }

}


/****************


    $sql_create_tbl_sessions = "CREATE TABLE $tbl_sessions (
        session_id bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Session ID in Server',
        session_guid varchar(50) NOT NULL COMMENT 'Session GUID from client',

        project_id int: ID of term in yasbil_projects taxonomy 1,2 etc
        project_name str  : taxonomy slug , uppercase e.g. SAL_LONGITUD'

        --- project_id varchar(50) NULL DEFAULT NULL COMMENT 'Project ID/Name e.g. SAL_LONGITUD', -- use First Name of user field? or custom role?

        wp_userid bigint(20) unsigned NOT NULL COMMENT 'WordPress User ID of participant, from users.ID',
        participant_name varchar(50) NOT NULL COMMENT 'Codename for Participant; WP Username? (maybe in format: PXXXX_RRRR e.g P2-glm, P101-n90 (random suffix, to prevent brute force login)',

        3 platform info properties
        4 browser info properties (active only in FF)

        session_device varchar(50) NULL DEFAULT NULL COMMENT 'Mac/Windows/Android/iPhone etc',
        session_ua varchar(50) NULL DEFAULT NULL COMMENT 'User-Agent (browser type / version?)',


        session_tz_str varchar(50) NULL DEFAULT NULL COMMENT 'timezone string, e.g., America/Chicago',
        session_tz_offset smallint(6) NULL DEFAULT NULL COMMENT 'UTC +/- X, in minutes',
        session_start_ts bigint(20) unsigned NOT NULL COMMENT 'session start time; no. of MILLIseconds since 1970/01/01; from JavaScript',
        session_end_ts bigint(20) unsigned NULL COMMENT 'session end time; milliseconds; from JavaScript',
        sync_ts bigint(20) unsigned NOT NULL COMMENT 'sync time in server; milliseconds; const value for a bunch of rows; from PHP;',
        PRIMARY KEY  (session_id),
        UNIQUE KEY session_guid (session_guid),
        KEY project_id (project_id),
        KEY user_id (wp_userid),
        KEY participant_name (participant_name)
    ) $charset_collate;";

    $sql_create_tbl_pagevisits = "CREATE TABLE $tbl_pagevisits (
        pv_id bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PageVisit ID in Server',
        pv_guid varchar(50) NOT NULL COMMENT 'PageVisit GUID from client',
        session_guid varchar(50) NOT NULL COMMENT 'Session GUID from client; Use for Joining with sessions table',

        ------ redundant -- for easy query -------
        project_id int: ID of term in yasbil_projects taxonomy 1,2 etc
        project_name str  : taxonomy slug , uppercase e.g. SAL_LONGITUD'
        wp_userid bigint(20) unsigned NOT NULL COMMENT 'WordPress User ID of participant, from users.ID',
        participant_name varchar(50) NOT NULL COMMENT 'Codename for Participant; WP Username? (maybe in format: PXXXX_RRRR e.g P2-glm, P101-n90 (random suffix, to prevent brute force login)',
        ------ redundant -- for easy query -------

        win_id int(11) NULL DEFAULT NULL COMMENT 'Numeric Window ID by Extension API',
        win_guid varchar(50) NULL DEFAULT NULL COMMENT 'GUID for Window',
        tab_id int(11) NULL DEFAULT NULL COMMENT 'Numeric Tab ID by Extension API',
        tab_guid varchar(50) NULL DEFAULT NULL COMMENT 'GUID for Tab',
        pv_ts bigint(20) unsigned NOT NULL COMMENT 'PageVisit time; no. of MILLIseconds since 1970/01/01; from JavaScript',
        pv_url varchar(500) NULL DEFAULT NULL COMMENT 'URL of Page',
        pv_title varchar(500) NULL DEFAULT NULL COMMENT 'Title of Page',
        title_upd tinyint(4) NULL DEFAULT NULL COMMENT 'Whether the Title was updated from URL',
        pv_hostname varchar(100) NULL DEFAULT NULL COMMENT 'hostname of page',
        pv_rev_hostname varchar(100) NULL DEFAULT NULL COMMENT 'hostname reversed; for easy lookup of TLD types visited?',
        pv_transition_type varchar(50) NULL DEFAULT NULL COMMENT 'Cause of the navigation: https://developer.mozilla.org/en-US/docs/Mozilla/Add-ons/WebExtensions/API/webNavigation/TransitionType',
        pv_transition_qualifier varchar(100) NULL DEFAULT NULL COMMENT 'Extra information about a page visit transition. https://developer.mozilla.org/en-US/docs/Mozilla/Add-ons/WebExtensions/API/webNavigation/TransitionQualifier',
        pv_srch_engine varchar(50) NULL DEFAULT NULL COMMENT 'search engine name identified, if any',
        pv_srch_qry varchar(500) NULL DEFAULT NULL COMMENT 'search query identified, if any',
        sync_ts bigint(20) unsigned NOT NULL COMMENT 'sync time in server; milliseconds; const value for a bunch of rows; from PHP;',
        PRIMARY KEY  (pv_id),
        UNIQUE KEY pv_guid (pv_guid),
        KEY session_guid (session_guid),
        KEY win_guid (win_guid),
        KEY tab_guid (tab_guid),
        KEY pv_hostname (pv_hostname),
        KEY pv_transition_type (pv_transition_type)
    ) $charset_collate;";

 */