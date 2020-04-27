# os-newsletter-sync-mailchimp
WP Plugin sync with mychimp and pods

## Installation
Install in your WP

Insert your api key and list in admin menu

## Usage

##Create pods options grupo with this
options: recaptcha_site_key and recaptcha_secret_key

Insert in your theme 
Form newsletter

```html
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo getPodOptions('options','recaptcha_site_key') ?>"></script>
<div class="newsletter__wrapper" style='background-image:  url("wp-content/themes/sahara/images/newsletter2.jpeg")'>
    <h3 class="title-block title-block--light title-block--tiny"> <?php _e( 'Subscribete a nuestra newsletter', 'twentysixteen' ); ?></h3>
    <section class="newsletter__content">
        <form id="newsletter" action="#" method="post">
            <input type="hidden" name="action" value="newsletter__register"/>
            <div class="newsletter-form__content">
                <div>
                    <input name="email" type="email" class="newsletter-form__input" value="" placeholder="your@mail.com" required>
                </div>
                <div class="input-btn-ok">
                        <button type="submit"> <?php _e( 'OK', 'sahara' ); ?> </button>
                </div>
            </div>

            <div class="input-wrapper">
                <div class="squaredOne">
                    <input type="checkbox" value="accept" id="squaredOne" name="accept" required />
                    <label for="squaredOne"></label>
                </div>
                <div class="newsletter-accept">
                    <a class="link--light" href="" target="_blank">
                        <?php _e( 'Acepto la política de privacidad', 'twentysixteen' ); ?>
                    </a>
                </div>
            </div>
                <div class="newsletter__subtext">
                    <?php _e( 'Puede darse de baja en cualquier momento. Para ello, consulte nuestra información de contacto en el aviso legal.', 'twentysixteen' ); ?>
                </div>
        </form>

        <div class="newsletter__message__wrapper js-newsletter__wrapper">
            <span class="js-newsletter__message newsletter__message__span"></span>
        </div>
    </section>
</div>

<script>
 $("#newsletter").submit(function(event){
    event.preventDefault();
    var post_url = '/wp-admin/admin-ajax.php';
    var request_method = $(this).attr("method");
    var form = $(this);
    var site_key = $('#recaptcha-site').val();
    
    grecaptcha.ready(function() {
      grecaptcha.execute(site_key, {action: 'create_newsletter'}).then(function(token) {
        // add token to form
        $('#newsletter_form').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');

        var form_data = form.serialize();
        form_data = form_data+'&token='+token;
        ;
        $.ajax({
          url: post_url,
          type: request_method,
          data: form_data,
          captcha: token,
          mierdaVIda: 'test'
        }).done(function(response){ //
          $('.js-newsletter__message').html(response.message);
          $('.js-newsletter__message').removeClass('error');
          $('.js-newsletter__message').removeClass('success');
          if(response.status === 'ok') {
            $('.js-newsletter__message').addClass('success');
          } else {
            $('.js-newsletter__message').addClass('error');
          }
        });

      });;
    });
  });
</script>

```
## Usage With pods
Create Pods	Advanced Content Type 

with email type email and created type date time

Active en Admin Api newsletter de option to save in pods


## Contributing
1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## Credits
Oscar Sanchez oscarsan1986@gmail.com
]]