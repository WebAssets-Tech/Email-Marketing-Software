<form role="form" action="{{ route('payment.pay') }}" method="post" class="validation p-3" data-cc-on-file="false"
    id="payment-form">
    @csrf

    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
    <input type="hidden" name="method" value="stripe">
    <input type="hidden" name="email" :value="email">
    <input type="hidden" name="name" :value="name">
    <input type="hidden" name="source">


    <div class="">
        <h6 id="heading" class="text-center package-title text-uppercase font-weight-bold">@translate(Enter Card Details)
        </h6>
        <div class="mb-3">
            <label for="name">Name</label>
            <input type="name" x-model="name" class="form-control w-100 @error('name') is-invalid @enderror"
                name="name" disabled placeholder="John Doe">
        </div>
        <div class="mb-3">
            <label for="email">Email</label>
            <input type="email" x-model="email" class="form-control w-100 @error('email') is-invalid @enderror"
                name="email" disabled placeholder="xyz@mail.com">
        </div>
        <div class="mb-3">
            <label for="email">Card No.</label>
            <input type="text" autocomplete='off' class="card-num form-control w-100" name="cardno" id="cr_no"
                placeholder="0000 0000 0000 0000" minlength="19" maxlength="19">
        </div>
        <div class="row mb-10">

            <div class="mb-3 col-4">
                <label for="email">Expiry Month</label>
                <input type="text" name="expm" class="card-expiry-month form-control w-100" placeholder="MM"
                    minlength="1" maxlength="2">
            </div>
            <div class="mb-3 col-4">
                <label for="email">Expiry Year</label>
                <input type="text" name="expy" class="card-expiry-year  form-control w-100" placeholder="YY"
                    minlength="2" maxlength="4">
            </div>
            <div class="mb-3 col-4">
                <label for="email">CVC</label>
                <input type="password" name="expm" name="cvcpwd" placeholder="&#9679;&#9679;&#9679;"
                    class="placeicon card-cvc  form-control w-100" autocomplete='off' minlength="3" maxlength="3">
            </div>
            <div class="col-12">
                <button type="button" class="btn btn-success col-12 w-full" id="submit-btn">@translate(Pay Now)
                    ({{ formatPrice($plan->price) }})</button>
            </div>
        </div>
    </div>

</form>
@push('scripts')
    <script src="{{ filePath('assets/stripe/jquery.js') }}"></script>
    <script src="{{ filePath('assets/stripe/script.js') }}"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script>
        $(document).ready(function() {
            let form = $('#payment-form')
            let inputVal = [
                'input[type=email]',
                'input[type=password]',
                'input[type=text]',
                'input[type=file]',
                'textarea'
            ].join(', ')
            let $inputs = form.find('.required').find(inputVal)
            let $errorStatus = form.find('div.error')

            form.on('submit', function(e) {
                e.preventDefault()
                let valid = true
                $errorStatus.addClass('hide');
                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorStatus.removeClass('hide');
                        e.preventDefault();
                    }
                });

                let pubKey = "{{ env('STRIPE_KEY') }}";
                Stripe.setPublishableKey(pubKey);
                Stripe.createToken({
                    number: $('.card-num').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                }, stripeHandleResponse);

            })
            $('#submit-btn').on('click', (e) => {
                form.submit()
            })

            function stripeHandleResponse(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);
                    console.log(response);
                } else {
                    var token = response['id'];
                    form.find('input[type=text]').empty();
                    $('[name="source"]').val(token);
                    form.unbind('submit').submit()
                }
            }
        });
    </script>
@endpush
