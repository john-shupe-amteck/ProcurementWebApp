$(function() {
  // hides all PO tables on load
  $(".sub-table").each(function(e) {
    $(this).hide();
  })

  // Colors expired equipment rentals red
  $(".finish-date").each(function (index, element) {
    var a = new Date($(this).text());
    var b = new Date();
    
    if (a < b) {
      $(this).css("color", "red");
    }  
  });

  // toggles visibility of PO tables
  $(".main-info").click(function (e) { 
    $(this).next().toggle(200);    
  });
})