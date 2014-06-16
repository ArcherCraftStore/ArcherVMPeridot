<?php

/**
 * @file
 * Hooks and functions related to Project Source: GitLab web hook reactions.
 */


/**
 * Callback function for GitLab web hook handling.
 */
function project_src_gitlab_webhook() {
  // We're only expecting to respond to POST requests.
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = drupal_json_decode(file_get_contents('php://input'));

    // Check the ref and ensure this is a Drupal release branch.
    $branch = isset($input['ref']) ? trim(str_replace('refs/heads/', '', $input['ref'])) : '';
    if (preg_match_all("/^(\\d+).x-(\\d+).x$/is", $branch, $version)) {
      // Clear the Project Source: GitLab cache.
      project_src_gitlab_clear_cache();

      // Log the commit.
      $username = isset($input['user_name']) ? $input['user_name'] : t('Someone');
      $repo = isset($input['repository']['name']) ? $input['repository']['name'] : t('some repository');
      $repo_url = isset($input['repository']['homepage']) ? $input['repository']['homepage'] : variable_get('project_src_gitlab_url', 'https://gitlab.com');
      watchdog('project src gitlab', '@name committed to the @branch branch of !project_name. Project Source: GitLab cache was cleared.', array(
        '@name' => $username,
        '@branch' => $branch,
        '!project_name' => l($repo, $repo_url),
      ), WATCHDOG_INFO);

      // Allow other modules to react to the web hook.
      module_invoke_all('project_src_gitlab_webhook', $input);
    }

    // Indicate a positive response and exit.
    drupal_add_http_header('Status', '204 No Content');
    drupal_exit();
  }
  else {
    drupal_add_http_header('Status', '400 Bad Request');
    drupal_exit();
  }
}
