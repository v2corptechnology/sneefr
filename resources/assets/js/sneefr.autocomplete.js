jQuery(document).ready(function($) {

  /**
   * Algolia Search keys setup
   * @type {[type]}
   */
  var client = algoliasearch('QRDJYDN0HS', 'db10a3ff325a8be41913f31f6fef87d6');

  /**
   * base Url for images
   * @type String
   */
  var imgBaseUrl = $('#auto-complete-image-base-url').val();
  var imgShopBaseUrl = $('#auto-complete-shop-image-base-url').val();
  var baseUrl = $('#base-url').val();
  /**
   * Current Env 
   * @type String
   */
  var env = $('#env').val();

  /**
   * Initialisation of indes 
   */
  var ads   = client.initIndex('ads_' + env);
  var shops = client.initIndex('shops_' + env);
 // var users = client.initIndex('users_' + env);
 // var places = client.initIndex('place_names_' + env);

  /**
   * Template for Ad in Auto-complete Dropdown (Mustache templating by Hogan.js) 
   * @type String
   */
  var templateAd = Hogan.compile('<div class="auto-complete-item ad">' +
    '<a href="{{ url }}">'+
    '<div class="name">'+
    '<img class="image" src="{{ imageUrl }}">'+
    '{{{ title }}}'+
    '</div>'+
    '</a>'+
    '</div>');

    /**
   * Template for Shop in Auto-complete Dropdown (Mustache templating by Hogan.js) 
   * @type String
   */

  var templateShop = Hogan.compile('<div class="auto-complete-item shop">' +
    '<a href="{{ url }}">'+
    '<div class="name">'+
    '<img class="image" src="{{ imageUrl }}">'+
    '{{{ data.name }}}'+
    '</div>'+
    '</a>'+
    '</div>');

  /**
   * Template for User in Auto-complete Dropdown (Mustache templating by Hogan.js) 
   * @type String
   */
  var templateUser = Hogan.compile('<div class="auto-complete-item search-user-item">' +
    '<a href="{{ url }}">'+
    '<div class="name">'+
    '<img class="image round" src="{{ imageUrl }}">'+
    '{{ given_name }} {{ surname }}'+
    '</div>'+
    '</a>'+
    '</div>');

    /**
   * Template for Place in Auto-complete Dropdown (Mustache templating by Hogan.js) 
   * @type String
   */
  var templatePlace = Hogan.compile('<div class="auto-complete-item place">' +
    '<a href="#">'+
    '<div class="name">{{{ formatted_address }}}</div>'+
    '</a>'+
    '</div>');

  /**
   * Object init to search in Algolia
   * @type {Array}
   */
  var toSearch = [
    {
      source: autocomplete.sources.hits(ads, {hitsPerPage: 3}),
      displayKey: 'title',
      templates: {
        header: '<div class="category">Ads</div>',
        suggestion: function(hit) {
          // add image base url to hit object to be render
          hit['imageUrl'] = imgBaseUrl + hit.id + '/' + hit.images[0];
          hit['url'] = baseUrl + '/ad/' + hit['id'] ;
          // render the hit using Hogan.js
          return templateAd.render(hit);
        }
      }
    },
    {
      source: autocomplete.sources.hits(shops, {hitsPerPage: 2}),
      displayKey: 'name',
      templates: {
        header: '<div class="category">Shops</div>',
        suggestion: function(hit) {
          // render the hit using Hogan.js
          // add image base url to hit object to be render
          hit['imageUrl'] = imgShopBaseUrl + hit['slug'] + '/' + hit['data']['logo'] ;
          hit['url'] = baseUrl +'/shops/' + hit['slug'];
          return templateShop.render(hit);
        }
      }
    }/*,
    {
      source: autocomplete.sources.hits(users, {hitsPerPage: 2}),
      displayKey: 'name',
      templates: {
        header: '<div class="category">Users</div>',
        suggestion: function(hit) {
          // render the hit using Hogan.js
          hit['url'] = baseUrl +'/profiles/' + hit['user_hash'] ;
          hit['imageUrl'] = 'https://graph.facebook.com/'+ hit['facebook_id'] +'/picture'
          return templateUser.render(hit);
        }
      }
    },
    {
      source: autocomplete.sources.hits(places, {hitsPerPage: 2}),
      displayKey: 'name',
      templates: {
        header: '<div class="category">Places</div>',
        suggestion: function(hit) {
          // render the hit using Hogan.js
          return templatePlace.render(hit);
        }
      }
    }*/

  ];

  // autocomplete.js initialization for navbar, mobile and home Search
  autocomplete('.js-add-autocompletion', {hint: false}, toSearch).on('autocomplete:selected', function(event, suggestion, dataset) {
    redirectToUrl(suggestion);
  });

  /**
   * when autocomplete selected, redirect if url is set                  
   */
  function redirectToUrl(suggestion){
    if(suggestion['url'] != undefined ){
      window.location.href = suggestion['url'];
    }
  }

  /**
   * add z-index to dropdown
   */
  $('.aa-dropdown-menu').css("z-index", "200");
});
