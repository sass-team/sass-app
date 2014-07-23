$(function () {

  $('.bs-docs-external-link')
    .addClass ('btn btn- btn-warning')
    .css ({ 'margin-top': '.65em' })
    .prop ('target', '_blank')
    .append ('&nbsp;&nbsp;<i class="icon-external-link"></i>');

  var $window = $(window);
  var $body   = $(document.body);

  $('.bs-docs-section').eq (0).addClass ('first');

  var source = $("#bs-sidenav-tmpl").html ();
  var template = Handlebars.compile(source);
  data = getNavObject ();
  $('.bs-sidenav').html (template (data)).find ('li').eq (0).addClass ('active');
  var navHeight = $('.navbar').outerHeight (true) + 10;

  $body.scrollspy({
    target: '.bs-sidebar',
    offset: navHeight
  });

  $window.on('load', function () {
    $body.scrollspy('refresh')
  });

  $('.bs-docs-container [href=#]').click(function (e) {
    e.preventDefault();
  });

  // back to top
  setTimeout(function () {
    var $sideBar = $('.bs-sidebar')

    $sideBar.affix({
      offset: {
        top: function () {
          var offsetTop      = $sideBar.offset().top
          var sideBarMargin  = parseInt($sideBar.children(0).css('margin-top'), 10)
          var navOuterHeight = $('.bs-docs-nav').height()

          return (this.top = offsetTop - navOuterHeight - sideBarMargin)
        }
      , bottom: function () {
          return (this.bottom = $('.bs-footer').outerHeight(true))
        }
      }
    })
  }, 100);

  setTimeout(function () {
    $('.bs-top').affix()
  }, 100);

  $('pre.snip-html').snippet ('html', { style: 'bright' });
  $('pre.snip-js').snippet ('javascript', { style: 'bright' });

  initBackToTop ();
    
});

function initBackToTop () {
    var backToTop = $('<a>', { id: 'back-to-top', href: '#top' });
    var icon = $('<i>', { class: 'icon-chevron-up' });

    backToTop.appendTo ('body');
    icon.appendTo (backToTop);
    
      backToTop.hide();

      $(window).scroll(function () {
          if ($(this).scrollTop() > 150) {
              backToTop.fadeIn ();
          } else {
              backToTop.fadeOut ();
          }
      });

      backToTop.click (function (e) {
        e.preventDefault ();

          $('body, html').animate({
              scrollTop: 0
          }, 600);
      });
  }

function getNavObject () {
  var nav = {};
  nav.sections = new Array ();

  $('.bs-docs-section').each (function (i) {
    var $subHeaders = $(this).find ('.bs-docs-section-sub')
        , header = new Array ()
        , subs = new Array ();

    header.id = '#' + $(this).prop ('id');
    header.title = $(this).data ('title');    
    header.subsCount = $subHeaders.length;    

    $subHeaders.each (function (i) {
      subs.push ({ id: '#' + $(this).prop ('id'), title: $(this).data ('title') });
    });

    header.subs = subs;
    nav.sections.push (header);

  });


  return nav;
}