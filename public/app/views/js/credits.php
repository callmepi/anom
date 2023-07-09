<script>
// create image-credits div
let ic_div = document.createElement('div');
ic_div.innerHTML = '<a href="<?=THEME['url']?>" target="_blank" title="Attribution for the lovely background image"><?=THEME['label']?></a>';
ic_div.className = 'image-credits';

// append image-credits div to body
document.querySelector('body').appendChild(ic_div);

// make sure...
// the user agrees with cookie-policy on his device
let cookieExp = 3600*24*200;    // cookie expiration (200 days)
if (!cookieExists('operational') || (readCookie('operational') == 'no')) {
    let confirm_cookies = confirm([
        'Ο ιστότοπος χρησιμοποιεί cookies τα οποία έχουν αποκλειστικά λειτουργικό ρόλο.',
        ' ',
        'Ειδικότερα:',
        '– ένα Session cookie που εγγυάται την ασφάλεια της σύνδεσής σας',
        '– ένα Http-only cookie που διατηρεί κωδικοποιημένη την κατάσταση της σύνδεσής σας μετά την είσοδό σας στο σύστημα, και',
        '– ένα cookie που εξετάζει αν έχετε αποδεχθεί τη χρήση αυτών των cookies.',
        ' ',
        'Και τα 3 αυτά cookies είναι Secure, Same-site, Non-Tracking cookies.',
        ' ',
        'Συμφωνείτε με τη διατήρηση αυτών των cookies;'
    ].join('\n'))
    if (!confirm_cookies) {
        window.location.href = 'https://en.wikipedia.org/wiki/HTTP_cookie';
        
    } else {
        createCookie('operational', 'yes', cookieExp);
    }
}

</script>