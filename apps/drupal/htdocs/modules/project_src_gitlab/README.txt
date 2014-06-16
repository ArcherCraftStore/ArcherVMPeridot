-- SUMMARY --

This module communicates with your GitLab instance using your account token to
automatically generate project status XML suitable for use by the core Drupal
Update module and drush.

By simply following existing drupal.org module/repository/release naming
conventions, any project will be automatically registered as a project and
listed at http://example.com/drupal/release-history/YOUR_PROJECT/API_VERSION.

For more details on drupal.org release naming conventions, please see:
https://drupal.org/node/1015226

For more details on integrating your custom Drupal extensions with Drupal's
update status workflow, see the README.txt of the Project Source module.

To more tightly couple GitLab with your Project Source: GitLab server, note that
a web hook URL is made available at http://example.com/drupal/gitlab/webhook
