<?php
/**
 * Delivery-date calculation functionality.
 *
 * @package WooDeliveryDateEstimator
 */

defined( 'ABSPATH' ) || exit;

/**
 * Calculate business and delivery dates.
 */
final class WDDE_Date_Calculator {

	/**
	 * Add business days while skipping Saturdays and Sundays.
	 *
	 * @param DateTimeImmutable $date Starting date.
	 * @param int               $days Number of business days.
	 *
	 * @return DateTimeImmutable
	 */
	public static function add_business_days( $date, $days ) {
		$days       = absint( $days );
		$added_days = 0;

		while ( $added_days < $days ) {
			$date = $date->modify( '+1 day' );

			// ISO-8601: Monday is 1 and Sunday is 7.
			$day_number = (int) $date->format( 'N' );

			if ( $day_number < 6 ) {
				$added_days++;
			}
		}

		return $date;
	}
}
