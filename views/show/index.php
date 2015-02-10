<? foreach ($errors as $name => $content): ?>
 <?= $this->render_partial('show/_table.php', array("name" => $name, "content" => $content)); ?>
<? endforeach; ?>
