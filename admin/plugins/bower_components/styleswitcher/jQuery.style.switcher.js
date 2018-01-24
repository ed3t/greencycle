// Theme color settings
$(document).ready(function(){
    function store(name, val) {
        if (typeof (Storage) !== "undefined") {
          localStorage.setItem(name, val);
        } else {
          window.alert('Please use a modern browser to properly view this template!');
        }
    }

    $("*[theme]").click(function(e){
        e.preventDefault();
        var currentStyle = $(this).attr('theme');
        store('theme', currentStyle);
        $('#theme').attr({href: 'assets/css/colors/'+currentStyle+'.css'})
    });

    var currentTheme = get('theme');
    if(currentTheme)
    {
      $('#theme').attr({href: 'assets/css/colors/'+currentTheme+'.css'});
    }
    // color selector
    $('#themecolors').on('click', 'a', function(){
        $('#themecolors li a').removeClass('working');
        $(this).addClass('working')
    });

    $("*[maintheme]").click(function(e){
        e.preventDefault();
        var currentStyle = $(this).attr('maintheme');
        store('maintheme', currentStyle);
        $('#maintheme').attr({href: 'assets/css/'+currentStyle+'.css'})
    });

    var currentTheme = get('maintheme');
    if(currentTheme)
    {
        $('#maintheme').attr({href: 'assets/css/'+currentTheme+'.css'});
    }
    // color selector
    $('#mainthemecolors').on('click', 'a', function(){
        $('#mainthemecolors li a').removeClass('working');
        $(this).addClass('working')
    });

});

$(document).ready(function(){
    $("*[theme]").click(function(e){
      e.preventDefault();
        var currentStyle = $(this).attr('theme');
        store('theme', currentStyle);
        $('#theme').attr({href: 'assets/css/colors/'+currentStyle+'.css'})
    });

    var currentTheme = get('theme');
    if(currentTheme)
    {
      $('#theme').attr({href: 'assets/css/colors/'+currentTheme+'.css'});
    }
    // color selector
    $('#themecolors').on('click', 'a', function(){
        $('#themecolors li a').removeClass('working');
        $(this).addClass('working')
    });

    $("*[maintheme]").click(function(e){
        e.preventDefault();
        var currentStyle = $(this).attr('maintheme');
        store('maintheme', currentStyle);
        $('#maintheme').attr({href: 'css/'+currentStyle+'.css'})
    });

    var currentTheme = get('maintheme');
    if(currentTheme)
    {
        $('#maintheme').attr({href: 'assets/css/'+currentTheme+'.css'});
    }
    // color selector
    $('#mainthemecolors').on('click', 'a', function(){
        $('#mainthemecolors li a').removeClass('working');
        $(this).addClass('working')
    });
});


function get(name) {

}
