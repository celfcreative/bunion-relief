<?php

if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($phoneNumber)
    {
        // Remove the leading '+1' country code if present
        $phoneNumber = preg_replace('/^\+1/', '', $phoneNumber);

        // Ensure the phone number is 10 digits long
        if (strlen($phoneNumber) === 10) {
            $areaCode = substr($phoneNumber, 0, 3);
            $firstPart = substr($phoneNumber, 3, 3);
            $secondPart = substr($phoneNumber, 6, 4);

            return "($areaCode) $firstPart-$secondPart";
        }

        // Return the original phone number if it doesn't match the expected format
        return $phoneNumber;
    }
}
