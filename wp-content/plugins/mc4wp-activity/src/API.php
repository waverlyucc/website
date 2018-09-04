<?php

namespace MC4WP\Activity;

use MC4WP_API;

/**
 * Class API
 *
 * @package MC4WP\Activity
 * @deprecated 1.1
 */
class API extends MC4WP_API {

	/**
	 * @param string $list_id
	 *
	 * @return array
	 */
	public function get_lists_activity( $list_id ) {

		$args = array(
			'id' => $list_id
		);

		$result = $this->call( 'lists/activity', $args );

		// return empty array if something's up
		if( ! is_array( $result ) ) {
			return array();
		}

		return $result;
	}

}