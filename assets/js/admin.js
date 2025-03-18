jQuery(document).ready(function($) {
    $('.wpicp-tabs .nav-tab').on('click', function(e) {
        e.preventDefault();
        $('.wpicp-tabs .nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.wpicp-tabs .tab-content').hide();
        $($(this).attr('href')).show();
    });
});