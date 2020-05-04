<?php

namespace SLB_API\Controller;

use WP_REST_Controller;
use WP_Error;
use SLB_API\Plugin;

abstract class REST_Controller extends WP_REST_Controller
{
    protected $namespace = Plugin::BASE_API;

    public function permissions_check($capability, $object_id = 0)
    {
        $object       = get_post_type_object(static::POST_TYPE);
        $capabilities = is_null($object) ? array() : (array)$object->cap;

        return current_user_can( isset($capabilities[$capability]) ? $capabilities[$capability] : '', $object_id );
    }

    protected function success_response(array $response = array())
    {
        return rest_ensure_response(array_merge(array(
            'status' => 'OK',
        ), $response));
    }

    protected function save_item_image($image_url = '', $id = 0)
    {
        if (!$image_url) {
            delete_post_thumbnail($id);
            return;
        }

        $filename  = basename($image_url);

        $uploaddir  = wp_upload_dir();
        $uploadfile = $uploaddir['path'] . '/' . $filename;

        $contents = file_get_contents($image_url);
        $savefile = fopen($uploadfile, 'w');

        fwrite($savefile, $contents);
        fclose($savefile);

        $wp_filetype = wp_check_filetype(basename($filename), null);

        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => $filename,
            'post_content'   => '',
            'post_status'    => 'inherit',
        );

        $attach_id = wp_insert_attachment($attachment, $uploadfile, $id, true);

        if (is_wp_error($attach_id)) {
            throw new \Exception(__( 'Upload image error.', 'salon-booking-system' ));
        }

        $imagenew     = get_post($attach_id);
        $fullsizepath = get_attached_file($imagenew->ID);

        if (!function_exists('wp_generate_attachment_metadata')) {
            include_once ABSPATH . 'wp-admin/includes/image.php';
        }

        $attach_data  = wp_generate_attachment_metadata($attach_id, $fullsizepath);
        wp_update_attachment_metadata($attach_id, $attach_data);

        set_post_thumbnail($id, $attach_id);
    }

    public function rest_validate_not_empty_string($value, $request, $param)
    {
        $result = rest_validate_request_arg($value, $request, $param);

        if ($result !== true) {
            return $result;
        }

        if (trim($value) === '') {
            return new WP_Error( 'rest_invalid_param', sprintf( __( '%1$s is empty.' ), $param ) );
        }

        return true;
    }

    public function rest_validate_request_arg($value, $request, $param)
    {
        $result = rest_validate_request_arg($value, $request, $param);

        if ($result !== true) {
            return $result;
        }

        $attributes = $request->get_attributes();

	if ( ! isset( $attributes['args'][ $param ] ) || ! is_array( $attributes['args'][ $param ] ) ) {
            return true;
	}

        return $this->rest_validate_value_from_schema($value, $attributes['args'][ $param ], $param);
    }

    protected function rest_validate_value_from_schema( $value, $args, $param = '' )
    {
        if ( 'array' === $args['type'] ) {
            foreach ( $value as $index => $v ) {
                $is_valid = $this->rest_validate_value_from_schema( $v, $args['items'], $param . '[' . $index . ']' );
                if ( is_wp_error( $is_valid ) ) {
                        return $is_valid;
                }
            }
	}

        if ( 'object' === $args['type'] ) {

            if ( $value instanceof stdClass ) {
                $value = (array) $value;
            }

            foreach ( $value as $property => $v ) {
                if ( isset( $args['properties'][ $property ] ) ) {
                    $is_valid = $this->rest_validate_value_from_schema( $v, $args['properties'][ $property ], $param . '[' . $property . ']' );
                    if ( is_wp_error( $is_valid ) ) {
                        return $is_valid;
                    }
                }
            }
	}

        if ( isset( $args['format'] ) ) {
            switch ( $args['format'] ) {
                case 'YYYY-MM-DD' :
                    if ( !  preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) || ! strtotime($value) ) {
                        return new WP_Error( 'rest_invalid_date', __( sprintf('%s is invalid date.', $param), 'salon-booking-system' ) );
                    }
                    break;
                case 'HH:ii' :
                    if ( ! preg_match('/^\d{2}:\d{2}$/', $value) || ! strtotime($value) ) {
                        return new WP_Error( 'rest_invalid_time', __( sprintf('%s is invalid time.', $param), 'salon-booking-system' ) );
                    }
                    break;
                case 'YYYY-MM-DD HH:ii:ss' :
                    if ( !  preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value) || ! strtotime($value) ) {
                        return new WP_Error( 'rest_invalid_date_time', __( sprintf('%s is invalid date/time.', $param), 'salon-booking-system' ) );
                    }
                    break;
            }
	}

        return true;
    }

    public function get_items_permissions_check( $request )
    {
        if ( ! $this->permissions_check( 'read' ) ) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, you cannot list resources.', 'salon-booking-system' ), array( 'status' => rest_authorization_required_code() ) );
        }

        return true;
    }

    public function create_item_permissions_check( $request )
    {
        if ( ! $this->permissions_check( 'create_posts' ) ) {
            return new WP_Error( 'salon_rest_cannot_create', __( 'Sorry, you cannot create resource.', 'salon-booking-system' ), array( 'status' => rest_authorization_required_code() ) );
        }

        return true;
    }

    public function get_item_permissions_check( $request )
    {
        if ( ! $this->permissions_check( 'read' ) ) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, you cannot view resource.', 'salon-booking-system' ), array( 'status' => rest_authorization_required_code() ) );
        }

        return true;
    }

    public function update_item_permissions_check( $request )
    {
        if ( ! $this->permissions_check( 'edit_posts' ) ) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, you cannot update resource.', 'salon-booking-system' ), array( 'status' => rest_authorization_required_code() ) );
        }

        return true;
    }

    public function delete_item_permissions_check( $request )
    {
        if ( ! $this->permissions_check( 'delete_posts' )) {
            return new WP_Error( 'salon_rest_cannot_view', __( 'Sorry, you cannot delete resource.', 'salon-booking-system' ), array( 'status' => rest_authorization_required_code() ) );
        }

        return true;
    }

}