$(document).ready(() => {
    InitLeftMenuAim();
  })
    
  InitLeftMenuAim = function () {
    let $block = $('.menu.dropdown:not(.aim-init)');
    let $isBlockHover = $block.find('.full.has-child.v_hover').length
    if ($isBlockHover) {
      $block.addClass("aim-init");
      BX.loadExt('aspro_menu_aim').then(() => {
        $block.menuAim({
          tolerance: 75,
          rowSelector: "> .full.v_hover",
          activate: function (a) {
            $(a).find(".dropdown").show();
          },
          deactivate: function (a) {
            $(a).find(".dropdown").hide();
          },
          exitMenu: function (a) {
            $(a).find(".dropdown").hide();
            return true;
          },
        });
      });
    }
  }
  