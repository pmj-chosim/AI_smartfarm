<?php
class limit{

    function limit_kata($string, $word_limit = null)
    {     
        $words = explode(' ', $string);
        if (count($words) > $word_limit):
            $kata = implode(' ', array_slice($words, 0, $word_limit)) ;
            // return clean_text($kata)."...";
            return trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($kata))))))."...";
        else:
            $kata = implode(' ', array_slice($words, 0, $word_limit)) ;
            return trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($kata))))))."...";
        endif;
    }
    // function clean_text($text)
    // {
    //     return trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($text))))));
    // }
}
?>