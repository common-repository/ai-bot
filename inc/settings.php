<?php
namespace AIBOT\Settings;

function setup() {
	global $pagenow;

	add_action( 'admin_menu', __NAMESPACE__ . '\\ai_bot_settings_add_plugin_page' );
	add_action( 'admin_init', __NAMESPACE__ . '\\ai_bot_settings_page_init' );

	// Hooks for Plugins overview page
	if ( $pagenow === 'plugins.php' ) {
		add_filter( 'plugin_action_links_' . AIBOT_PLUGIN_FILE, __NAMESPACE__ . '\\add_plugin_settings_link', 10, 2 );
	}
}

function ai_bot_settings_add_plugin_page() {
	add_options_page(
		'AI Bot Settings', // page_title
		'AI Bot Settings', // menu_title
		'manage_options', // capability
		'aibot-settings', // menu_slug
		__NAMESPACE__ . '\\ai_bot_settings_create_admin_page' // function
	);

}

function ai_bot_settings_create_admin_page() {
	 ?>

	<div class="wrap">
		<h2>AI Bot Settings</h2>

		<form method="post" action="options.php">
			<?php
			settings_fields( 'ai_bot_settings_option_group' );
			do_settings_sections( 'aibot-settings-admin' );
			submit_button();
			?>
		</form>
	</div>
<?php }

function ai_bot_settings_page_init() {
	register_setting(
		'ai_bot_settings_option_group', // option_group
		'ai_bot_settings_option_name', // option_name
		__NAMESPACE__ . '\\ai_bot_settings_sanitize'  // sanitize_callback
	);

	add_settings_section(
		'ai_bot_settings_setting_section', // id
		'Settings', // title
		__NAMESPACE__ . '\\ai_bot_settings_section_info' , // callback
		'aibot-settings-admin' // page
	);

	add_settings_field(
		'api_key', // id
		' API Key', // title
		__NAMESPACE__ . '\\api_key' , // callback
		'aibot-settings-admin', // page
		'ai_bot_settings_setting_section' // section
	);
}

function ai_bot_settings_section_info() {
	echo '<p>' . __('Please add your api key you can find in your account on <a href="https://wpaibot.com/admin/settings" target="_blank">wpaibot.com</a>') . '</p>';
}

function ai_bot_settings_sanitize($input) {
	$sanitary_values = array();
	if ( isset( $input['api_key'] ) ) {
		$sanitary_values['api_key'] = sanitize_text_field( $input['api_key'] );
	}

	return $sanitary_values;
}

function api_key() {
	$option = get_option( 'ai_bot_settings_option_name' );
	printf(
		'<input class="regular-text" type="text" name="ai_bot_settings_option_name[api_key]" id="api_key" value="%s">',
		isset( $option['api_key'] ) ? esc_attr( $option['api_key']) : ''
	);
}


function add_plugin_settings_link( $links, $file ) {
	if ( $file !== AIBOT_PLUGIN_FILE ) {
		return $links;
	}

	$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=aibot-settings' ), esc_html__( 'Settings', 'aibot' ) );
	array_unshift( $links, $settings_link );
	return $links;
}
