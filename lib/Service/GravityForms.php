<?php

namespace Theme\Service;

use Theme\Singleton;
use GFAPI;

class GravityForms extends Singleton
{
	protected $inputFiltering = true;

	protected function __construct()
	{
		// we need to add "inputName" attributes
		add_filter( 'gform_field_content', [$this, 'addInputName'], 10, 5 );
		add_filter( 'gform_merge_tag_filter', [$this, 'mergeTagFilter'], 10, 5 );
		add_filter( 'gform_replace_merge_tags', [$this, 'storePhoneMergeTag'], 10, 7);
		add_filter( 'gform_get_input_value', [$this, 'getInputValueFilter'], 10, 4 );
		// add_filter( 'gform_notification', [$this, 'routeAdminEmails'], 10, 3 );
	}

	public function addInputName( $content, $field, $value, $lead_id, $form_id )
	{
		$id = $field['id'];
		if( !empty($field['inputs']) ){
			foreach( $field['inputs'] as $input ){
				if( !empty( $input['name'] ) ){
					$content = $this->replace( $input['id'], $input['name'], $content );
				}
			}
		}
		else {
			if( !empty( $field['inputName'] ) ){
				$content = $this->replace( $id, $field['inputName'], $content );
			}
		}
		return $content;
	}

	public function replace( $id, $name, $content )
	{
		$id = preg_quote( $id, '/' );
		$re = "/(name=['\"]input_{$id}['\"])/";
		return preg_replace( $re, '${1} data-input-name=\''.esc_attr($name).'\'', $content );
	}

	public function mergeTagFilter( $value, $merge_tag, $modifier, $field, $raw_value )
	{
		if( $field->inputName == 'location' && is_numeric( $value ) ){
			return get_post( $value )->post_title;
		}
		return $value;
	}

	public function getInputValueFilter($value, $entry, $field, $input_id)
	{
		if( !$this->inputFiltering ) return $value;
		if( is_numeric($value) && $field->inputName == 'location' ){
			return get_post( $value )->post_title;
		}
		return $value;
	}

	public function routeAdminEmails( $notification, $form, $entry )
	{
		if( !preg_match('/^internal\:/i', $notification['name'] ) ){
			error_log( 'not routing notification: '.$notification['name'] );
			return $notification;
		}

		error_log( 'should be routing notification: '.$notification['name'] );

		// check for a location field
		foreach( $form['fields'] as $field ){

			if( $field['inputName'] == 'location' ){
				$this->inputFiltering = false;
				$rawEntry = GFAPI::get_entry( $entry['id'] );
				$this->inputFiltering = true;
				$email = get_field( 'email', $rawEntry[$field['id']] );
				if( $email ){
					if( !get_field( 'development_mode', 'theme' ) ){
						$notification['to'] = $email;
					}
					else {
						$notification['message'].="\n\n<hr />Dev Note: this would send to ${email}";
					}
				}
				break;
			}

			if( $field['inputName'] == 'designer_id' ){
				$email = get_field('email', $entry[$field['id']] );
				if( $email ){
					if( !get_field( 'development_mode', 'theme' ) ){
						$notification['to'] = $email;
					}
					else {
						$notification['message'].="\n\n<hr />Dev Note: this would send to ${email}";
					}
				}
				break;
			}
		}
		error_log( print_r( $notification, 1 ) );
		return $notification;
	}

	public function storePhoneMergeTag($text, $form, $entry, $url_encode, $esc_html, $nl2br, $format)
	{
		$custom_merge_tag = '{store_phone}';

	    if ( strpos( $text, $custom_merge_tag ) === false ) {
	        return $text;
	    }

		$phone = '';
		// check for a location field
		foreach( $form['fields'] as $field ){

			if( $field['inputName'] == 'location' ){
				$this->inputFiltering = false;
				$rawEntry = GFAPI::get_entry( $entry['id'] );
				$this->inputFiltering = true;
				$phone = get_field( 'phone', $rawEntry[$field['id']] );
				break;
			}
		}

	    $text = str_replace( $custom_merge_tag, $phone, $text );

	    return $text;
	}
}
