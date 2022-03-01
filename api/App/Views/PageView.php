<?php

namespace Jara\App\Views;

use Jara\App\Configs\Config;
use Jara\App\Controllers\PageController;

class PageView
{
  public $controller;

  function __construct()
  {
    $this->controller = new PageController();
    $this->api = Config::$app_url;
  }

  public function get_page($slug)
  {
    if (!isset($slug[0])) {
      echo '{
        "pageTitle": null,
        "sections": [
          {
            "name": "HeroCentered",
            "props": {
              "media": {
                "type": "video",
                "source": {
                  "title": "Video Title",
                  "url": "http://localhost:4000/uploads/videos/video.mp4"
                }
              },
              "logo": "https://assets.suelo.pl/soup/img/logo-light-green.svg",
              "title": "Meilleur Sushi & Poke",
              "tagline": "and use it with your next order!",
              "link": {
                "title": "Check our menu",
                "slug": "/menu-grid-navigation"
              }
            }
          },
          {
            "name": "ImageEdge",
            "props": {
              "content": {
                "rate": 4,
                "title": "The best food in London!",
                "description": "Donec a eros metus. Vivamus volutpat leo dictum risus ullamcorper condimentum. Cras sollicitudin varius condimentum. Praesent a dolor sem....",
                "reviews": [
                  {
                    "content": "It’ was amazing feeling for may belly!",
                    "rate": 4,
                    "source": "Booking",
                    "author": {
                      "name": "Mark Johnson",
                      "thumbnail": {
                        "title": "Image Title",
                        "url": "https://assets.suelo.pl/soup/img/avatars/avatar02.jpg"
                      }
                    }
                  },
                  {
                    "content": "Great food and great atmosphere!",
                    "rate": 5,
                    "source": "Facebook",
                    "author": {
                      "name": "Kate Hudson",
                      "thumbnail": {
                        "title": "Image Title",
                        "url": "https://assets.suelo.pl/soup/img/avatars/avatar01.jpg"
                      }
                    }
                  }
                ]
              },
              "image": {
                "title": "Image Title",
                "url": "' . $this->api . '/uploads/images/review.jpeg"
              },
              "reverse": true
            }
          },
          {
            "name": "Categories",
            "props": {
              "title": null
            }
          },
          {
            "name": "Offers",
            "props": {
              "title": "Special Offers",
              "theme": "light"
            }
          },
          {
            "name": "Features",
            "props": {
              "theme": "dark",
              "bg": "dark",
              "items": [
                {
                  "title": "Pick a dish",
                  "description": "Vivamus volutpat leo dictum risus ullamcorper condimentum.",
                  "icon": "shopping-cart"
                },
                {
                  "title": "Make a payment",
                  "description": "Vivamus volutpat leo dictum risus ullamcorper condimentum.",
                  "icon": "wallet"
                },
                {
                  "title": "Recieve your food!",
                  "description": "Vivamus volutpat leo dictum risus ullamcorper condimentum.",
                  "icon": "package"
                }
              ],
              "extend": "left"
            }
          },
          {
            "name": "LatestPosts",
            "props": {
              "title": "LatestPosts",
              "theme": "light"
            }
          },
          {
            "name": "Cta",
            "props": {
              "theme": "dark",
              "bg": "dark",
              "image": {
                "title": "Image Title",
                "url": "' . $this->api . '/uploads/images/cta.jpeg"
              },
              "title": "Check our promo video!",
              "tagline": "Book a table even right now or make an online order!",
              "link": {
                "title": "Order now!",
                "slug": "menu-grid-navigation"
              }
            }
          }
        ],
        "settings": {
          "headerTransparent": true,
          "hideHeaderLogo": false
        }
      }';
      exit();
    }
    switch ($slug[0]) {
      case "about":
        echo '{"pageTitle":{"title":"About Us","theme":"light","tagline":"Some informations about our restaurant"},"sections":[{"name":"ImageEdge","props":{"content":{"rate":4,"title":"The best food in London!","descriptionLead":"Donec a eros metus. Vivamus volutpat leo dictum risus ullamcorper condimentum. Cras sollicitudin varius condimentum. Praesent a dolor sem....","description":"Nam eleifend elementum sapien et bibendum. Nunc ac diam efficitur, ultrices lorem quis, consectetur odio. Nullam vulputate, eros quis accumsan cursus, elit lectus bibendum nulla, sed dapibus ligula tellus at purus. Fusce id eros id mi cursus semper. Quisque efficitur bibendum nunc a consectetur. Maecenas vitae quam iaculis, scelerisque purus nec, varius purus. Nullam eget varius elit. Donec eget facilisis nunc, non rutrum lorem.","author":"Mark Johnson, Chef","sign":"https://assets.suelo.pl/soup/img/svg/sign.svg"},"image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/bg-chef.jpg"},"reverse":true}},{"name":"CtaAlternative","props":{"image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/bg-croissant.jpg"},"title":"Check our promo video!","tagline":"Book a table even right now or make an online order!","modalVideo":"https://www.youtube.com/embed/uVju5--RqtY","bg":"dark","theme":"dark"}}]}';
        break;

      case "contact":
        echo '{"pageTitle":{"title":"Contact","theme":"light","tagline":"Some informations about our restaurant"},"sections":[{"name":"Location","props":{"location":{"name":"Soup Restaurant","address":{"street":"St John 21/52","city":"Carcow","contry":"Poland"},"phone":"+48 21200 2122 221","email":"hello@example.com","lat":40.758895,"lng":-73.985131,"openHours":[{"days":[0],"openHour":"12:00","closeHour":"22:00"},{"days":[1,2,3,4,5],"openHour":"8:00","closeHour":"20:00"},{"days":[6],"openHour":"12:00","closeHour":"24:00"}]},"reverse":false}},{"name":"Location","props":{"location":{"name":"Soup Bistro","address":{"street":"St John 21/52","city":"Carcow","contry":"Poland"},"phone":"+48 21200 2122 221","email":"hello@example.com","lat":40.758895,"lng":-73.985131,"openHours":[{"days":[0],"openHour":"12:00","closeHour":"22:00"},{"days":[1,2,3,4,5],"openHour":"8:00","closeHour":"20:00"},{"days":[6],"openHour":"12:00","closeHour":"24:00"}]},"reverse":true}}]}';
        break;

      case "faq":
        echo  '{"pageTitle":{"title":"FAQ","theme":"light","tagline":"Some informations about our restaurant"},"categories":[{"id":1,"name":"General","questions":[{"id":1,"title":"How to make an order?","content":"Donec rutrum turpis justo, vel condimentum neque fringilla vel. Nam feugiat at lacus eu egestas. Suspendisse nibh sem, blandit sed elit at, dictum accumsan elit. Sed eu orci risus. Suspendisse ante nisi, vestibulum suscipit sagittis ut, elementum id quam. Aliquam eget ullamcorper odio."},{"id":2,"title":"Is the order process safe for customers?","content":"Donec rutrum turpis justo, vel condimentum neque fringilla vel. Nam feugiat at lacus eu egestas. Suspendisse nibh sem, blandit sed elit at, dictum accumsan elit. Sed eu orci risus. Suspendisse ante nisi, vestibulum suscipit sagittis ut, elementum id quam. Aliquam eget ullamcorper odio."}]},{"id":2,"name":"Delivery","questions":[{"id":1,"title":"How to make an order?","content":"Donec rutrum turpis justo, vel condimentum neque fringilla vel. Nam feugiat at lacus eu egestas. Suspendisse nibh sem, blandit sed elit at, dictum accumsan elit. Sed eu orci risus. Suspendisse ante nisi, vestibulum suscipit sagittis ut, elementum id quam. Aliquam eget ullamcorper odio."},{"id":2,"title":"Is the order process safe for customers?","content":"Donec rutrum turpis justo, vel condimentum neque fringilla vel. Nam feugiat at lacus eu egestas. Suspendisse nibh sem, blandit sed elit at, dictum accumsan elit. Sed eu orci risus. Suspendisse ante nisi, vestibulum suscipit sagittis ut, elementum id quam. Aliquam eget ullamcorper odio."},{"id":3,"title":"How to make an order?","content":"Donec rutrum turpis justo, vel condimentum neque fringilla vel. Nam feugiat at lacus eu egestas. Suspendisse nibh sem, blandit sed elit at, dictum accumsan elit. Sed eu orci risus. Suspendisse ante nisi, vestibulum suscipit sagittis ut, elementum id quam. Aliquam eget ullamcorper odio."}]},{"id":3,"name":"Payments","questions":[{"id":1,"title":"How to make an order?","content":"Donec rutrum turpis justo, vel condimentum neque fringilla vel. Nam feugiat at lacus eu egestas. Suspendisse nibh sem, blandit sed elit at, dictum accumsan elit. Sed eu orci risus. Suspendisse ante nisi, vestibulum suscipit sagittis ut, elementum id quam. Aliquam eget ullamcorper odio."},{"id":2,"title":"Is the order process safe for customers?","content":"Donec rutrum turpis justo, vel condimentum neque fringilla vel. Nam feugiat at lacus eu egestas. Suspendisse nibh sem, blandit sed elit at, dictum accumsan elit. Sed eu orci risus. Suspendisse ante nisi, vestibulum suscipit sagittis ut, elementum id quam. Aliquam eget ullamcorper odio."},{"id":3,"title":"How to make an order?","content":"Donec rutrum turpis justo, vel condimentum neque fringilla vel. Nam feugiat at lacus eu egestas. Suspendisse nibh sem, blandit sed elit at, dictum accumsan elit. Sed eu orci risus. Suspendisse ante nisi, vestibulum suscipit sagittis ut, elementum id quam. Aliquam eget ullamcorper odio."}]}]}';
        break;

      case "gallery":
        echo '{"pageTitle":{"title":"Our Gallery","theme":"dark","tagline":"Some informations about our restaurant"},"sections":[{"name":"Gallery","props":{"items":[{"title":"Photo Title","url":"https://assets.suelo.pl/soup/img/gallery/gallery01.jpg"},{"title":"Photo Title","url":"https://assets.suelo.pl/soup/img/gallery/gallery02.jpg"},{"title":"Photo Title","url":"https://assets.suelo.pl/soup/img/gallery/gallery03.jpg"},{"title":"Photo Title","url":"https://assets.suelo.pl/soup/img/gallery/gallery04.jpg"},{"title":"Photo Title","url":"https://assets.suelo.pl/soup/img/gallery/gallery05.jpg"}]}},{"name":"Cta","props":{"image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/bg-croissant.jpg"},"title":"Check our promo video!","tagline":"Book a table even right now or make an online order!","modalVideo":"https://www.youtube.com/embed/uVju5--RqtY","bg":"dark","theme":"dark"}}]}';
        break;

      case "index-burgers":
        echo '{"pageTitle":null,"sections":[{"name":"HeroSimple","props":{"media":{"type":"image","source":{"title":"Video Title","url":"https://assets.suelo.pl/soup/img/photos/hero-burger.jpg"}},"title":"The Best Burgers in the city!","tagline":"and use it with your next order!","link":{"title":"Check our menu","slug":"/menu-list-navigation"}}},{"name":"ImageEdge","props":{"content":{"rate":4,"title":"Why our Burgers?","description":"Donec a eros metus. Vivamus volutpat leo dictum risus ullamcorper condimentum. Cras sollicitudin varius condimentum. Praesent a dolor sem....","features":[{"icon":"desktop","title":"Online Order","description":"Vivamus volutpat leo dictum risus ullamcorper condimentum."},{"icon":"heart","title":"Perfect Taste","description":"Vivamus volutpat leo dictum risus ullamcorper condimentum."}]},"image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/bg-burger.jpg"},"reverse":false}},{"name":"Categories","props":{"title":null}},{"name":"Menu","props":{"title":"Our Menu","categories":[1,2,3]}},{"name":"Features","props":{"theme":"dark","bg":"dark","items":[{"title":"Pick a dish","description":"Vivamus volutpat leo dictum risus ullamcorper condimentum.","icon":"shopping-cart"},{"title":"Make a payment","description":"Vivamus volutpat leo dictum risus ullamcorper condimentum.","icon":"wallet"},{"title":"Recieve your food!","description":"Vivamus volutpat leo dictum risus ullamcorper condimentum.","icon":"package"}]}},{"name":"CtaAlternative","props":{"theme":"dark","bg":"dark","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/bg-burger2.jpg"},"title":"Check our promo video!","tagline":"Book a table even right now or make an online order!","modalVideo":"https://www.youtube.com/embed/uVju5--RqtY","link":{"title":"Order now!","slug":"/menu-list-collapse"}}}],"settings":{"headerTransparent":true,"hideHeaderLogo":false}}';
        break;

      case "index-slider":
        echo '{"pageTitle":null,"sections":[{"name":"HeroSlider","props":{"theme":"dark","slides":[{"type":"PRODUCT","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/slider-burger.jpg"},"productId":1,"title":"American Classic","tagline":"New product!","price":9.98},{"type":"BASIC","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/slider-pasta.jpg"},"offerId":1,"title":"Get 10% off coupon","tagline":"and use it with your next order!","link":{"title":"Check our offers","slug":"/offers"}},{"type":"BASIC","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/slider-dessert.jpg"},"offerId":1,"title":"Delicious Desserts","tagline":"Order it online even now!","link":{"title":"Check our menu","slug":"/menu-list-navigation"}}]}},{"name":"ImageEdge","props":{"content":{"rate":4,"title":"The best food in London!","description":"Donec a eros metus. Vivamus volutpat leo dictum risus ullamcorper condimentum. Cras sollicitudin varius condimentum. Praesent a dolor sem....","reviews":[{"content":"It’ was amazing feeling for may belly!","rate":4,"source":"Booking","author":{"name":"Mark Johnson","thumbnail":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/avatars/avatar02.jpg"}}},{"content":"Great food and great atmosphere!","rate":5,"source":"Facebook","author":{"name":"Kate Hudson","thumbnail":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/avatars/avatar01.jpg"}}}]},"image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/bg-chef.jpg"},"reverse":true}},{"name":"Features","props":{"theme":"dark","bg":"dark","items":[{"title":"Pick a dish","description":"Vivamus volutpat leo dictum risus ullamcorper condimentum.","icon":"shopping-cart"},{"title":"Make a payment","description":"Vivamus volutpat leo dictum risus ullamcorper condimentum.","icon":"wallet"},{"title":"Recieve your food!","description":"Vivamus volutpat leo dictum risus ullamcorper condimentum.","icon":"package"}],"extend":"left"}},{"name":"Offers","props":{"title":"Special Offers","theme":"light","bg":"light"}},{"name":"Categories","props":{"title":"Our Menu"}}]}';
        break;

      case "index-video":
        echo '{"pageTitle":null,"sections":[{"name":"HeroBasic","props":{"theme":"dark","slides":[{"type":"PRODUCT","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/slider-pasta.jpg"},"productId":1,"title":"Boscaiola Pasta","tagline":"New product!","price":9.98},{"type":"BASIC","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/slider-burger.jpg"},"offerId":1,"title":"Get 10% off coupon","tagline":"and use it with your next order!","link":{"title":"Check our offers","slug":"/offers"}},{"type":"BASIC","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/slider-dessert.jpg"},"offerId":1,"title":"Delicious Desserts","tagline":"Order it online even now!","link":{"title":"Check our menu","slug":"/menu-list-navigation"}}]}},{"name":"ImageEdge","props":{"content":{"rate":4,"title":"The best food in London!","description":"Donec a eros metus. Vivamus volutpat leo dictum risus ullamcorper condimentum. Cras sollicitudin varius condimentum. Praesent a dolor sem....","reviews":[{"content":"It’ was amazing feeling for may belly!","rate":4,"source":"Booking","author":{"name":"Mark Johnson","thumbnail":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/avatars/avatar02.jpg"}}},{"content":"Great food and great atmosphere!","rate":5,"source":"Facebook","author":{"name":"Kate Hudson","thumbnail":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/avatars/avatar01.jpg"}}}]},"image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/bg-food.png"},"reverse":false}},{"name":"Features","props":{"theme":"dark","bg":"dark","items":[{"title":"Pick a dish","description":"Vivamus volutpat leo dictum risus ullamcorper condimentum.","icon":"shopping-cart"},{"title":"Make a payment","description":"Vivamus volutpat leo dictum risus ullamcorper condimentum.","icon":"wallet"},{"title":"Recieve your food!","description":"Vivamus volutpat leo dictum risus ullamcorper condimentum.","icon":"package"}],"extend":"right"}},{"name":"Categories","props":{"title":"Our Menu"}},{"name":"Offers","props":{"title":"Special Offers","theme":"light"}},{"name":"Cta","props":{"theme":"dark","bg":"dark","image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/bg-croissant.jpg"},"title":"Check our promo video!","tagline":"Book a table even right now or make an online order!","modalVideo":"https://www.youtube.com/embed/uVju5--RqtY"}}]}';
        break;

      case "offers":
        echo '{"pageTitle":{"title":"Special Offers","theme":"light","tagline":"Some informations about our restaurant"},"sections":[{"name":"Offers","props":{"title":null,"theme":"light","bg":"light","carousel":false}}]}';
        break;

      case "reviews":
        echo '{"pageTitle":{"title":"Reviews","theme":"dark","media":{"type":"image","source":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/bg-croissant.jpg"}},"tagline":"Some informations about our restaurant"},"sections":[{"name":"Reviews","props":{"title":null,"items":[{"id":1,"content":"It’ was amazing feeling for may belly!","rate":4,"source":"Booking","author":{"name":"Mark Johnson","thumbnail":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/avatars/avatar02.jpg"}}},{"id":2,"content":"Great food and great atmosphere!","rate":5,"source":"Facebook","author":{"name":"Kate Hudson","thumbnail":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/avatars/avatar01.jpg"}}},{"id":3,"content":"Best food in da city!","rate":4,"source":"Booking","author":{"name":"John Johnson","thumbnail":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/avatars/avatar02.jpg"}}}],"carousel":false}}]}';
        break;

      case "services":
        echo '{"pageTitle":{"title":"Our Services","theme":"light","tagline":"Some informations about our restaurant"},"sections":[{"name":"Double","props":{"content":{"title":"Weddings","description":"Praesent scelerisque mi ac bibendum tristique. Cras in magna a quam molestie tincidunt nec vitae diam.","link":{"title":"Get a quote","slug":"/contact"}},"image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/service-weddings.jpg"},"reverse":false}},{"name":"Double","props":{"content":{"title":"Engagement Parties","description":"Praesent scelerisque mi ac bibendum tristique. Cras in magna a quam molestie tincidunt nec vitae diam.","link":{"title":"Get a quote","slug":"/contact"}},"image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/service-engagement.jpg"},"reverse":true}},{"name":"Double","props":{"content":{"title":"Birthday Parties","description":"Praesent scelerisque mi ac bibendum tristique. Cras in magna a quam molestie tincidunt nec vitae diam.","link":{"title":"Get a quote","slug":"/contact"}},"image":{"title":"Image Title","url":"https://assets.suelo.pl/soup/img/photos/service-birthday.jpg"},"reverse":false}}]}';
        break;

      case "terms":
        echo '{"pageTitle":{"title":"Terms & Conditions","theme":"light","tagline":"Some informations about our restaurant"},"chapters":[{"id":1,"name":"General","content":"<p class=\'lead\'>In hac habitasse platea dictumst. Curabitur vestibulum nulla non efficitur pulvinar. Nulla pulvinar est in eros vehicula lacinia. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p><p>Aenean rutrum dapibus molestie. Praesent eu nibh a magna maximus semper. Aliquam odio nulla, ornare et imperdiet dignissim, rutrum et felis. Nulla in eros et magna vehicula tincidunt quis eget orci. Quisque in est ac augue bibendum fringilla quis a odio. Donec mollis consectetur commodo. Cras interdum ac nibh id sodales. Phasellus egestas varius pulvinar. Aliquam sit amet felis sit amet purus vestibulum dictum in sit amet justo. Nam blandit arcu porttitor, faucibus purus a, vehicula odio. Pellentesque semper, odio sed commodo iaculis, augue mauris scelerisque mi, in mattis elit justo ut erat. Nulla cursus turpis sollicitudin, eleifend lectus non, tristique nunc. Fusce vitae varius tellus.</p>"},{"id":2,"name":"Delivery","content":"<p class=\'lead\'>In hac habitasse platea dictumst. Curabitur vestibulum nulla non efficitur pulvinar. Nulla pulvinar est in eros vehicula lacinia. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p><p>Aenean rutrum dapibus molestie. Praesent eu nibh a magna maximus semper. Aliquam odio nulla, ornare et imperdiet dignissim, rutrum et felis. Nulla in eros et magna vehicula tincidunt quis eget orci. Quisque in est ac augue bibendum fringilla quis a odio. Donec mollis consectetur commodo. Cras interdum ac nibh id sodales. Phasellus egestas varius pulvinar. Aliquam sit amet felis sit amet purus vestibulum dictum in sit amet justo. Nam blandit arcu porttitor, faucibus purus a, vehicula odio. Pellentesque semper, odio sed commodo iaculis, augue mauris scelerisque mi, in mattis elit justo ut erat. Nulla cursus turpis sollicitudin, eleifend lectus non, tristique nunc. Fusce vitae varius tellus.</p>"},{"id":3,"name":"Payments","content":"<p class=\'lead\'>In hac habitasse platea dictumst. Curabitur vestibulum nulla non efficitur pulvinar. Nulla pulvinar est in eros vehicula lacinia. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p><p>Aenean rutrum dapibus molestie. Praesent eu nibh a magna maximus semper. Aliquam odio nulla, ornare et imperdiet dignissim, rutrum et felis. Nulla in eros et magna vehicula tincidunt quis eget orci. Quisque in est ac augue bibendum fringilla quis a odio. Donec mollis consectetur commodo. Cras interdum ac nibh id sodales. Phasellus egestas varius pulvinar. Aliquam sit amet felis sit amet purus vestibulum dictum in sit amet justo. Nam blandit arcu porttitor, faucibus purus a, vehicula odio. Pellentesque semper, odio sed commodo iaculis, augue mauris scelerisque mi, in mattis elit justo ut erat. Nulla cursus turpis sollicitudin, eleifend lectus non, tristique nunc. Fusce vitae varius tellus.</p>"}]}';
        break;

        // default:
        //   echo '{
        //     "pageTitle": null,
        //     "sections": [
        //       {
        //         "name": "HeroCentered",
        //         "props": {
        //           "media": {
        //             "type": "video",
        //             "source": {
        //               "title": "Video Title",
        //               "url": "https://assets.suelo.pl/soup/video/video_1.mp4"
        //             }
        //           },
        //           "logo": "https://assets.suelo.pl/soup/img/logo-light-green.svg",
        //           "title": "Meilleur Sushi & Poke",
        //           "tagline": "and use it with your next order!",
        //           "link": {
        //             "title": "Check our menu",
        //             "slug": "/menu-grid-navigation"
        //           }
        //         }
        //       },
        //       {
        //         "name": "ImageEdge",
        //         "props": {
        //           "content": {
        //             "rate": 4,
        //             "title": "The best food in London!",
        //             "description": "Donec a eros metus. Vivamus volutpat leo dictum risus ullamcorper condimentum. Cras sollicitudin varius condimentum. Praesent a dolor sem....",
        //             "reviews": [
        //               {
        //                 "content": "It’ was amazing feeling for may belly!",
        //                 "rate": 4,
        //                 "source": "Booking",
        //                 "author": {
        //                   "name": "Mark Johnson",
        //                   "thumbnail": {
        //                     "title": "Image Title",
        //                     "url": "https://assets.suelo.pl/soup/img/avatars/avatar02.jpg"
        //                   }
        //                 }
        //               },
        //               {
        //                 "content": "Great food and great atmosphere!",
        //                 "rate": 5,
        //                 "source": "Facebook",
        //                 "author": {
        //                   "name": "Kate Hudson",
        //                   "thumbnail": {
        //                     "title": "Image Title",
        //                     "url": "https://assets.suelo.pl/soup/img/avatars/avatar01.jpg"
        //                   }
        //                 }
        //               }
        //             ]
        //           },
        //           "image": {
        //             "title": "Image Title",
        //             "url": "' . $this->api . '/uploads/images/review.jpeg"
        //           },
        //           "reverse": true
        //         }
        //       },
        //       {
        //         "name": "Categories",
        //         "props": {
        //           "title": null
        //         }
        //       },
        //       {
        //         "name": "Offers",
        //         "props": {
        //           "title": "Special Offers",
        //           "theme": "light"
        //         }
        //       },
        //       {
        //         "name": "Features",
        //         "props": {
        //           "theme": "dark",
        //           "bg": "dark",
        //           "items": [
        //             {
        //               "title": "Pick a dish",
        //               "description": "Vivamus volutpat leo dictum risus ullamcorper condimentum.",
        //               "icon": "shopping-cart"
        //             },
        //             {
        //               "title": "Make a payment",
        //               "description": "Vivamus volutpat leo dictum risus ullamcorper condimentum.",
        //               "icon": "wallet"
        //             },
        //             {
        //               "title": "Recieve your food!",
        //               "description": "Vivamus volutpat leo dictum risus ullamcorper condimentum.",
        //               "icon": "package"
        //             }
        //           ],
        //           "extend": "left"
        //         }
        //       },
        //       {
        //         "name": "LatestPosts",
        //         "props": {
        //           "title": "LatestPosts",
        //           "theme": "light"
        //         }
        //       },
        //       {
        //         "name": "Cta",
        //         "props": {
        //           "theme": "dark",
        //           "bg": "dark",
        //           "image": {
        //             "title": "Image Title",
        //             "url": "' . $this->api . '/uploads/images/cta.jpeg"
        //           },
        //           "title": "Check our promo video!",
        //           "tagline": "Book a table even right now or make an online order!",
        //           "link": {
        //             "title": "Order now!",
        //             "slug": "menu-grid-navigation"
        //           }
        //         }
        //       }
        //     ],
        //     "settings": {
        //       "headerTransparent": true,
        //       "hideHeaderLogo": false
        //     }
        //   }';
        //   break;
    }
  }
}
