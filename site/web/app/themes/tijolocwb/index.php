<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
  <?php //echo \Roots\view('layouts/head/preload')->render(); ?>
  <?php echo \Roots\view('layouts/head/favicon')->render(); ?>
  <?php echo \Roots\view('layouts/head/gconsole')->render(); ?>
  <?php echo \Roots\view('layouts/head/gtaghead')->render(); ?>
  <?php echo \Roots\view('partials/snippets/schema')->render(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <?php echo \Roots\view('partials/snippets/gtagbody')->render(); ?>
  <?php do_action('get_header'); ?>

  <div id="app">
    <?php echo view(app('sage.view'), app('sage.data'))->render(); ?>
  </div>

  <?php do_action('get_footer'); ?>
  <?php wp_footer(); ?>
</body>

</html>
