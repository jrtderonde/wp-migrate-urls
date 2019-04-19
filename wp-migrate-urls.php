<?php

  // Class for checking
  class Migrator {

    // Constructor
    function __construct () {
      global $wpdb;
      $this->wpdb     = $wpdb;
      $this->sitename = get_site_url();
    }

    // get name
    public function makeName () {

      // check for https
      if (empty($_SERVER['HTTPS']) === false && $_SERVER['HTTPS'] === 'on') {
        $link = "https";
      } else {
        $link = "http";
      }

      // Set up url
      $link .= "://";
      $link .= $_SERVER['HTTP_HOST'];

      // Print the link
      return $link;
    }

    // check name
    public function checkName () {

      // Do check
      if ($this->sitename !== $this->makeName()) {
        return false;
      } else {
        return true;
      }
    }

    // set name
    public function setName () {

      // Do check
      if ($this->checkName() === false) {
        // Array with queries
        $queries = [
          "UPDATE wp_options SET option_value = replace(option_value, '" . $this->sitename . "', '" . $this->makeName() . "') WHERE option_name = 'home' OR option_name = 'siteurl';",
          "UPDATE wp_posts SET guid = replace(guid, '" . $this->sitename . "', '" . $this->makeName() . "');",
          "UPDATE wp_posts SET post_content = replace(post_content, '" . $this->sitename . "', '" . $this->makeName() . "');",
          "UPDATE wp_postmeta SET meta_value = replace(meta_value, '" . $this->sitename . "', '" . $this->makeName() . "');",
        ];

        // Loop and query
        foreach ($queries as $q) {
          // Fire query
          $this->wpdb->query($q);
        }

        return true;
        // Run update wp_options
      } else {
        return false;
      }
    }
  }

  // Declare
  $migrator = new Migrator();

  // Fire migrator
  $migrator->setName();
