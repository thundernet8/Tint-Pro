<?php
/**
 * Copyright (c) 2014-2016, WebApproach.net
 * All right reserved.
 *
 * @since 2.0.0
 * @package Tint
 * @author Zhiyan
 * @date 2016/08/30 20:25
 * @license GPL v3 LICENSE
 * @license uri http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://webapproach.net/tint.html
 */
?>
<?php

/**
 * WP部分REST API相关函数在4.5版本才引入，但是大部分功能在4.4引入，为兼容至4.4，将后引入的函数写在这里备用
 */
if ( ! function_exists( 'rest_get_server' ) ) {
    /**
     * Retrieves the current REST server instance.
     *
     * Instantiates a new instance if none exists already.
     *
     * @since 4.5.0
     *
     * @global WP_REST_Server $wp_rest_server REST server instance.
     *
     * @return WP_REST_Server REST server instance.
     */
    function rest_get_server() {
        /* @var WP_REST_Server $wp_rest_server */
        global $wp_rest_server;

        if ( empty( $wp_rest_server ) ) {
            /**
             * Filter the REST Server Class.
             *
             * This filter allows you to adjust the server class used by the API, using a
             * different class to handle requests.
             *
             * @since 4.4.0
             *
             * @param string $class_name The name of the server class. Default 'WP_REST_Server'.
             */
            $wp_rest_server_class = apply_filters( 'wp_rest_server_class', 'WP_REST_Server' );
            $wp_rest_server = new $wp_rest_server_class;

            /**
             * Fires when preparing to serve an API request.
             *
             * Endpoint objects should be created and register their hooks on this action rather
             * than another action to ensure they're only loaded when needed.
             *
             * @since 4.4.0
             *
             * @param WP_REST_Server $wp_rest_server Server object.
             */
            do_action( 'rest_api_init', $wp_rest_server );
        }

        return $wp_rest_server;
    }
}

if ( ! function_exists( 'wp_parse_slug_list' ) ) {
    /**
     * Clean up an array, comma- or space-separated list of slugs.
     *
     * @since
     *
     * @param  array|string $list List of slugs.
     * @return array Sanitized array of slugs.
     */
    function wp_parse_slug_list( $list ) {
        if ( ! is_array( $list ) ) {
            $list = preg_split( '/[\s,]+/', $list );
        }

        foreach ( $list as $key => $value ) {
            $list[ $key ] = sanitize_title( $value );
        }

        return array_unique( $list );
    }
}

/**
 * Integration points with WordPress core that won't ever be committed
 */

/**
 * Inject `parent__in` and `parent__not_in` vars to avoid bad cache
 *
 * @param   object  $query  WP_Query
 * @see https://core.trac.wordpress.org/ticket/35677
 */
function wp_api_comment_query_vars( $query ) {
    $query->query_var_defaults['parent__in'] = array();
    $query->query_var_defaults['parent__not_in'] = array();
}
add_action( 'pre_get_comments', 'wp_api_comment_query_vars' );

/**
 * 为一些post type注册额外参数
 *
 * These attributes will eventually be committed to core.
 *
 * @since 4.4.0
 *
 * @global array $wp_post_types post type数组
 */
function tt_add_extra_api_post_type_arguments() {
    global $wp_post_types;

    if ( isset( $wp_post_types['post'] ) ) {
        $wp_post_types['post']->show_in_rest = true;
        $wp_post_types['post']->rest_base = 'posts';
        $wp_post_types['post']->rest_controller_class = 'WP_REST_Posts_Controller';
    }

    if ( isset( $wp_post_types['page'] ) ) {
        $wp_post_types['page']->show_in_rest = true;
        $wp_post_types['page']->rest_base = 'pages';
        $wp_post_types['page']->rest_controller_class = 'WP_REST_Posts_Controller';
    }

    if ( isset( $wp_post_types['attachment'] ) ) {
        $wp_post_types['attachment']->show_in_rest = true;
        $wp_post_types['attachment']->rest_base = 'media';
        $wp_post_types['attachment']->rest_controller_class = 'WP_REST_Attachments_Controller';
    }
}
add_filter( 'init', 'tt_add_extra_api_post_type_arguments', 11 );

/**
 * 为一些分类法注册额外的参数
 *
 * These attributes will eventually be committed to core.
 *
 * @since 4.4.0
 *
 * @global array $wp_taxonomies taxonomies
 */
function _add_extra_api_taxonomy_arguments() {
    global $wp_taxonomies;

    if ( isset( $wp_taxonomies['category'] ) ) {
        $wp_taxonomies['category']->show_in_rest = true;
        $wp_taxonomies['category']->rest_base = 'categories';
        $wp_taxonomies['category']->rest_controller_class = 'WP_REST_Terms_Controller';
    }

    if ( isset( $wp_taxonomies['post_tag'] ) ) {
        $wp_taxonomies['post_tag']->show_in_rest = true;
        $wp_taxonomies['post_tag']->rest_base = 'tags';
        $wp_taxonomies['post_tag']->rest_controller_class = 'WP_REST_Terms_Controller';
    }
}
add_action( 'init', '_add_extra_api_taxonomy_arguments', 11 );


if(!function_exists('register_rest_field')):
/**
 * 在一个已存在的WordPress对象类型上添加新的字段
 *
 * @global array $wp_rest_additional_fields 储存已注册字段
 *
 * @param string|array $object_type Object(s) 待注册字段 "post"|"term"|"comment" 等
 * @param string $attribute         属性名
 * @param array  $args {
 *     可选 - 参数数组
 *
 *     @type string|array|null $get_callback    Optional. The callback function used to retrieve the field
 *                                              value. Default is 'null', the field will not be returned in
 *                                              the response.
 *     @type string|array|null $update_callback Optional. The callback function used to set and update the
 *                                              field value. Default is 'null', the value cannot be set or
 *                                              updated.
 *     @type string|array|null $schema          Optional. The callback function used to create the schema for
 *                                              this field. Default is 'null', no schema entry will be returned.
 * }
 */
function register_rest_field( $object_type, $attribute, $args = array() ) {
    $defaults = array(
        'get_callback'    => null,
        'update_callback' => null,
        'schema'          => null,
    );

    $args = wp_parse_args( $args, $defaults );

    global $wp_rest_additional_fields;

    $object_types = (array) $object_type;

    foreach ( $object_types as $object_type ) {
        $wp_rest_additional_fields[ $object_type ][ $attribute ] = $args;
    }
}
endif;


if(!function_exists('rest_validate_request_arg')):
/**
 * Validate a request argument based on details registered to the route.
 *
 * @param  mixed            $value
 * @param  WP_REST_Request  $request
 * @param  string           $param
 * @return WP_Error|boolean
 */
function rest_validate_request_arg( $value, $request, $param ) {

    $attributes = $request->get_attributes();
    if ( ! isset( $attributes['args'][ $param ] ) || ! is_array( $attributes['args'][ $param ] ) ) {
        return true;
    }
    $args = $attributes['args'][ $param ];

    if ( ! empty( $args['enum'] ) ) {
        if ( ! in_array( $value, $args['enum'] ) ) {
            return new WP_Error( 'rest_invalid_param', sprintf( __( '%s is not one of %s' ), $param, implode( ', ', $args['enum'] ) ) );
        }
    }

    if ( 'integer' === $args['type'] && ! is_numeric( $value ) ) {
        return new WP_Error( 'rest_invalid_param', sprintf( __( '%s is not of type %s' ), $param, 'integer' ) );
    }

    if ( 'string' === $args['type'] && ! is_string( $value ) ) {
        return new WP_Error( 'rest_invalid_param', sprintf( __( '%s is not of type %s' ), $param, 'string' ) );
    }

    if ( isset( $args['format'] ) ) {
        switch ( $args['format'] ) {
            case 'date-time' :
                if ( ! rest_parse_date( $value ) ) {
                    return new WP_Error( 'rest_invalid_date', __( 'The date you provided is invalid.' ) );
                }
                break;

            case 'email' :
                if ( ! is_email( $value ) ) {
                    return new WP_Error( 'rest_invalid_email', __( 'The email address you provided is invalid.' ) );
                }
                break;
        }
    }

    if ( in_array( $args['type'], array( 'numeric', 'integer' ) ) && ( isset( $args['minimum'] ) || isset( $args['maximum'] ) ) ) {
        if ( isset( $args['minimum'] ) && ! isset( $args['maximum'] ) ) {
            if ( ! empty( $args['exclusiveMinimum'] ) && $value <= $args['minimum'] ) {
                return new WP_Error( 'rest_invalid_param', sprintf( __( '%s must be greater than %d (exclusive)' ), $param, $args['minimum'] ) );
            } else if ( empty( $args['exclusiveMinimum'] ) && $value < $args['minimum'] ) {
                return new WP_Error( 'rest_invalid_param', sprintf( __( '%s must be greater than %d (inclusive)' ), $param, $args['minimum'] ) );
            }
        } else if ( isset( $args['maximum'] ) && ! isset( $args['minimum'] ) ) {
            if ( ! empty( $args['exclusiveMaximum'] ) && $value >= $args['maximum'] ) {
                return new WP_Error( 'rest_invalid_param', sprintf( __( '%s must be less than %d (exclusive)' ), $param, $args['maximum'] ) );
            } else if ( empty( $args['exclusiveMaximum'] ) && $value > $args['maximum'] ) {
                return new WP_Error( 'rest_invalid_param', sprintf( __( '%s must be less than %d (inclusive)' ), $param, $args['maximum'] ) );
            }
        } else if ( isset( $args['maximum'] ) && isset( $args['minimum'] ) ) {
            if ( ! empty( $args['exclusiveMinimum'] ) && ! empty( $args['exclusiveMaximum'] ) ) {
                if ( $value >= $args['maximum'] || $value <= $args['minimum'] ) {
                    return new WP_Error( 'rest_invalid_param', sprintf( __( '%s must be between %d (exclusive) and %d (exclusive)' ), $param, $args['minimum'], $args['maximum'] ) );
                }
            } else if ( empty( $args['exclusiveMinimum'] ) && ! empty( $args['exclusiveMaximum'] ) ) {
                if ( $value >= $args['maximum'] || $value < $args['minimum'] ) {
                    return new WP_Error( 'rest_invalid_param', sprintf( __( '%s must be between %d (inclusive) and %d (exclusive)' ), $param, $args['minimum'], $args['maximum'] ) );
                }
            } else if ( ! empty( $args['exclusiveMinimum'] ) && empty( $args['exclusiveMaximum'] ) ) {
                if ( $value > $args['maximum'] || $value <= $args['minimum'] ) {
                    return new WP_Error( 'rest_invalid_param', sprintf( __( '%s must be between %d (exclusive) and %d (inclusive)' ), $param, $args['minimum'], $args['maximum'] ) );
                }
            } else if ( empty( $args['exclusiveMinimum'] ) && empty( $args['exclusiveMaximum'] ) ) {
                if ( $value > $args['maximum'] || $value < $args['minimum'] ) {
                    return new WP_Error( 'rest_invalid_param', sprintf( __( '%s must be between %d (inclusive) and %d (inclusive)' ), $param, $args['minimum'], $args['maximum'] ) );
                }
            }
        }
    }

    return true;
}
endif;


if(!function_exists('rest_sanitize_request_arg')):
/**
 * Sanitize a request argument based on details registered to the route.
 *
 * @param  mixed            $value
 * @param  WP_REST_Request  $request
 * @param  string           $param
 * @return mixed
 */
function rest_sanitize_request_arg( $value, $request, $param ) {

    $attributes = $request->get_attributes();
    if ( ! isset( $attributes['args'][ $param ] ) || ! is_array( $attributes['args'][ $param ] ) ) {
        return $value;
    }
    $args = $attributes['args'][ $param ];

    if ( 'integer' === $args['type'] ) {
        return (int) $value;
    }

    if ( isset( $args['format'] ) ) {
        switch ( $args['format'] ) {
            case 'date-time' :
                return sanitize_text_field( $value );

            case 'email' :
                /*
                 * sanitize_email() validates, which would be unexpected
                 */
                return sanitize_text_field( $value );

            case 'uri' :
                return esc_url_raw( $value );
        }
    }

    return $value;
}
endif;


if(!function_exists('rest_get_avatar_sizes')):
/**
 * Retrieves the pixel sizes for avatars.
 *
 * @since 4.4.0
 *
 * @return array List of pixel sizes for avatars. Default `[ 24, 48, 96 ]`.
 */
function rest_get_avatar_sizes() {
    /**
     * Filter the REST avatar sizes.
     *
     * Use this filter to adjust the array of sizes returned by the
     * `rest_get_avatar_sizes` function.
     *
     * @since 4.4.0
     *
     * @param array $sizes An array of int values that are the pixel sizes for avatars.
     *                     Default `[ 32, 64, 96 ]`.
     */
    return apply_filters( 'rest_avatar_sizes', array( 32, 64, 96 ) );
}
endif;


if(!function_exists('rest_get_avatar_urls')):
/**
 * Retrieves the avatar urls in various sizes based on a given email address.
 *
 * @since 4.4.0
 *
 * @see get_avatar_url()
 *
 * @param string $email Email address.
 * @return array $urls Gravatar url for each size.
 */
function rest_get_avatar_urls( $email ) {
    $avatar_sizes = rest_get_avatar_sizes();

    $urls = array();
    foreach ( $avatar_sizes as $size ) {
        //$urls[ $size ] = get_avatar_url( $email, array( 'size' => $size ) );
        $urls[ $size ] = tt_get_avatar($email, $size); // TODO
    }

    return $urls;
}
endif;
