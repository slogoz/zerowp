<?php

if ( ! function_exists( 'date_create_from_format' ) ) {

    /**
     * Reimplementation of DateTime::createFromFormat for PHP < 5.3. :(
     * Borrowed from http://stackoverflow.com/questions/5399075/php-datetimecreatefromformat-in-5-2
     *
     * @param string $date_format Date format.
     * @param string $date_value  Date value.
     *
     * @return DateTime
     */
    function date_create_from_format( $date_format, $date_value ) {

        $schedule_format = str_replace(
            array( 'M', 'Y', 'm', 'd', 'H', 'i', 'a' ),
            array( '%b', '%Y', '%m', '%d', '%H', '%M', '%p' ),
            $date_format
        );

        /*
         * %Y, %m and %d correspond to date()'s Y m and d.
         * %I corresponds to H, %M to i and %p to a
         */
        $parsed_time = strptime( $date_value, $schedule_format );

        $ymd = sprintf(
        /**
         * This is a format string that takes six total decimal
         * arguments, then left-pads them with zeros to either
         * 4 or 2 characters, as needed
         */
            '%04d-%02d-%02d %02d:%02d:%02d',
            $parsed_time['tm_year'] + 1900,  // This will be "111", so we need to add 1900.
            $parsed_time['tm_mon'] + 1,      // This will be the month minus one, so we add one.
            $parsed_time['tm_mday'],
            $parsed_time['tm_hour'],
            $parsed_time['tm_min'],
            $parsed_time['tm_sec']
        );

        return new DateTime( $ymd );
    }
}// End if.

