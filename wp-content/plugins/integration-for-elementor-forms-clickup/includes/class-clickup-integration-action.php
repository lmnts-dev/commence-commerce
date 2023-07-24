<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

class Clickup_Integration_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {

	/**
	 * Get Name
	 *
	 * Return the action name
	 *
	 * @access public
	 * @return string
	 */
	public function get_name() {
		return 'clickup integration';
	}

	/**
	 * Get Label
	 *
	 * Returns the action label
	 *
	 * @access public
	 * @return string
	 */
	public function get_label() {
		return __( 'Clickup', 'clickup-elementor-integration' );
	}

	/**
	 * Register Settings Section
	 *
	 * Registers the Action controls
	 *
	 * @access public
	 * @param \Elementor\Widget_Base $widget
	 */
	public function register_settings_section( $widget ) {
		$widget->start_controls_section(
			'section_clickup-elementor-integration',
			[
				'label' => __( 'Clickup', 'clickup-elementor-integration' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'clickup_api',
			[
				'label' => __( 'Clickup API key', 'clickup-elementor-integration' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => ' pk_2364675869_PCO4KHGSFGKSGBPT8E0SI...',
				'label_block' => true,
				'separator' => 'before',
				'description' => __( 'Enter your API key from Clickup. You can create one <a href="https://app.clickup.com/settings/apps" target="_blank">here</a>. Under Personal access token click copy and enter the token here.', 'clickup-elementor-integration' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			'clickup_list_id',
			[
				'label' => __( 'Clickup list ID', 'clickup-elementor-integration' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '785134567',
				'description' => __( 'Enter the list id - you can find this at the end of the url when your in the list in clickup for example: https://app.clickup.com/123456/v/l/li/<b>785174567</b>. Or when the list is in a folder copy the value in bold: https://app.clickup.com/123456/v/l/6-<b>78513115</b>-1. Copy the list id value here', 'clickup-elementor-integration' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			'clickup_assignee_id',
			[
				'label' => __( 'Clickup assignee ID', 'clickup-elementor-integration' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => '1515676',
				'description' => __( 'Enter the assignee id - you can find this <a href="https://app.clickup.com/settings/team/users" target="_blank">here</a> when logged in Clickup. Click on the three dots on the user and click on copy member ID. Paste the value here', 'clickup-elementor-integration' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			'clickup_notify_all',
			[
				'label' => __( 'Send notifications', 'clickup-elementor-integration' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$widget->add_control(
			'clickup_task_name_field',
			[
				'label' => __( 'Task name field ID', 'clickup-elementor-integration' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'taskname',
				'separator' => 'before',
				'description' => __( 'Enter the elementor form task name field id - you can find this under the elementor form field advanced tab for example "name".', 'clickup-elementor-integration' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			'clickup_task_description_field',
			[
				'label' => __( 'Task description field ID (Optional)', 'clickup-elementor-integration' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => 'taskdescription',
				'description' => __( 'Enter the elementor form task description field id - you can find this under the elementor form field advanced tab for example "message".', 'clickup-elementor-integration' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			'clickup_due_date',
			[
				'label' => __( 'Due date', 'clickup-elementor-integration' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '30',
				'description' => __( 'Enter the amount of days for the task due date. - for example when set to 30. Due date will be set today + 30 days. When set to 0 or empty it will be set to today', 'clickup-elementor-integration' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$widget->add_control(
			'need_help_note',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __('Need help? <a href="https://plugins.webtica.be/support/?ref=plugin-widget" target="_blank">Check out our support page.</a>', 'clickup-elementor-integration'),
			]
		);

		$widget->end_controls_section();

	}

	/**
	 * On Export
	 *
	 * Clears form settings on export
	 * @access Public
	 * @param array $element
	 */
	public function on_export( $element ) {
		unset(
			$element['clickup_api'],
			$element['clickup_list_id'],
			$element['clickup_assignee_id'],
			$element['clickup_task_name_field'],
			$element['clickup_task_description_field'],
			$element['clickup_due_date'],
			$element['clickup_notify_all']
		);

		return $element;
	}

	/**
	 * Run
	 *
	 * Runs the action after submit
	 *
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 */
	public function run( $record, $ajax_handler ) {
		$settings = $record->get( 'form_settings' );

		//  Make sure that there is an clickup API key set
		if ( empty( $settings['clickup_api'] ) ) {
			return;
		}

		//  Make sure that there is an workspace set
		if ( empty( $settings['clickup_list_id'] ) ) {
			return;
		}

		//  Make sure that there is an assignee set
		if ( empty( $settings['clickup_assignee_id'] ) ) {
			return;
		}

		//  Make sure that there is a task name set
		if ( empty( $settings['clickup_task_name_field'] ) ) {
			return;
		}

		// Get submitted Form data
		$raw_fields = $record->get( 'fields' );

		// Normalize the Form Data
		$fields = [];
		foreach ( $raw_fields as $id => $field ) {
			$fields[ $id ] = $field['value'];
		}

		//Set notifications
		$notifications = $settings['clickup_notify_all'];
		if ($notifications == "yes") {
			$notification = true;
		}
		else {
			$notification = false;
		}

		//Generate due date 
		if (empty($settings['clickup_due_date'])){
			$date = date("Y-m-d");
			$duedate = 1000 * strtotime($date);
		}
		if ($settings['clickup_due_date'] == "0"){
			$date = date("Y-m-d");
			$duedate = 1000 * strtotime($date);
		}
		else {
			$days = $settings['clickup_due_date'];
			$date = date('Y-m-d', strtotime("+$days days"));
			$duedate = 1000 * strtotime($date);
		}

		//Generate array to send
		$datatosend = [
			"name" => $fields[$settings['clickup_task_name_field']], 
			"description" => $fields[$settings['clickup_task_description_field']], 
			"assignees" => [
				$settings['clickup_assignee_id'] 
			   ], 
			"due_date" => $duedate, 
			"due_date_time" => false, 
			"notify_all" => $notification 
		]; 
		
		//Url to send to 
		$url = 'https://api.clickup.com/api/v2/list/'. $settings['clickup_list_id'] .'/task';

		//Send data to clickup
		$response = wp_remote_post( $url, array(
			'method'      => 'POST',
		    'timeout'     => 45,
		    'httpversion' => '1.0',
		    'blocking'    => false,
		    'headers'     => [
	            'accept' => 'application/json',
		    	'content-Type' => 'application/json',
	            'Authorization' => $settings['clickup_api'],
		    ],
		    'body'        => json_encode($datatosend)
			)
		);	
		
	}
}