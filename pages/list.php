<?php
if (!elgg_get_plugin_setting('username') | !elgg_get_plugin_setting('password') | !elgg_get_plugin_setting('repository')) {
    exit;
}

if (is_memcache_available()) {
    $memcache = new ElggMemcache('pleio_issues');
    $memcache->setDefaultExpiry(300);
    $output = $memcache->load('output');
} else {
    $output = false;
}

if (!$output) {
    $client = new GithubClient();

    $client->setCredentials(elgg_get_plugin_setting('username'), elgg_get_plugin_setting('password'));
    $issues = $client->request('/repos/' . elgg_get_plugin_setting('repository') . '/issues', 'GET', array('state'=>'all'));
    $output = array();
    $milestones = array();

    foreach ($issues as $issue) {
        $output[] = array(
            'number' => $issue->number,
            'state' => $issue->state,
            'title' => $issue->title,
            'labels' => $issue->labels,
            'due_on' => ($issue->milestone && $issue->state == "open" ? date("d-m", strtotime($issue->milestone->due_on)) : false)
        );
    }

    if (is_memcache_available()) {
        $memcache->save('output', $output);
    }

}

header("Content-type: application/json");
echo json_encode($output);
exit;