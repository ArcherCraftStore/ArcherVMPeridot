<?php

/**
 * @file
 * Private functions used by the Project Source: GitLab module. These functions
 * should not be used by any other module; no API compatibility is guaranteed.
 */


/**
 * Formats a release info array given a GitLab API git commit ref array
 * structure, the project info array, and a file URL.
 *
 * @param array $branch
 *   A GitLab API git commit ref array structure (e.g. commit/branch)
 *
 * @param array $info
 *   A project info array as provided by hook_project_src_info()
 *
 * @return array
 *   A release array suitable for use in the primary release theme function.
 */
function _project_src_gitlab_format_release_info($commitref, $info) {
  // Root of the project on GitLab.
  $gitlab = variable_get('project_src_gitlab_url', 'https://gitlab.com');
  $root = $gitlab . '/' . $info['api']['path_with_namespace'];

  // Filter $commitref['name'] here, since it's used very often below.
  $commitref['name'] = check_plain($commitref['name']);

  // Save relevant values to a release array.
  $release['name'] = $info['short_name'] . ' ' . $commitref['name'];
  $release['version'] = $release['tag'] = $commitref['name'];
  $release['date'] = strtotime($commitref['commit']['committed_date']);
  if (preg_match_all("/^\\d+\\.x-(\\d+)\\.([x\\d+])-?(.*)?/is", $commitref['name'], $version)) {
    $release['version_major'] = (int) $version[1][0];
    $release['version_patch'] = is_numeric($version[2][0]) ? (int) $version[2][0] : NULL;
    $release['version_extra'] = isset($version[3][0]) ? $version[3][0] : NULL;
  }
  $release['status'] = 'published';
  $release['release_link'] = check_url($root);

  // Details related to the download file.
  $download_root = 'drupal/release-files/';
  if ($file_contents = _project_src_gitlab_file_as_string($root . '/repository/archive', $commitref['name'])) {
    $release['download_link'] = url($download_root . $info['api']['path_with_namespace'] . '/' . $commitref['name'] . '/download.tar.gz', array(
      'absolute' => TRUE,
    ));
    $release['mdhash'] = md5($file_contents);
    $release['filesize'] = strlen($file_contents);
    $release['archive_type'] = 'tar.gz';
  }

  return $release;
}


/**
 * Returns the response of an API request to GitLab.
 *
 * @param string $url
 *   The URL of the desired GitLab API resource.
 *
 * @return array
 *   The contents of the unencoded GitLab response or an empty array on failure.
 */
function _project_src_gitlab_api_call($url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('PRIVATE-TOKEN: ' . variable_get('project_src_gitlab_token', '')));
  $response = curl_exec($ch);
  curl_close($ch);

  if ($response) {
    return drupal_json_decode($response);
  }
  else {
    return array();
  }
}


/**
 * Returns the contents of a file as a string given a file URL.
 *
 * @param string $file
 *   The URL of the file.
 *
 * @return string
 *   The contents of the specified file or FALSE on error.
 */
function _project_src_gitlab_file_as_string($file, $ref) {
  $contents = FALSE;

  if ($token = variable_get('project_src_gitlab_token', '')) {
    $file = url($file, array('query' => array(
      'ref' => $ref,
      'private_token' => $token,
    )));

    $ch = curl_init($file);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $contents = curl_exec($ch);
    curl_close($ch);
  }

  return $contents;
}
