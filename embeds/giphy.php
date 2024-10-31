<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width" />
<meta name="robots" content="noindex" />
<link rel="stylesheet" href="../../../themes/publishers/style.css" />
</head>
<body>
<main id="content" class="entry-content">
<h1><?php echo _( 'Add a GIPHY gif' ); ?></h1>
<form action="" method="" id="gif-search">
<input type="text" id="keywords" />
<input type="submit" value="<?php echo _( 'Search' ); ?>" />
</form>
<h2><?php echo _( 'Instructions' ); ?></h2>
<ol>
<li><?php echo _( 'Search your keywords above' ); ?></li>
<li><?php echo _( 'Browse gifs' ); ?></li>
<li><?php echo _( 'Once you\'ve found a gif, copy the full URL<br /><em>(example:' ); ?> <strong>https://giphy.com/gifs/cats-cute-cat-catception-H4DjXQXamtTiIuCcRU</strong>)</em></li>
<li><?php echo _( 'Paste it as-is into your post content area<br /><em>(WordPress will automatically embed it — no need to add embed code, short link, or any other method)' ); ?></em></li>
</ol>
<script src="../../../../wp-includes/js/jquery/jquery.min.js"></script>
<script>
jQuery(function($) {
$('#gif-search').submit(function(){
var keywords = $('#keywords').val();
$(this).attr('action', "https://giphy.com/search/" + keywords);
});
});
</script>
</main>
</body>
</html>