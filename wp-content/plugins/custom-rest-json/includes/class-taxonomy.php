<?php 
class GetTaxomonyClass {
    private static $taxonomyArray = ["product_cat", "brand"];

    public static function getTaxonomyArray() {
        return self::$taxonomyArray;
    }
}