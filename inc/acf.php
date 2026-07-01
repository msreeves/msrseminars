<?php
/**
 * ACF options page and local fields — MSR Seminars site copy (hero stays on Seminar Information).
 *
 * @package msrseminars
 */

/**
 * @return void
 */
function msrseminars_register_acf_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'MSR Seminars settings', 'msrseminars' ),
			'menu_title' => __( 'MSR Seminars', 'msrseminars' ),
			'menu_slug'  => 'msr-seminars-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
			'icon_url'   => 'dashicons-welcome-learn-more',
			'position'   => 58,
		)
	);
}
add_action( 'acf/init', 'msrseminars_register_acf_options_page' );

/**
 * @return void
 */
function msrseminars_register_acf_options_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'    => 'group_msr_seminars_programme_urls',
			'title'  => 'Programme URLs',
			'fields' => array(
				array(
					'key'   => 'field_msr_sem_opt_hub_url',
					'label' => 'MSR Events hub URL',
					'name'  => 'msr_programme_hub_url',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_msr_sem_opt_awards_url',
					'label' => 'MSR Awards URL',
					'name'  => 'msr_programme_awards_url',
					'type'  => 'url',
				),
				array(
					'key'   => 'field_msr_sem_opt_publishing_url',
					'label' => 'Atlas Briefing URL',
					'name'  => 'msr_programme_publishing_url',
					'type'  => 'url',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'msr-seminars-settings',
					),
				),
			),
		)
	);

	acf_add_local_field_group(
		array(
			'key'    => 'group_msr_seminars_site_copy',
			'title'  => 'Site copy',
			'fields' => array(
				array(
					'key'   => 'field_msr_sem_ecosystem_title',
					'label' => 'Ecosystem band title',
					'name'  => 'ecosystem_band_title',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_msr_sem_ecosystem_lead',
					'label' => 'Ecosystem band lead',
					'name'  => 'ecosystem_band_lead',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'   => 'field_msr_sem_agenda_lead',
					'label' => 'Agenda page lead',
					'name'  => 'agenda_page_lead',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'   => 'field_msr_sem_panelists_lead',
					'label' => 'Panelists page lead',
					'name'  => 'panelists_page_lead',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'   => 'field_msr_sem_partners_lead',
					'label' => 'Partners page lead',
					'name'  => 'partners_page_lead',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'   => 'field_msr_sem_delegates_lead',
					'label' => 'For delegates page lead',
					'name'  => 'delegates_page_lead',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'   => 'field_msr_sem_delegate_journey_lead',
					'label' => 'Delegate journey band lead',
					'name'  => 'delegate_journey_lead',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'           => 'field_msr_sem_footer_demo_toggle',
					'label'         => 'Show footer demo disclaimer',
					'name'          => 'show_footer_demo_note',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 1,
				),
				array(
					'key'   => 'field_msr_sem_footer_demo_text',
					'label' => 'Footer demo disclaimer text',
					'name'  => 'footer_demo_note',
					'type'  => 'text',
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'msr-seminars-settings',
					),
				),
			),
		)
	);

	acf_add_local_field_group(
		array(
			'key'    => 'group_msr_seminars_seo_copy',
			'title'  => 'SEO descriptions',
			'fields' => array(
				array(
					'key'   => 'field_msr_sem_seo_home',
					'label' => 'Home meta description',
					'name'  => 'seo_home_description',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'   => 'field_msr_sem_seo_agenda',
					'label' => 'Agenda page meta description',
					'name'  => 'seo_agenda_description',
					'type'  => 'textarea',
					'rows'  => 2,
				),
				array(
					'key'   => 'field_msr_sem_seo_search',
					'label' => 'Search meta description',
					'name'  => 'seo_search_description',
					'type'  => 'textarea',
					'rows'  => 2,
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'msr-seminars-settings',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'msrseminars_register_acf_options_fields' );
