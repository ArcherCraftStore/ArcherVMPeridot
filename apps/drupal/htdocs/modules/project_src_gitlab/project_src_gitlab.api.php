<?php

/**
 * @file
 * This file contains no working PHP code; it exists to provide additional
 * documentation for doxygen as well as to document hooks in the standard Drupal
 * manner.
 */

/**
 * @defgroup project_src_gitlab Project Source: GitLab module integrations.
 *
 * Module integrations with the Project Source: GitLab module.
 */

/**
 * @defgroup project_src_gitlab_hooks Project Source: GitLab's hooks.
 * @{
 * Hooks that can be implemented by other modules in order to extend the Project
 * Source: GitLab module. You can place these hooks in the same project_src .inc
 * file allowed by the Project Source module: my_module.project_src.inc.
 */

/**
 * React when a GitLab web hook is received.
 *
 * @param $payload
 *   An array of GitLab web hook payload data. Consult GitLab's hook API
 *   documentation for more details.
 */
function hook_project_src_gitlab_webhook($payload) {
  // Perform your custom logic here.
}

/**
 * @}
 */
