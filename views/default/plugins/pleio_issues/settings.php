<p>
  <?php 
  echo elgg_echo('pleio_issues:username');
  echo elgg_view("input/text", array(
  	'name' => 'params[username]',
  	'value' => $vars['entity']->username
  ));
  ?>
</p>
<p>
  <?php 
  echo elgg_echo('pleio_issues:password');
  echo elgg_view('input/text', array(
  	'name' => 'params[password]'
  ));
  ?>
</p>
<p>
  <?php 
  echo elgg_echo('pleio_issues:repository');
  echo elgg_view("input/text", array(
  	'name' => 'params[repository]',
  	'value' => $vars['entity']->repository
  ));
  ?>
</p>
