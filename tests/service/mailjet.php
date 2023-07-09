<?php

$envelope = [
    'email' => 'piipiis@gmail.com',
    'name' => 'Test User',
    'subject' => 'Some Subject',
    'body' => "Ευχαριστούμε,<br>
        για την εγγραφή σας στο <b>Classroom</b>.<br>
        <br>
        <i>Καλή συνέχεια!</i>."
];


$body = [
    'Messages' => [
        [
        'From' => [
            'Email' => REPLY_TO_EMAIL,
            'Name' => MAIL_FROM_NAME
        ],
        'To' => [
            [
                'Email' => $envelope['email'],
                'Name' => $envelope['name']
            ]
        ],
        'Subject' => $envelope['subject'],
        'HTMLPart' => $envelope['body']
        ]
    ]
];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.mailjet.com/v3.1/send");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json')
);
curl_setopt(
    $ch,
    CURLOPT_USERPWD,
    MAIL_PASSWORD
);
$server_output = curl_exec($ch);
curl_close ($ch);

$response = json_decode($server_output);

echo '<pre>';
    print_r($response);
    echo "\n\nstatus: ". $response->Messages[0]->Status;
    if ($response->Messages[0]->Status == 'success') echo "\n\nSuccess = true";
echo '</pre>';