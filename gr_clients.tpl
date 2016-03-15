<div style="text-align: center">
    <span class="gr_full_widget_design"></span>
    <span class="gr_container_full_widget_design">
        <img src="js/bootstrap-modal/img/loader.gif"/>
    </span>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="https://www.geniusreferrals.com/bundles/portal/js/geniusreferrals-api-client_1.0.6.js"></script>
<script type="text/javascript" src="https://www.geniusreferrals.com/bundles/portal/js/geniusreferrals-tool-box_1.0.9.js"></script>

<script type="text/javascript">
    var toolbox = new grToolbox();

    {if $logged}
        toolbox.loadTemplate({
            "grUsername" : "{$GR_USERNAME}",
            "grTemplateSlug": "{$GR_TEMPLATENOTAUTHSLUG}",
            "grCustomerEmail": "{$grCustomerEmail}",
            "grCustomerName": "{$grCustomerName}",
            "grCustomerLastname": "{$grCustomerLastname}",
            "grCustomerCurrencyCode": "{$grCustomerCurrencyCode}"
        });
    {else}
        toolbox.loadTemplate({
            "grUsername" : "{$GR_USERNAME}",
            "grTemplateSlug": "{$GR_TEMPLATESLUG}"
        });
    {/if}
    
</script>
