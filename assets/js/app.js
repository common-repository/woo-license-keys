
(function($){$.fn.lk_product_manager=function()
{var self=this;var $select_type=undefined;var $expire=undefined;self.ready=function()
{self.$select_type=$('#product-type');var $form=$('#license_key_product_data');if($form.length===0||window.license_keys.types===undefined)
return;self.$expire=$form.find('input[name="_expire"]');if(self.$expire.length===0)
return;self.enable_general();self.enable_pricing();self.enable_virtual();self.enable_downloadable();self.enable_expire();self.$expire.on('click',self.enable_expire);};self.enable_pricing=function()
{for(var index in window.license_keys.types){$('.options_group.pricing').addClass(window.license_keys.types[index].show_if).show();}};self.enable_virtual=function()
{for(var index in window.license_keys.simple_types){$('label[for="_virtual"]').addClass(window.license_keys.simple_types[index].show_if).show();}};self.enable_downloadable=function()
{for(var index in window.license_keys.simple_types){$('label[for="_downloadable"]').addClass(window.license_keys.simple_types[index].show_if).show();}};self.enable_general=function()
{if(window.license_keys.types.find(function(type){return type===self.$select_type.val()})!==undefined){$('li.general_options').show();}};self.enable_expire=function()
{if(self.$expire.is(':checked')){$('._expire_interval_field').show();$('._expire_value_field').show();$('._expire_notifications_group').show();}else{$('._expire_interval_field').hide();$('._expire_value_field').hide();$('._expire_notifications_group').hide();}};$(document).ready(self.ready);return self;}
if(window.license_keys)
window.license_keys.product_manager=$(document).lk_product_manager();})(jQuery);if(jQuery('.clipboard-copy').length){var clipboard=new ClipboardJS('.clipboard-copy');}