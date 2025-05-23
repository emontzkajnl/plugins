<?php

namespace ACA\EC\Search;

use AC\Helper\Select\Options;
use ACA\GravityForms\Search\Query\Bindings;
use ACP;
use ACP\Search;
use ACP\Search\Operators;
use ACP\Search\Value;

class RelatedEvents extends Search\Comparison implements Search\Comparison\Values
{

    /**
     * @var string
     */
    private $meta_key;

    public function __construct($meta_key)
    {
        parent::__construct(new Operators([Operators::EQ]));

        $this->meta_key = $meta_key;
    }

    public function format_label(string $value): string
    {
        return $value;
    }

    public function get_values(): Options
    {
        return Options::create_from_array([
            'yes' => __('Has Event', 'codepress-admin-columns'),
            'no'  => __('Has No Event', 'codepress-admin-columns'),
        ]);
    }

    protected function create_query_bindings(string $operator, Value $value): ACP\Query\Bindings
    {
        global $wpdb;

        $bindings = new Bindings();
        $post_ids = $this->get_related_post_ids();

        if (empty($post_ids)) {
            return $bindings->where('1=0');
        }

        $in_operator = $value->get_value() === 'yes' ? 'IN' : 'NOT IN';

        return $bindings->where("{$wpdb->posts}.ID $in_operator( " . implode(',', $post_ids) . " )");
    }

    public function get_related_post_ids(): array
    {
        global $wpdb;

        $related_events = tribe_get_events([
            'posts_per_page' => -1,
        ]);

        $related_event_ids = implode(',', array_map('absint', wp_list_pluck($related_events, 'ID')));

        if (empty($related_event_ids)) {
            return [];
        }

        $sql = $wpdb->prepare(
            "SELECT DISTINCT( meta_value )
								FROM {$wpdb->postmeta}
								WHERE meta_key = %s AND post_id IN ( " . $related_event_ids . ' )',
            $this->meta_key
        );

        return $wpdb->get_col($sql);
    }

}