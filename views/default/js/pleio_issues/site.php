<?php ?>
//<script>
elgg.provide('elgg.pleio_issues');

elgg.pleio_issues.init = function() {
    if ($('#pleio-issues-data').length == 0) {
        return true;
    }

    $.getJSON(elgg.get_site_url() + 'issues/list', function(issues) {
        if (issues.length == 0) {
           $('#pleio-issues-data').html(elgg.echo('pleio_issues:noissues'));
        }

        html = '<table id=\'pleio-issues\'>';
        html += '<tr><th>No.</th><th>Omschrijving</th><th>Status</th><th>Mijlpaal</th></tr>';
        $(issues).each(function(i, issue) {
            html += '<tr>';
            html += '<td>#' + issue.number + '</td>';
            html += '<td>';
            html += issue.title;
            
            $(issue.labels).each(function(i, label) {
                html += '<span class=\'label\' style=\'background-color:#' + label.color + '\'>' + label.name + '</span>';
            });
            
            html += '</td>';
            html += '<td>' + elgg.echo('pleio_issues:state:' + issue.state) + '</td>';

            html += '<td>';
            if (issue.due_on) {
                html += issue.due_on;
            } else {
                html += '-';
            }
            html += '</td>';

            html += '</tr>';
        });

        html += '</table>';
        $('#pleio-issues-data').html(html);
    });
}

//register init hook
elgg.register_hook_handler('init', 'system', elgg.pleio_issues.init);