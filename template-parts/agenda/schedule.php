<?php
/**
 * Agenda session schedule rows (sorted timeline).
 *
 * @package msrseminars
 *
 * @var array<int, array<string, mixed>>|null $msr_agenda_schedule_rows Optional pre-sorted rows.
 */

$post_id = get_the_ID();
$rows    = get_query_var( 'msr_agenda_schedule_rows', null );

if ( ! is_array( $rows ) ) {
	$rows = msrseminars_get_sorted_schedule_rows( $post_id );
}

if ( ! $rows ) {
	return;
}

echo '<div class="seminars-agenda-timeline" role="list">';

foreach ( array_values( $rows ) as $index => $row ) {
	if ( ! is_array( $row ) ) {
		continue;
	}

	get_template_part(
		'template-parts/agenda/session',
		'card',
		array(
			'row'        => $row,
			'post_id'    => $post_id,
			'index'      => $index,
			'context_id' => 'single',
		)
	);
}

echo '</div>';
