$(document).ready(function () {
  $("#navbarToggleExternalMenu").height($(window).height());

  $(".toggler-external-menu").click(() => {
    $("#navbarToggleExternalMenu").toggleClass("show");
    $(".tab-pane").removeClass('show').removeClass('active');
    $("#nav-right-menu").addClass('show').addClass('active');
  });

  $(".link-open-signin").click(()=>{
    $(".tab-pane").removeClass('show').removeClass('active');
    $("#nav-signin").addClass('show').addClass('active');
  })

  $(".link-open-register").click(()=>{
    $(".tab-pane").removeClass('show').removeClass('active');
    $("#nav-register").addClass('show').addClass('active');
  })

  // To style all selects
  // $('.selectpicker').selectpicker();

  const password = document.querySelector('#signin_password');

  $('.togglePassword').click( function (e) {
    // toggle the type attribute
    const type = $(this).parents('.form-group').find('.input-password').attr('type') === 'password' ? 'text' : 'password';
    $(this).parents('.form-group').find('.input-password').attr('type', type);
    // toggle the eye slash icon
    if(type == 'text')
      $(this).attr('class','bi bi-eye-slash togglePassword')
    else 
      $(this).attr('class','bi bi-eye togglePassword')

});

});
