<?php 

/* menus
 * --------------------------------
 */
$menu_json = '{
    "class" : "row",
    "struct" : [
        {
            "title" : "Σχετικά με το site",
            "entity" : "page",
            "list" : [2, 1, 3],
            "template" : "list",
            "width" : "col-md-4"
        },
        {
            "title" : "",
            "template" : "null",
            "width" : "col-md-3"
        },
        {
            "title" : "Eπικοινωνία",
            "entity" : "page",
            "content" : "Διεύθυνση: Κάποια Οδός 123  \nΤηλ. 6976.543.210",
            "template" : "static",
            "width" : "col-md-5"
        }
    ]
}';




$menu = json_decode($menu_json);

echo "<pre>";

    print_r($menu);

echo "</pre>";


