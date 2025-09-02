{*
* WhatsApp Widget - Custom Hook Usage Example
* 
* Bu dosya, özel hook'u tema dosyalarınızda nasıl kullanacağınızı gösterir.
* 
* Kullanım:
* 1. Bu kodu istediğiniz tema dosyasına (örn: header.tpl, footer.tpl, product.tpl) ekleyin
* 2. Admin panelinden "Display Hook" ayarını "Custom Position" olarak seçin
* 3. Widget seçtiğiniz konumda görünecektir
*}

{* Örnek 1: Header'da kullanım *}
{if $page.page_name == 'index' || $page.page_name == 'product'}
    <div class="whatsapp-custom-position">
        {hook h='displayCustomWhatsAppWidget'}
    </div>
{/if}

{* Örnek 2: Ürün sayfasında özel konumda kullanım *}
{if $page.page_name == 'product'}
    <div class="product-whatsapp-widget">
        {hook h='displayCustomWhatsAppWidget'}
    </div>
{/if}

{* Örnek 3: Sidebar'da kullanım *}
<div class="sidebar-whatsapp">
    {hook h='displayCustomWhatsAppWidget'}
</div>

{* Örnek 4: Şartlı kullanım *}
{if $customer.is_logged}
    <div class="logged-user-whatsapp">
        {hook h='displayCustomWhatsAppWidget'}
    </div>
{/if}

{* 
* CSS Örnekleri:
* 
* .whatsapp-custom-position {
*     position: relative;
*     margin: 20px 0;
* }
* 
* .product-whatsapp-widget {
*     float: right;
*     margin-left: 20px;
* }
* 
* .sidebar-whatsapp {
*     position: sticky;
*     top: 20px;
* }
*}