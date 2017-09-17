<h2><?php echo $title; ?></h2>
<ol>
<?php foreach($list as $item): ?>
	<li><?php echo $item['SEQ'] . ' : ' . $item['CLASSNAME']; ?></li>
<?php endforeach; ?>
</ol>