<?php
namespace Theme\Shortcode;

class LocationDesigners extends AbstractShortcode
{
	protected $tag = 'location-designers';

	public function run( $atts=array(), $content='', $tag=null )
	{
        $atts = shortcode_atts([
            'class'                 => '',
            'id'                    => null
        ], $atts, $tag );

        $id = $atts['id'];

        if( !$id ){
            $id = get_the_ID();
        }

        $shadow = \Theme\Service\ShadowTaxonomy::getByTaxonomy( \Theme\Taxonomy\Location::NAME );
        $location = $shadow->getTerm( $id );

        $query = new \WP_Query([
            'post_type' => \Theme\PostType\Designer::NAME,
            'posts_per_page' => -1,
            'tax_query' => [
                [
                    'taxonomy' => \Theme\Taxonomy\Location::NAME,
                    'terms' => [$location->term_id]
                ]
            ]
        ]);

		if( $query->have_posts() ) $this->template([
            'content'       => $content,
            'query'         => $query,
			'class'         => $atts['class']
		]);

	}

}
