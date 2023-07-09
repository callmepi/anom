<?php 
/** Menu of words (for logged-in user)
 * -----------------------------------------------------------------------------
 * 
 * imported:
 * @var stdClass $user (mandatory)
 * @var boolen $is_admin (mandatory)
 */
?>
<div class="info alter">
    <p>&nbsp;</p>
    <p>
        <b><?=$user->first_name?> <?=$user->last_name?></b>
        <?php if ($is_admin) : ?>
            (Administrator)
        <?php endif; ?>
    </p>

    <p><b><?=$user->position?></b></p>
    <p><b><?=$user->sector?></b></p>

    <p>AM: <b><?=$user->registration_number?></b></p>

    <p>Email: <b><?=$user->email?></b></p>
    <p>Tηλέφωνο: <b><?=$user->phone?></b></p>
    
</div>

