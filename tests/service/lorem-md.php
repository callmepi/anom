<?php

use Registry;

define("LIMIT", 50);   // number of random records to make

function lorem_md() {
    // create curl resource
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, "https://jaspervdj.be/lorem-markdownum/markdown.txt");

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);


    $title = substr($output, 2, strpos( $output, "\n" )-1);
    $body = trim(substr($output, strpos( $output, "\n" )+1));
    $intro = str_replace("\n", " ", (substr($body, 3, strpos( $body, "\n" )-2) . $title));



    // close curl resource to free up system resources
    curl_close($ch);

    return [
        'title' => $title,
        'intro' => $intro,
        'body' => $body
    ];

}

$rand_content = lorem_md();


for ($i=0; $i < LIMIT ; $i++) {

    $rand_content = lorem_md();

    $id = Registry::use('database')->query(
        "INSERT INTO lesson (title, course_id, intro, `body`, `status`)
        VALUES (:title, :courseid, :intro, :body, :status)",
        [
            ':title' => $rand_content['title'],
            ':courseid' => rand(3,11),
            ':intro' => $rand_content['intro'],
            ':body' => $rand_content['body'],
            'status' => rand(0,1)
        ]
    )->lastInsertID();

    Registry::use('database')->runQuery(
        "INSERT INTO lesson_privilege (lesson_id, privilege_id)
        VALUES (:lesson_id, :privilege_id)",
        [
            ':lesson_id' => $id,
            ':privilege_id' => rand(1,2)
        ]
    );

}


?>

done!