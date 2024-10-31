<?php
/**
 * Helper Link Functions - for building link tags.
 *
 * @package Next Page Caching
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly.
}

if ( ! function_exists( 'npc_add_prefetch' ) ) {
	function npc_add_prefetch( $url ) {
		NPC_Browser_Hints::add_link( npc_form_prefetcher( $url ) );
	}
}

if ( ! function_exists( 'npc_add_preload' ) ) {
	function npc_add_preload( $url ) {
		NPC_Browser_Hints::add_link( npc_form_preloader( $url ) );
	}
}

if ( ! function_exists( 'npc_add_preconnect' ) ) {
	function npc_add_preconnect( $url ) {
		NPC_Browser_Hints::add_link( npc_form_preconnect( $url ) );
	}
}

if ( ! function_exists( 'npc_form_prefetcher' ) ) {
	function npc_form_prefetcher( $url ) {
		return array(
			'rel' => 'prefetch',
			'href' => esc_url( $url ),
			'as' => esc_attr( npc_get_type( $url ) ),
		);
	}
}

if ( ! function_exists( 'npc_form_preloader' ) ) {
	function npc_form_preloader( $url ) {
		$attrs = array(
			'rel' => 'preload',
			'href' => esc_url( $url ),
			'as' => esc_attr( npc_get_type( $url ) ),
		);
		$type = npc_get_type( $url );

		if ( $type === 'style' ) {
			$attrs['onload'] = 'this.rel="stylesheet"';
		} else if ( $type === 'font' ) {
			$type = npc_get_font_type( $url );
			if ( $type ) {
				$attrs['type'] = $type;
			}
		}

		if ( ! npc_is_local_domain( $url ) ) {
			$attrs['crossorigin'] = '';
		}

		return $attrs;
	}
}

if ( ! function_exists( 'npc_form_preconnect' ) ) {
	function npc_form_preconnect( $url ) {
		$attrs = array(
			'rel' => 'preconnect',
			'href' => esc_url( $url ),
		);

		if ( ! npc_is_local_domain( $url ) ) {
			$attrs['crossorigin'] = '';
		}

		return $attrs;
	}
}

if ( ! function_exists( 'npc_form_link_tag' ) ) {
	function npc_form_link_tag( $attrs ) {
		if ( count( $attrs ) ) {
			$link = '<link ';
			$link .= implode( ' ', array_map( 'npc_form_link_tag_attr', array_keys( $attrs ), $attrs ) );
			$link .= '/>';
			return $link;
		}
		return '';
	}
}

if ( ! function_exists( 'npc_form_link_tag_attr' ) ) {
	function npc_form_link_tag_attr( $key, $value ) {
		if ( $value !== '' ) {
			return $key . '="' . esc_attr( $value ) . '"';
		} else {
			return $key;
		}
	}
}

if ( ! function_exists( 'npc_is_local_domain' ) ) {
	function npc_is_local_domain( $url ) {
		return npc_domain_matches( get_site_url(), $url );
	}
}

if ( ! function_exists( 'npc_domain_matches' ) ) {
	function npc_domain_matches( $url1, $url2 ) {
		return parse_url( $url1, PHP_URL_HOST ) === parse_url( $url2, PHP_URL_HOST );
	}
}

if ( ! function_exists( 'npc_get_type' ) ) {
	/**
	 * Returns the type of the URL for use in the `as` attribute of a
	 * <link> tag.
	 *
	 * @param $url string The URL
	 *
	 * @return string The type
	 *
	 * @see https://www.w3.org/TR/preload/#x3-2-as-attribute
	 */
	function npc_get_type( $url ) {
		$parsed = parse_url( $url );
		if ( preg_match( '/.js$/', $parsed['path'] ) ) {
			return 'script';
		} else if ( preg_match( '/.css$/', $parsed['path'] ) ) {
			return 'style';
		} else if ( preg_match( '/.(jpe?g|gif|png|svg)$/', $parsed['path'] ) ) {
			return 'image';
		} else if ( preg_match( '/.(eot|woff2?|ttf)$/', $parsed['path'] ) ) {
			// @see https://transfonter.org/formats
			return 'font';
		}
		return 'html';
	}
}

if ( ! function_exists( 'npc_get_font_type' ) ) {
	/**
	 * Gets the "type" of the font based on its extension.
	 *
	 * @param $url string The URL
	 *
	 * @return string The font type for use in the `type` <link> attribute.
	 */
	function npc_get_font_type( $url ) {
		$parsed = parse_url( $url );
		if ( preg_match( '/.(\w+)$/', $parsed['path'], $matches ) ) {
			return 'font/' . $matches[1];
		}
		return '';
	}
}
