<?php

/**
 * @file
 * Admin hooks and functions for the Project Source: GitLab module.
 */


/**
 * Admin form for the Project Source: GitLab module.
 */
function project_src_gitlab_settings($form_state) {
  $form = array();

  // GitLab URL.
  $form['project_src_gitlab_url'] = array(
    '#type' => 'textfield',
    '#title' => t('GitLab URL'),
    '#description' => t('The URL of your GitLab instance, including URI scheme (e.g. https://gitlab.com)'),
    '#default_value' => variable_get('project_src_gitlab_url', 'https://gitlab.com'),
    '#required' => TRUE,
  );

  // GitLab API Token.
  $form['project_src_gitlab_token'] = array(
    '#type' => 'textfield',
    '#title' => t('GitLab Token'),
    '#description' => t('The API token associated with the account used to find custom projects.'),
    '#default_value' => variable_get('project_src_gitlab_token', ''),
  );

  $form['actions']['cache_clear'] = array(
    '#type' => 'submit',
    '#value' => t('Clear project cache'),
    '#weight' => 10,
    '#submit' => array('project_src_gitlab_clear_cache_submit'),
  );

  return system_settings_form($form);
}


/**
 * Submit handler to clear Project Source: GitLab's cached projects/releases.
 */
function project_src_gitlab_clear_cache_submit($form, &$form_state) {
  drupal_set_message(t('GitLab project cache data cleared successfully.'));
  project_src_gitlab_clear_cache();
}
