<?php

namespace Theme\Service;

class ShadowTaxonomy
{
	protected $post_type;
	protected $taxonomy;
	protected $isTrigger = false;
	protected $taxonomyArguments = [];

	public static $post_types = [];
	public static $taxonomies = [];

	public static function register( $post_type, $taxonomy=false, $register_taxonomy=false )
	{
		self::$post_types[$post_type] =
			self::$taxonomies[$taxonomy?:$post_type] =
				new self( $post_type, $taxonomy, $register_taxonomy );
	}

	public static function getByPostType( $post_type )
	{
		return self::$post_types[$post_type];
	}

	public static function getByTaxonomy( $taxonomy )
	{
		return self::$taxonomies[$taxonomy];
	}

	public function __construct( $post_type, $taxonomy=false, $register_taxonomy=false )
	{
		$this->post_type = $post_type;
		$this->taxonomy = $taxonomy ?: $post_type;

		if( $register_taxonomy ){
			$this->taxonomyArguments = $register_taxonomy;
			add_action( 'init', [$this, 'registerTaxonomy'], 10 );
		}

		// sync on post events
		add_action( 'save_post', [$this, 'onPostSave'] );
		add_action( 'before_delete_post', [$this, 'onPostDelete'] );

		// sync on taxonomy events
		add_action( 'created_'.$this->taxonomy, [$this, 'onTermCreated'], 10, 2 );
		add_action( 'edited_'.$this->taxonomy, [$this, 'onTermEdited'], 10, 2 );
		add_action( 'deleted_'.$this->taxonomy, [$this, 'onTermDeleted'], 10, 4 );
	}

	public function registerTaxonomy( )
	{
		register_taxonomy( $this->taxonomy, [], $this->taxonomyArguments );
	}

	/**
	 * Create or update taxonomy term
	 *
	 * @param  int $post_id ID of post being created / updated
	 * @return void
	 */
	public function onPostSave( $post_id )
	{

		// check for the post type
		if( $this->post_type !== get_post_type( $post_id ) ){
			return;
		}

		$status = get_post_status( $post_id );

		if( 'trash' === $status ) {
			$this->deleteTerm( $post_id );
		}
		else {
			$this->updateTerm( $post_id );
		}
	}

	/**
	 * Delete term when post is deleted
	 *
	 * @param  int $post_id ID of post being deleted
	 * @return void
	 */
	public function onPostDelete( $post_id )
	{
		// check for the post type
		if( $this->post_type !== get_post_type( $post_id ) ){
			return;
		}
		$this->deleteTerm( $post_id );
	}

	/**
	 * When a term is created, also add a post
	 *
	 * @param  int $term_id Term ID
	 * @param  int $tt_id   Term Taxonomy ID
	 * @return void
	 */
	public function onTermCreated( $term_id, $tt_id )
	{
		$this->updatePost( $term_id  );
	}

	/**
	 * When a term is edited, also edit or create a post
	 *
	 * @param  int $term_id Term ID
	 * @param  int $tt_id   Term Taxonomy ID
	 * @return void
	 */
	public function onTermEdited( $term_id, $tt_id )
	{
		$this->updatePost( $term_id  );
	}

	/**
	 * When a term is deleted, delete the post
	 *
	 * @param  int $term_id Term ID
	 * @param  int $tt_id   Term Taxonomy ID
	 * @return void
	 */
	public function onTermDeleted( $term_id, $tt_id, $deleted_term, $object_ids )
	{
		$this->deletePost( $term_id  );
	}

	/**
	 * Get the post meta key for this shadow taxonomy
	 *
	 * @return string post meta key
	 */
	protected function getPostMetaKey()
	{
		return '__'.$this->taxonomy;
	}

	/**
	 * Get the associated term_id from a post_id
	 *
	 * @param  int $post_id The post ID
	 * @return mixed The associated term_id or null
	 */
	public function getTermId( $post_id )
	{
		return get_post_meta( $post_id, $this->getPostMetaKey(), true );
	}

	/**
	 * Get the associated term from a post_id
	 *
	 * @param  int $post_id The post ID
	 * @return mixed The associated term or null
	 */
	public function getTerm( $post_id )
	{
		$term_id = $this->getTermId( $post_id );
		$term = null;

		if( $term_id ){
			$term = get_term( $term_id, $this->taxonomy );
		}
		else {
			// we should see if a term exists with this slug
			$term = get_term_by(
				'slug',
				get_post( $post_id )->post_name,
				$this->taxonomy
			);

			if( $term && is_wp_error( $term ) ){
				$term = null;
			}
		}
		return $term;
	}

	/**
	 * Get the associated post from a term_id
	 *
	 * @param  int $term_id The term ID
	 * @return mixed The associated post or null
	 */
	public function getPost( $term_id )
	{
		$posts = get_posts([
			'post_type' => $this->post_type,
			'meta_key' => $this->getPostMetaKey(),
			'meta_value' => $term_id,
			'posts_per_page' => -1,
			'post_status' => 'any'
		]);

		return count( $posts ) ? $posts[0] : null;
	}

	/**
	 * Update a term from a post
	 *
	 * @param  int $post_id The post ID
	 * @return void
	 */
	protected function updateTerm( $post_id )
	{
		if( $this->isTrigger ){
			return;
		}
		$this->isTrigger = true;
		$post = get_post( $post_id );
		$term = $this->getTerm( $post_id );

		if( $term && !is_wp_error( $term ) ){
			// update slug and name
			$update = [];
			if( $post->post_name !== $term->slug ){
				$update['slug'] = $post->post_name;
			}
			if( $post->post_title !== $term->name ){
				$update['name'] = $post->post_title;
			}
			if( $post->post_content !== $term->description ){
				$update['description'] = $post->post_content;
			}
			wp_update_term( $term->term_id, $term->taxonomy, $update );
			update_post_meta( $post_id, $this->getPostMetaKey(), $term->term_id );
		}

		else if( $post->post_status === 'publish' ){
			$term = wp_insert_term( $post->post_title, $this->taxonomy, [
				'slug' => $post->post_name,
				'description' => $post->post_content
			]);
			if( is_wp_error( $term ) ){
				error_log( print_r( $term, 1 ) );
			}
			else{
				update_post_meta( $post_id, $this->getPostMetaKey(), $term['term_id'] );
			}
		}
		$this->isTrigger = false;
	}

	/**
	 * Delete from a post_id
	 *
	 * @param  int $post_id The post ID
	 * @return void
	 */
	public function deleteTerm( $post_id )
	{
		if( $this->isTrigger ){
			return;
		}
		$this->isTrigger = true;
		$term = $this->getTerm( $post_id );
		wp_delete_term( $term->term_id, $term->taxonomy );
		$this->isTrigger = false;
	}

	/**
	 * Update a post from a term
	 *
	 * @param  int $term_id The term_id
	 * @return void
	 */
	public function updatePost( $term_id )
	{
		if( $this->isTrigger ){
			return;
		}
		$this->isTrigger = true;
		$post = $this->getPost( $term_id );
		if( !$post ){

			// create the post
			$args = [
				'post_type' => $this->post_type,
				'post_status' => 'publish',
				'post_title' => $term->name,
				'post_name' => $term->slug,
				'post_content' => $term->description
			];
			$post_id = wp_insert_post( $args );
			update_post_meta( $post_id, $this->getPostMetaKey(), $term_id );
		}
		else {
			$args = [
				'ID' => $post->ID,
				'post_title' => $term->name,
				'post_name' => $term->slug,
				'post_content' => $term->description
			];
			//die( print_r( $args,1  ) );
			$response = wp_update_post( $args );
			//die( print_r( [$response, $args], 1 ) );
		}
		$this->isTrigger = false;
	}

	/**
	 * Delete a post from a term
	 *
	 * @param  int $term_id The term_id
	 * @return void
	 */
	public function deletePost( $term_id )
	{
		if( $this->isTrigger ){
			return;
		}
		$this->isTrigger = true;
		$post = $this->getPost( $term_id );
		if( $post ){
			wp_delete_post( $post->ID );
		}
		$this->isTrigger = false;
	}

}
