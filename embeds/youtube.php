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
<h1><?php echo _( 'Add a YouTube Video' ); ?></h1>
<form action="https://www.youtube.com/results" method="get">
<input type="text" name="search_query" />
<input type="submit" value="<?php echo _( 'Search' ); ?>" />
</form>
<h2><?php echo _( 'Instructions' ); ?></h2>
<ol>
<li><?php echo _( 'Search your keywords above' ); ?></li>
<li><?php echo _( 'Browse videos' ); ?></li>
<li><?php echo _( 'Once you\'ve found a video, copy the full URL<br /><em>(example:' ); ?> <strong>https://www.youtube.com/watch?v=DXUAyRRkI6k</strong>)</em></li>
<li><?php echo _( 'Paste it as-is into your post content area<br /><em>(WordPress will automatically embed it — no need to add embed code, short link, or any other method)' ); ?></em></li>
</ol>
</main>
</body>
</html>