<?php 

$sorry = get_template_directory_uri() . "/assets/sorry.jpeg";

?>

<?php get_header(); ?>

<div class="d-flex flex-column justify-content-center align-items-center">
    <img src="<?php echo $sorry; ?>" alt="">
    <h1>404 </h1>
    <h3>Page Not Found</h3>
    <p>Sorry, we could not find what you are looking for</p>
</div>

<?php get_footer(); ?>