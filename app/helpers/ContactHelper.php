<?php

if (!function_exists('contact_phone_link')) {
    function contact_phone_link()
    {
        $phone = str_replace('+', '', config('contact.phone'));
        return $phone ? "tel:+{$phone}" : '#';
    }
}

if (!function_exists('contact_email_link')) {
    function contact_email_link()
    {
        $email = config('contact.email');
        return $email ? "mailto:{$email}" : '#';
    }
}

if (!function_exists('contact_whatsapp_link')) {
    function contact_whatsapp_link()
    {
        $number = str_replace('+', '', config('contact.whatsapp.number'));
        $message = urlencode(config('contact.whatsapp.message'));

        return $number ? "https://wa.me/{$number}?text={$message}" : '#';
    }
}
