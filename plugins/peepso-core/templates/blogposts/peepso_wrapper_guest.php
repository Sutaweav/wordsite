<?php if(strlen($header)) { ?>
    <h2><?php echo $header;?></h2>
<?php } ?>


<?php
$args = array();
if( 1==PeepSo::get_option('blogposts_comments_no_cover' ,0)) {
    $args['no_cover'] = TRUE;
}
PeepSoTemplate::exec_template('general', 'register-panel', $args);?>

<?php if(strlen($header_comments)) { ?>
    <h3><?php echo $header_comments; ?></h3>
<?php } ?>

{peepso_comments}