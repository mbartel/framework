$(function() {
  $('#garagentor').click(function(e) {
    $.ajax({
      url: '/',
      type: 'POST',
      data: {
        action: 'garagentor'
      }
    });
  });
  $('#test').click(function(e) {
    $.ajax({
      url: '/',
      type: 'POST',
      data: {
        action: 'test'
      }
    });
  });
});