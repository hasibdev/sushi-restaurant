<?php

namespace Jara\App\Views;

use Jara\App\Controllers\BlogController;

class BlogView
{
  public $controller;

  function __construct()
  {
    $this->controller = new BlogController();
  }

  function get_blogs($search = null)
  {
    header('Content-Type: application/json');

    if (isset($search[0])) {
      echo '
        {
            "id": 1,
            "title": "How to create effective webdeisign?",
            "shortDescription": "In hac habitasse platea dictumst. Curabitur vestibulum nulla non efficitur pulvinar.",
            "content": "<p class=lead>In hac habitasse platea dictumst. Curabitur vestibulum nulla non efficitur pulvinar. Nulla pulvinar est in eros vehicula lacinia. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p><p>Aenean rutrum dapibus molestie. Praesent eu nibh a magna maximus semper. Aliquam odio nulla, ornare et imperdiet dignissim, rutrum et felis. Nulla in eros et magna vehicula tincidunt quis eget orci. Quisque in est ac augue bibendum fringilla quis a odio. Donec mollis consectetur commodo. Cras interdum ac nibh id sodales. Phasellus egestas varius pulvinar. Aliquam sit amet felis sit amet purus vestibulum dictum in sit amet justo. Nam blandit arcu porttitor, faucibus purus a, vehicula odio. Pellentesque semper, odio sed commodo iaculis, augue mauris scelerisque mi, in mattis elit justo ut erat. Nulla cursus turpis sollicitudin, eleifend lectus non, tristique nunc. Fusce vitae varius tellus.</p>",
            "media": {
              "type": "image",
              "url": "http://assets.suelo.pl/soup/img/posts/post01.jpg",
              "sizes": {
                "md": "http://assets.suelo.pl/soup/img/posts/post01.jpg",
                "lg": "http://assets.suelo.pl/soup/img/posts/post01_lg.jpg"
              }
            },
            "createdAt": "2020-07-14T11:12:18+0000",
            "author": "Johnatan Doe"
          }';
    } else {
      echo '[
          {
            "id": 1,
            "title": "How to create effective webdeisign?",
            "shortDescription": "In hac habitasse platea dictumst. Curabitur vestibulum nulla non efficitur pulvinar.",
            "content": "<p class=lead>In hac habitasse platea dictumst. Curabitur vestibulum nulla non efficitur pulvinar. Nulla pulvinar est in eros vehicula lacinia. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p><p>Aenean rutrum dapibus molestie. Praesent eu nibh a magna maximus semper. Aliquam odio nulla, ornare et imperdiet dignissim, rutrum et felis. Nulla in eros et magna vehicula tincidunt quis eget orci. Quisque in est ac augue bibendum fringilla quis a odio. Donec mollis consectetur commodo. Cras interdum ac nibh id sodales. Phasellus egestas varius pulvinar. Aliquam sit amet felis sit amet purus vestibulum dictum in sit amet justo. Nam blandit arcu porttitor, faucibus purus a, vehicula odio. Pellentesque semper, odio sed commodo iaculis, augue mauris scelerisque mi, in mattis elit justo ut erat. Nulla cursus turpis sollicitudin, eleifend lectus non, tristique nunc. Fusce vitae varius tellus.</p>",
            "media": {
              "type": "image",
              "url": "http://assets.suelo.pl/soup/img/posts/post01.jpg",
              "sizes": {
                "md": "http://assets.suelo.pl/soup/img/posts/post01.jpg",
                "lg": "http://assets.suelo.pl/soup/img/posts/post01_lg.jpg"
              }
            },
            "createdAt": "2020-07-14T11:12:18+0000",
            "author": "Johnatan Doe"
          },
          {
            "id": 2,
            "title": "Awesome weekend in Polish mountains!",
            "shortDescription": "In hac habitasse platea dictumst. Curabitur vestibulum nulla non efficitur pulvinar.",
            "content": "<p class=lead>In hac habitasse platea dictumst. Curabitur vestibulum nulla non efficitur pulvinar. Nulla pulvinar est in eros vehicula lacinia. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p><p>Aenean rutrum dapibus molestie. Praesent eu nibh a magna maximus semper. Aliquam odio nulla, ornare et imperdiet dignissim, rutrum et felis. Nulla in eros et magna vehicula tincidunt quis eget orci. Quisque in est ac augue bibendum fringilla quis a odio. Donec mollis consectetur commodo. Cras interdum ac nibh id sodales. Phasellus egestas varius pulvinar. Aliquam sit amet felis sit amet purus vestibulum dictum in sit amet justo. Nam blandit arcu porttitor, faucibus purus a, vehicula odio. Pellentesque semper, odio sed commodo iaculis, augue mauris scelerisque mi, in mattis elit justo ut erat. Nulla cursus turpis sollicitudin, eleifend lectus non, tristique nunc. Fusce vitae varius tellus.</p>",
            "media": {
              "type": "image",
              "url": "http://assets.suelo.pl/soup/img/posts/post02.jpg",
              "sizes": {
                "md": "http://assets.suelo.pl/soup/img/posts/post02.jpg",
                "lg": "http://assets.suelo.pl/soup/img/posts/post02_lg.jpg"
              }
            },
            "createdAt": "2020-08-12T11:12:18+0000",
            "author": "Johnatan Doe"
          }
        ]';
    }
  }
}
