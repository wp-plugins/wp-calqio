<?php
return "---
properties:
  slug: 'calqio'
  name: 'Calq.io'
  show_feature_menu_item: true
  storage_key: 'calqio' # should correspond exactly to that in the feature-plugin.php
  menu_title: 'Tracking Options'
# Options Sections
sections:
  -
    slug: 'section_enable_plugin_feature_calqio'
    primary: true
  -
    slug: 'section_calqio_basic_configuration'
  -
    slug: 'section_non_ui'
    hidden: true

# Define Options
options:
  -
    key: 'enable_calqio'
    section: 'section_enable_plugin_feature_calqio'
    default: 'N'
    type: 'checkbox'
    link_info: ''
    link_blog: ''
  -
    key: 'write_key'
    section: 'section_calqio_basic_configuration'
    default: ''
    type: 'text'
    link_info: ''
    link_blog: ''
  -
    key: 'read_key'
    section: 'section_calqio_basic_configuration'
    default: ''
    type: 'text'
    link_info: ''
    link_blog: ''
    hidden: true
  -
    key: 'track_every_page_view'
    section: 'section_calqio_basic_configuration'
    default: 'Y'
    type: 'checkbox'
    link_info: ''
    link_blog: ''
  -
    key: 'tracking_cookie_domain'
    section: 'section_calqio_basic_configuration'
    default: ''
    type: 'text'
    link_info: ''
    link_blog: ''
  -
    key: 'ignore_logged_in_user'
    section: 'section_calqio_basic_configuration'
    default: 'N'
    type: 'checkbox'
    link_info: ''
    link_blog: ''
  -
    key: 'current_plugin_version'
    section: 'section_non_ui'
";