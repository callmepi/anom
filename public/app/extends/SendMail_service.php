<?php
namespace app\extends;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class SendMail_service
{
    /** send_activation_code
     * 
     * construct the envelope = [
     *    email => user email
     *    name => user full name
     *    subject => subject
     *    body => email message body
     * ]
     * 
     * then send the email;
     * 
     * @param $activator (array): [
     *    email => user email,
     *    name => user full name,
     *    code => activation code
     * ]
     * 
     * @return success_starus (boolean)
     * 
     * TODO:
     * check for email templating:
     * + https://stackoverflow.com/questions/2391171/how-to-get-output-from-local-script-in-php
     * + https://stackoverflow.com/questions/171318/how-do-i-capture-php-output-into-a-variable
     */
    public static function send_invitation($memo)
    {
        $invitation = SITE_URL ."/invitation/". $memo['code'];
        $envelope = [
            'email' => $memo['email'],
            'name' => $memo['name'],
            'subject' => 'Πρόσκληση από το 1ο Γυμνάσιο Ραφήνας',
            'body' => "Χαίρετε,<br>
                Στο παρόν email θα βρείτε πληροφορίες για να
                ενεργοποιήσετε την πρόσβασή σας στην Πλατφόρμα
                ηλεκτρονικών αιτήσεων και δημιουργίας διαδικαστικών
                εγγράφων του 1ου Γυμνάσιου Ραφήνας.<br>
                <br>
                Όνομα επαφής: <b>". $memo['name'] ."</b><br>
                Email : <b>". $memo['email'] ."</b><br>
                <br>
                Για να ενεργοποιήσετε την πρόσβασή σας στο site
                θα πρέπει να ακολουθήσετε τον παρακάτω σύνδεσμο:
                <a href=\"". $invitation ."\">". $invitation ."</a>.
                <br>
                <br>
                Μετά την ενεργοποίηση θα έχετε πρόσβαση
                στο περιεχόμενο του site.<br>
                Μην απαντήσετε στο email,
                δεν υπάρχει φυσική επαφή η οποία να λαμβάνει τυχόν replies."
        ];

        switch (DEFAULT_MAIL_SERVICE) {
            case 'mailjet':
                $reply = self::send_mailjet($envelope);
                break;
            
            case 'phpMailer':
                $reply = self::send_phpMailer($envelope);
                break;
        }

        return $reply;

    }


    /** send phpMailer
     * 
     * send email using phpMailer class;
     * optional SMTP server configuration;
     * 
     * @param array $envelope: array with [ email, name, subject, body ] keys
     * @return boolean success_starus
     */
    public static function send_phpMailer($envelope)
    {
        $mail = new PHPMailer();

        # // setup smpt
        $mail->SMTPDebug = 3;
        $mail->IsSMTP();
        $mail->Host = MAIL_HOST;
        # $mail->SMPTAuth = false;
        $mail->SMTPSecure = MAIL_ENCRYPTION;
        # // $mail->Protocol = 'mail';
        
        $mail->Mailer = MAIL_MAILER;
        $mail->Port = MAIL_PORT;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;

        // setup format
        $mail->CharSet = 'utf-8';
        $mail->IsHTML(true);

        // setup THE mail
        // --- -- -- - - -
        // It's important not to use the submitter's address as the from address
        // as it's forgery, which will cause your messages to fail SPF checks.
        // Use an address in your own domain as the from address;
        //  put the submitter's address in a reply-to

        $mail->setFrom(NO_REPLY_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($envelope['email'], $envelope['name']);
        $mail->addReplyTo(REPLY_TO_EMAIL, MAIL_FROM_NAME);
        $mail->Subject = $envelope['subject'];
        $mail->Body = $envelope['body'];

        return (!$mail->send()) ? false : true;
    }


    /** send_mailjet
     * 
     * send email using CURL mail-relay of Mailjet
     * 
     * @param $envelope
     * @return success_starus (boolean)
     */
    public static function send_mailjet($envelope)
    {
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
            MAILJET_APIKEY. ':'. MAILJET_SECRET
        );
        $server_output = curl_exec($ch);
        curl_close ($ch);
        
        $response = json_decode($server_output);

        return ($response->Messages[0]->Status == 'success')
            ? true
            : $response;

    }



}





/** NOTE:
 * -----------------------------------------------------------------------------
 * (brainstorming)
 * 

Optimization per concept alternative technologies
--- -- -- - - -

## Session
File Session
Database Session
Redis Session
Memcached Session

---

## Cache
File Cache
Database Cache
Redis Cache
Memcashed Cache

---

## Log
File Log
Database Log
Log event to Slack
Log event to Email

 * -----------------------------------------------------------------------------
 */
