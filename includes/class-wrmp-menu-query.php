<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WRMP_Menu_Query {
	/**
	 * Selected categories for top navigation, as a two-level tree.
	 *
	 * A selected term is "main" (right-side top nav) unless its own real
	 * WooCommerce parent is also selected, in which case it nests under
	 * that parent's `children` instead. Flat, parent-less selections (the
	 * common case) behave exactly as before: every selected term is main.
	 *
	 * @return array
	 */
	public function get_selected_categories() {
		$selected_ids = array_filter( array_map( 'absint', (array) WRMP_Helpers::get_setting( 'category_ids', array() ) ) );

		if ( empty( $selected_ids ) ) {
			return array();
		}

		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'include'    => $selected_ids,
				'orderby'    => 'include',
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return array();
		}

		$selected_lookup = array();
		foreach ( $terms as $term ) {
			$selected_lookup[ $term->term_id ] = true;
		}

		$categories = array();

		foreach ( $terms as $term ) {
			if ( isset( $selected_lookup[ $term->parent ] ) ) {
				continue; // Nested under its main entry below instead.
			}

			$category = $this->normalize_category( $term );

			$children = array();
			foreach ( $terms as $maybe_child ) {
				if ( (int) $maybe_child->parent === (int) $term->term_id ) {
					$children[] = $this->normalize_category( $maybe_child );
				}
			}
			$category['children'] = $children;

			$categories[] = $category;
		}

		return $categories;
	}

	/**
	 * Flat list of every selected term ID (main + nested children).
	 *
	 * @return array
	 */
	public function get_selected_term_ids() {
		return array_filter( array_map( 'absint', (array) WRMP_Helpers::get_setting( 'category_ids', array() ) ) );
	}

	/**
	 * Expand category list with descendants.
	 *
	 * @param array $category_ids Category IDs.
	 * @return array
	 */
	protected function expand_category_ids( $category_ids ) {
		$all_ids = $category_ids;

		foreach ( $category_ids as $category_id ) {
			$children = get_term_children( $category_id, 'product_cat' );

			if ( ! is_wp_error( $children ) ) {
				$all_ids = array_merge( $all_ids, $children );
			}
		}

		return array_values( array_unique( array_map( 'absint', $all_ids ) ) );
	}

	/**
	 * Products for one category.
	 *
	 * @param int $term_id Term.
	 * @return array
	 */
	protected function get_products_for_category( $term_id ) {
		$term_id = absint( $term_id );

		$query = new WP_Query(
			array(
				'post_type'              => 'product',
				'post_status'            => 'publish',
				'posts_per_page'         => -1,
				'orderby'                => 'menu_order title',
				'order'                  => 'ASC',
				'ignore_sticky_posts'    => true,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'tax_query'              => array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => array( $term_id ),
						'include_children' => true,
					),
				),
			)
		);

		$products = array();

		foreach ( $query->posts as $post ) {
			$product = wc_get_product( $post );

			if ( ! $product ) {
				continue;
			}

			$products[] = array(
				'id'                => $product->get_id(),
				'name'              => $product->get_name(),
				'image'             => get_the_post_thumbnail_url( $product->get_id(), 'large' ),
				'price_html'        => $product->get_price_html(),
				'short_description' => $product->get_short_description() ? $product->get_short_description() : wp_trim_words( $product->get_description(), 24 ),
			);
		}

		wp_reset_postdata();

		return $products;
	}

	/**
	 * Products for public rendering.
	 *
	 * @param int $term_id Term ID.
	 * @return array
	 */
	public function get_category_products( $term_id ) {
		return $this->get_products_for_category( $term_id );
	}

	/**
	 * Normalize category data.
	 *
	 * @param WP_Term $term Term.
	 * @return array
	 */
	protected function normalize_category( $term ) {
		return array(
			'term_id'       => (int) $term->term_id,
			'name'          => $term->name,
			'description'   => $term->description,
			'thumbnail_url' => WRMP_Helpers::get_category_thumbnail_url( $term->term_id ),
		);
	}
}
