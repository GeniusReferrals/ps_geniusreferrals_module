<script>
$(function(){
    var a = '<div id="responsive" class="modal" tabindex="-1" data-width="1064"  style="display: none;">';
    a += '  <div class="modal-body">';
    a += '  <span class="modal-header">';
    a += '    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
    a += '  </span>';
    a += '    <div class="row">';
    a += '      <div class="col-md-12" style="text-align: center">';
    a += '        <span class="gr_pos_design"> </span>';
    a += '        <span class="gr_container_pos_design"> <img src="'+"{$urlImagen}" +'"/> </span>';
    a += '      </div>';
    a += '    </div>';
    a += '  </div>';
    a += '  <div class="modal-footer">';
    a += '    <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>';
    a += '  </div>';
    a += '</div>';

console.log('hello alain'); 

var toolbox = new grToolbox();

toolbox.loadTemplate({
    "grUsername" : "{$grUsername}",
    "grTemplateSlug": "{$grTemplateSlugConfirmPage}",
    "grCustomerEmail": "{$grCustomerEmail}",
    "grCustomerName": "{$grCustomerName}",
    "grCustomerLastname": "{$grCustomerLastname}",
    "grCustomerCurrencyCode": "{$grCustomerCurrencyCode}"});

    var highest = -999;
    $("*").each(function() {
        var current = parseInt($(this).css("z-index"), 10);
        if(current && highest < current) highest = current;
    });

    $("body").prepend(a);
    $("#responsive").modal('show');
    $("#responsive").closest(".modal-scrollable").css("zIndex",highest+1);

});
</script>