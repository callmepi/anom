<?php 
/** Menu of words (for logged-in user)
 * -----------------------------------------------------------------------------
 * 
 * imported:
 * @var boolen $is_admin (mandatory)
 */
?>
<ul class="list-group">

    <li class="list-group-item"><a href="/petition/application">Νέα Αίτηση</a></li>
    <li class="list-group-item"><a href="/petition/penalty">Νέo Πρακτικό Πειθαρχικής Υπόθεσης</a></li>

    <li class="list-group-item separator">To αρχείο σας</li>
    <li class="list-group-item"><a href="/user/petitions">Πρακτικά και αιτήσεις</a></li>

    <li class="list-group-item separator">Προφίλ</li>
    <li class="list-group-item"><a href="/user/update_form">Ενημέρωση στοιχείων</a></li>
    <li class="list-group-item"><a href="/user/password_form">Αλλαγή password</a></li>

  <?php if ($is_admin) : ?>
    <li class="list-group-item separator">Διαχείριση</li>
    <li class="list-group-item"><a href="/admin/petitions">Κατάσταση πρακτικών & αιτήσεων</a></li>
    <li class="list-group-item"><a href="/admin/users">Διαχείριση Χρηστών</a></li>
    <li class="list-group-item"><a href="/admin/invite">Αποστολή προσκλήσεων</a></li>
  <?php endif; ?>

    <li class="list-group-item separator"></li>

    <li class="list-group-item"><a href="/logout">Αποσύνδεση</a></li>

</ul>
