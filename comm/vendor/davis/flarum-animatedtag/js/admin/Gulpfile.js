var flarum = require('flarum-gulp');

flarum({
  modules: {
    'davis/animatedtag': [
      'src/**/*.js'
    ]
  }
});