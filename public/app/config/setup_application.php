<?php
/** SETUP APPLICATION
 * -----------------------------------------------------------------------------
 *
 * Define application specific constants
 * 
 * ----------------------------------------------------------------------------- 
 */

define('DEFAULT_SCHOOL', '1ο Γυμνάσιο Ραφήνας');


// email setup /////////////////////////////////////////////////////////////////
// -----------------------------------------------------------------------------

    // SMTP
define('MAIL_MAILER', $env['MAIL_MAILER']);
define('MAIL_HOST', $env['MAIL_HOST']);
define('MAIL_PORT', $env['MAIL_PORT']);
define('MAIL_USERNAME', $env['MAIL_USERNAME']);
define('MAIL_PASSWORD', $env['MAIL_PASSWORD']);
define('MAIL_ENCRYPTION', $env['MAIL_ENCRYPTION']);
    // Mailjet
define('MAILJET_APIKEY', $env['MAILJET_APIKEY']);   // apikey
define('MAILJET_SECRET', $env['MAILJET_SECRET']);   // secret
define('MAILJET_TICKET', $env['MAILJET_TICKET']);   // apikey:secret
    // sender info
define('MAIL_FROM_NAME', $env['MAIL_FROM_NAME']);
define('NO_REPLY_EMAIL', $env['NO_REPLY_EMAIL']);
define('REPLY_TO_EMAIL', $env['REPLY_TO_EMAIL']);

// default mail service; options so far: [ mailjet | phpmailer ]
define('DEFAULT_MAIL_SERVICE', 'mailjet');


// DEFAULT VALUES //////////////////////////////////////////////////////////////
// -----------------------------------------------------------------------------

// default privileges (for the subscribed user)
// (array of privilege aliases)
// --- -- -- - - -
define('DEFAULT_PRIVILEGES', [
    1,      // economics, level 1
    5       // programming. level 1
]);


// User Roles
// --- -- -- - - -
define('Admin', 1);
define('Secretary', 2);
define('Teacher', 3);


// ACCESS HISTORY //////////////////////////////////////////////////////////////
// -----------------------------------------------------------------------------
// (user-)access types

define('TRACK_LOGIN', 1);       // login / logout
define('TRACK_ACCOUNT', 2);     // create user / activate account / modidy profile
define('TRACK_REQUESTS', 4);    // order / payment / refund etc...

// access type strings
// ---
define('USER_ACCESS_TYPE', [
    1 => 'Login',
    2 => 'Account',
    3 => 'Request'
]);






// ASSETS: PATHS USED HEAVILY //////////////////////////////////////////////////
// -----------------------------------------------------------------------------
// these paths are used in order to easily find and autoload useful elemets

# define('JS_DIR', '/assets/js/');      // JavaScript base library path
# define('CSS_DIR', '/assets/css/');    // CSS base path



// MESSAGES ////////////////////////////////////////////////////////////////////
// -----------------------------------------------------------------------------
// Error Mesagges, Warnings and other Common Mesages


// 404
// ---
define('PAGE_404_TITLE', '404: Not Found');


// Alert and connfirmation messages
// ---
define('EMPTY_REQUIRED_FIELDS', 'Δεν έχουν συμπληρωθεί όλα τα υποχρεωτικά πεδία');
define('TICKET_EXPIRED', 'Η φόρμα που συμπληρώσατε φαίνεται να έχει λήξη. Ανανεώστε τη και προσπαθήστε πάλι.');
define('CONFIRM_EXIT', 'ΠΡΟΣΟΧΗ: αν εγκαταλείψετε την σελίδα θα χάσετε ό,τι πληροφορίες έχετε εισάγει στη φόρμα.\n\nΘέλετε να φύγετε από τη σελίδα;');


// registration
// ---
define('REGISTRATION_SUCCESS', "<h3>Επιτυχής Εγγραφή</h3>
  <p>
      Παρακαλώ ελέγξτε το email σας και ακολουθήστε το σύνδεσμο ενεργοποίησης
      που σας 'εχει αποσταλεί, ώστε να έχετε πρόσβαση σε επιπλέον περιεχόμενο του site
  </p>"
);
define ('REGISTRATION_USER_EXISTS', "<h3>Αποτυχία Εγγραφής</h3>
  <p>
      Υπάρχει ήδη χρήστης με αυτό το email. Αν δεν θυμάστε το password
      μπορείτε να κάνετε επαναφορά του ακολουθώντας
      <a href='/reset-password'>αυτό το σύνδεσμο</a>.
  </p>"
);


// activation
// ---
define('ACCOUNT_ACTIVATED_TITLE', 'Ο λογαριασμός σας έχει ενεργοποιηθεί');
define('ACCOUNT_ACTIVATED_MESSAGE', 'Τώρα μπορείτε να
  <a href="/login">συνδεθείτε στο σύστημα</a>.');
define('NOT_VALID_ACTIVATION_TITLE', 'Σφάλμα!');
define('NOT_VALID_ACTIVATION_MESSAGE', 'Ο λογαριασμός δεν έχει ενεργοποιηθεί.
    Ξαναδοκιμάστε αργότερα κι αν το σφάλμα επιμείνει, επικοινωνήστε με την υπεύθυνη του προγράμμαρος.');

// update properties
// ---
define('ACCOUNT_UPDATED_TITLE','Ο λογαριασμός σας ενημερώθηκε!');
define('ACCOUNT_UPDATED_MESSAGE','Τώρα μπορείτε να επιστρέψετε 
<a href="/panel">στον πίνακα ελέγχου</a>.');
define('RETRY_SET_PASSWORD', 'Μπορείτε <a href="/user/password_form">να ξαναδοκιμάσετε</a> ή 
    <br>ή να επιστρέψετε στον <a href="/panel">πίνακα ελέγψου</a>.');



define ('PETITION_TYPE', [
    1 => 'common',
    2 => 'penalty'
]);



// standard arrays
// --- -- -- - - -
define('TEACHER_SECTORS', [
    'ΠΕ01 ΘΕΟΛΟΓΟΙ',
    'ΠΕ02 ΦΙΛΟΛΟΓΟΙ',
    'ΠΕ02.50 ΦΙΛΟΛΟΓΟΙ ΕΙΔΙΚΗΣ ΑΓΩΓΗΣ',
    'ΠΕ03 ΜΑΘΗΜΑΤΙΚΟΙ',
    'ΠΕ03.50 ΜΑΘΗΜΑΤΙΚΟΙ ΕΙΔΙΚΗΣ ΑΓΩΓΗΣ',
    'ΠΕ04.01 ΦΥΣΙΚΟΙ',
    'ΠΕ04.01.50 ΦΥΣΙΚΟΙ ΕΙΔΙΚΗΣ ΑΓΩΓΗΣ',
    'ΠΕ04.02 ΧΗΜΙΚΟΙ',
    'ΠΕ04.04 ΒΙΟΛΟΓΟΙ',
    'ΠΕ05 ΓΑΛΛΙΚΗΣ ΦΙΛΟΛΟΓΙΑΣ',
    'ΠΕ06 ΑΓΓΛΙΚΗΣ ΦΙΛΟΛΟΓΙΑΣ',
    'ΠΕ07 ΓΕΡΜΑΝΙΚΗΣ ΦΙΛΟΛΟΓΙΑΣ',
    'ΠΕ08 ΚΑΛΛΙΤΕΧΝΙΚΩΝ',
    'ΠΕ11 ΦΥΣΙΚΗΣ ΑΓΩΓΗΣ',
    'ΠΕ23-ΣΔΕΥ ΨΥΧΟΛΟΓΟΙ',
    'ΠΕ30-ΣΔΕΥ ΚΟΙΝΩΝΙΚΟΙ ΛΕΙΤΟΥΡΓΟΙ',
    'ΠΕ78 ΚΟΙΝΩΝΙΚΩΝ ΕΠΙΣΤΗΜΩΝ',
    'ΠΕ79.01 ΜΟΥΣΙΚΗΣ ΕΠΙΣΤΗΜΗΣ',
    'ΠΕ80 ΟΙΚΟΝΟΜΙΑΣ',
    'ΠΕ81 ΠΟΛ.ΜΗΧΑΝΙΚΩΝ-ΑΡΧΙΤΕΚΤΟΝΩΝ',
    'ΠΕ82 ΜΗΧΑΝΟΛΟΓΩΝ',
    'ΠΕ86 ΠΛΗΡΟΦΟΡΙΚΗΣ',
    'ΠΕ88.01 ΓΕΩΠΟΝΟΙ'
]);



/** GENDER-ed constant arrays (LGBTQIA+ inclusive)
 * they use pronounces as key instead of a binary gender-flag
 * -----------------------------------------------------------------------------
 */

define('GENDERED_POSITIONS', [      // gender-ed positions
    'he' =>  [
        'Διευθυντής',
        'Υποδιευθυντής',
        'Μόνιμος Καθηγητής',
        'Αναπληρωτής Καθηγητής'
    ],
    'she' => [
        'Διευθύντρια',
        'Υποδιευθύντρια',
        'Μόνιμη Καθηγήτρια',
        'Αναπληρώτρια Καθηγήτρια'
    ],
    'ze' => [
        'Διευθυντ@',
        'Υποδιευθυντ@',
        'Μόνιμ@ Καθηγητ@',
        'Αναπληρωτ@ Καθηγητ@'
    ]
]);

define('GENDERED_ARTICLE',[         // gendered (grammar) articles
    'he' => 'ο',
    'she' => 'η',
    'ze' => '@'
]);



## -----------------------------------------------------------------------------
##
##                            t h e   f o r m s
##
## -----------------------------------------------------------------------------



// registration/activation form (from invitation)
// ---
define('REGISTRATION_FORM', [
    'defaults' => [
        'outer_class' => 'col-12',
        'inner_class' => 'form-control',
        'type' => 'text',
        'source' => '\app\controllers\User::current'
    ],
    'form' => [
        [
            'name' => 'first_name',
            'label' => 'Όνομα',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'last_name',
            'label' => 'Επίθετο',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'father_name',
            'label' => 'Πατρώνυμο',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'registration_number',
            'label' => 'Αριθμός μητρώου',
            'class' => 'col-8',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'sector',
            'label' => 'Κλάδος / Ειδικότητα',
            'type' => 'select',
            'attributes' => ['required', 'auto'],
            'options' => TEACHER_SECTORS
        ],
        [
            'name' => 'belonging_school',
            'label' => 'Σχολείο τοποθέτησης',
            'attributes' => ['required', 'auto']
        ],
        // [    // working school = (obviously) is the current school
        //     'name' => 'working_school',
        //     'label' => 'Σχολείο εργασίας',
        //     'attributes' => ['required']
        // ],
        [
            'name' => 'position',
            'label' => 'Θέση',
            'type' => 'select',
            'attributes' => ['required', 'auto'],
            'options' => []
        ],
        [
            'name' => 'phone',
            'label' => 'Τηλέφωνο',
            'class' => 'col-8',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'password',
            'label' => 'Κωδικός πρόσβασης',
            'type' => 'password',
            'class' => 'col-8',
            'attributes' => ['required']
        ],
        [
            'name' => 'confirm_password',
            'label' => 'Επαλήθευση κωδικού πρόσβασης',
            'type' => 'password',
            'class' => 'col-8',
            'attributes' => ['required']
        ]
    ]
]);


// (edit) user properties
// ---
define('USER_PROPERTIES_FORM', [
    'defaults' => [
        'outer_class' => 'col-12',
        'inner_class' => 'form-control',
        'type' => 'text',
        'source' => '\app\controllers\User::current'
    ],
    'form' => [
        [
            'name' => 'first_name',
            'label' => 'Όνομα',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'last_name',
            'label' => 'Επίθετο',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'father_name',
            'label' => 'Πατρώνυμο',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'registration_number',
            'label' => 'Αριθμός μητρώου',
            'class' => 'col-8',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'sector',
            'label' => 'Κλάδος / Ειδικότητα',
            'type' => 'select',
            'attributes' => ['required', 'auto'],
            'options' => TEACHER_SECTORS
        ],
        [
            'name' => 'belonging_school',
            'label' => 'Σχολείο τοποθέτησης',
            'attributes' => ['required', 'auto']
        ],
        // [    // working school = (obviously) is the current school
        //     'name' => 'working_school',
        //     'label' => 'Σχολείο εργασίας',
        //     'attributes' => ['required']
        // ],
        [
            'name' => 'position',
            'label' => 'Θέση',
            'type' => 'select',
            'attributes' => ['required', 'auto'],
            'options' => []
        ],
        [
            'name' => 'phone',
            'label' => 'Τηλέφωνο',
            'class' => 'col-8',
            'attributes' => ['required', 'auto']
        ]
    ]
]);


// (re-)set password
// ---
define('SET_PASSWORD_FORM', [
    'defaults' => [
        'outer_class' => 'col-12',
        'inner_class' => 'form-control',
        'type' => 'text',
        'source' => '\app\controllers\User::current'
    ],
    'form' => [
        [
            'name' => 'oldpass',
            'label' => 'Τρέχον κωδικός πρόσβασης',
            'type' => 'password',
            'attributes' => ['required']
        ],
        [
            'name' => 'password',
            'label' => 'Νέος Κωδικός πρόσβασης',
            'type' => 'password',
            'attributes' => ['required']
        ],
        [
            'name' => 'confirm_password',
            'label' => 'Επαλήθευση νέου κωδικού πρόσβασης',
            'type' => 'password',
            'attributes' => ['required']
        ]
    ]
]);


// Application (common request) form
// ---
define('APPLICATION_FORM', [
    'defaults' => [
        'outer_class' => 'col-12',
        'inner_class' => 'form-control',
        'type' => 'text',
        'source' => '\app\controllers\User::current'
    ],
    'form' => [
        [
            'name' => 'subject',
            'label' => 'Θέμα (χαρακτηριστικός τίτλος για αναφορά)',
            'attributes' => ['required']
        ],
        [
            'name' => 'first_name',
            'label' => 'Όνομα',
            'class' => 'col-5',
            'attributes' => ['auto']
        ],
        [
            'name' => 'last_name',
            'label' => 'Επίθετο',
            'class' => 'col-7',
            'attributes' => ['auto']
        ],
        [
            'name' => 'father_name',
            'label' => 'Πατρώνυμο',
            'class' => 'col-5',
            'attributes' => ['auto']
        ],
        [
            'label' => '',
            'type' => 'label', 
            'class' => 'col-7',
        ],
        [
            'name' => 'phone',
            'label' => 'Τηλέφωνο',
            'class' => 'col-5',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'email',
            'label' => 'e-mail',
            'type' => 'email',
            'class' => 'col-7',
            'attributes' => ['auto']
        ],
        [
            'name' => 'registration_number',
            'label' => 'Αριθμός μητρώου',
            'class' => 'col-5',
            'attributes' => ['auto']
        ],
        [
            'name' => 'sector',
            'label' => 'Κλάδος / Ειδικότητα',
            'type' => 'select',
            'class' => 'col-7',
            'attributes' => ['auto']
        ],
        [
            'name' => 'belonging_school',
            'label' => 'Σχολείο τοποθέτησης',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'date',
            'label' => 'Ημερομηνία αίτησης',
            'type' => 'date',
            'class' => 'col-5',
            'attributes' => ['required']
        ],
        [
            'name' => 'position',
            'label' => 'Θέση',
            'type' => 'select',
            'class' => 'col-7',
            'attributes' => ['required', 'auto']
        ],
        [
            'name' => 'request',
            'label' => 'Αίτημα (πχ, Παρακαλώ να μου χορηγήσετε ...)',
            'class' => 'col-12 x7-lines',
            'type' => 'textarea',
            'attributes' => ['required']
        ]
    ]
]);


// penalty form
// ---
define('PENALTY_FORM', [
  'defaults' => [
      'outer_class' => 'col-12',
      'inner_class' => 'form-control',
      'type' => 'text',
      'source' => 'user'
  ],
  'form' => [
      [
          'name' => 'subject',
          'label' => 'Θέμα (χαρακτηριστικός τίτλος για αναφορά)',
          'attributes' => ['required']
      ],
      [
          'label' => '',
          'type' => 'label'
      ],
      [
          'label' => 'Πειθαρχική υπόθεση',
          'type' => 'label'
      ],
      [
        'name' => 'of_article',
        'label' => 'του/της/των...',
        'type' => 'select',
        'options' => [
            'του μαθητή',
            'της μαθήτριας',
            'των μαθητών',
            'των μαθητριών'
        ],
        'class' => 'col-md-4',
        'attributes' => ['required']
      ],
      [
          'name' => 'student_names',
          'label' => '(ονοματεπώνυ-μο/μα, πτώση γενική)',
          'type' => 'textarea',
          'class' => 'col-md-8',
          'attributes' => ['required']
      ],
      [
          'name' => 'of_department',
          'label' => 'του τμήματος...',
          'class' => 'col-md-4',
          'attributes' => ['required']
      ],
      [
          'name' => 'date',
          'label' => 'Ημερομηνία',
          'type' => 'date',
          'class' => 'col-md-4',
          'attributes' => ['required']
      ],
      [
          'name' => 'time',
          'label' => 'Ώρα',
          'class' => 'col-md-4',
          'attributes' => ['required']
      ],
      [
          'name' => 'place',
          'label' => 'Τόπος',
          'type' => 'select',
          'options' => [
              'Γραφείο Διευθηντή',
              'Γραφείο Καθηγητών'
          ],
          'attributes' => ['required']
      ],
      [
          'name' => 'rapporteur',
          'label' => 'εισηγητής',
          'type' => 'select',
          'source' => '\app\extends\Cache_service::all_users',
          'attributes' => ['required']
      ],
      [
          'name' => 'charge',
          'label' => 'Περιγραφή συμβάντος:',
          'class' => 'col-12 x5-lines',
          'type' => 'textarea',
          'attributes' => ['required']
      ],
      [
          'name' => 'apology',
          'label' => 'Απολογία: (μαθητή/-των)',  // + of_student
          'type' => 'textarea',
          'attributes' => []
      ],
      [
          'name' => 'decision_reasoning',
          'label' => 'Αιτιολογία απόφασης (πχ. Επειδή...)',
          'class' => 'col-12 x5-lines',
          'type' => 'textarea',
          'attributes' => []
      ],
      [
          'label' => 'Το Πειθαρχικό Συμβούλιο',
          'type' => 'label'
      ],
      [
          'name' => 'decision',
          'label' => 'Αποφασίζει',
          'type' => 'select',
          'options' => [
              'προφορική επίπληξη',
              'ωριαία αποβολή',
              'ημερήσια αποβολή'
          ],
          'attributes' => []
      ],
      [
          'name' => 'decision_more',
          'label' => '(μη τυποποιημένο κείμενο απόφασης)',
          'attributes' => []
      ],
      [
          'name' => 'president',
          'label' => 'Πρόεδρος συνεδρίασης',
          'type' => 'select',
          'source' => '\app\extends\Cache_service::all_users',
          'attributes' => ['required']
      ],
      [
          'name' => 'members',
          'label' => 'Μέλη',
          'type' => 'select',
          'attributes' => ['required', 'multiple'],
          'source' => '\app\extends\Cache_service::all_users'
      ]
  ]
]);
