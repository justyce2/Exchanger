<form class="exchange-form" method="POST" action="{{ route('user.exchange.start') }}" id="exchange-form">
    @csrf
    <div class="form-group w-100">
        <label>@lang('You Send')</label>
        <input type="number" step="any" name="sending_amount" id="sending_amount" class="form--control" placeholder="0.00" value="{{ old('sending_amount') }}" required>
        <select class="select-bar form--control" name="sending_currency" id="send">
            <option value="" selected disabled>@lang('Select Currency')</option>
            @foreach ($sellCurrencies as $sellCurrency)
                <option data-image="{{ getImage(getFilePath('currency') . '/' . @$sellCurrency->image, getFileSize('currency')) }}" data-min="{{ getAmount($sellCurrency->minimum_limit_for_buy) }}" data-max="{{ getAmount($sellCurrency->maximum_limit_for_buy) }}" data-buy="{{ getAmount($sellCurrency->buy_at) }}" data-currency="{{ @$sellCurrency->cur_sym }}" value="{{ $sellCurrency->id }}" @selected(old('sending_currency') == $sellCurrency->id)>
                    {{ __($sellCurrency->name) }} - {{ __($sellCurrency->cur_sym) }}
                </option>
            @endforeach
        </select>
        <span class="limit-alert d-none" id="currency-limit"></span>
    </div>

    <div class="form-group receiveData w-100">
        <label for="receive">@lang('You Get')</label>
        <input type="number" step="any" name="receiving_amount" class="form--control" id="receiving_amount" placeholder="@lang('0.00')" value="{{ old('receiving_amount') }}">
        <select class="select-bar form--control" name="receiving_currency" id="receive">
            <option value="" selected disabled>@lang('Select Currency')</option>
            @foreach ($buyCurrencies as $buyCurrency)
                <option data-image="{{ getImage(getFilePath('currency') . '/' . @$buyCurrency->image, getFileSize('currency')) }}" data-sell="{{ getAmount($buyCurrency->sell_at) }}" data-currency="{{ @$buyCurrency->cur_sym }}" value="{{ $buyCurrency->id }}" data-min="{{ getAmount($buyCurrency->minimum_limit_for_sell) }}" data-max="{{ getAmount($buyCurrency->maximum_limit_for_sell) }}" data-reserve="{{ getAmount($buyCurrency->reserve) }}" @selected(old('receiving_currency') == $buyCurrency->id)>
                    {{ __($buyCurrency->name) }} - {{ __($buyCurrency->cur_sym) }}
                </option>
            @endforeach
        </select>
        <span class="limit-alert d-none" id="currency-limit-received"></span>
    </div>
    <div class="form-group w-100">
        <button type="submit" class="btn--base btn">@lang('Exchange')</button>
    </div>
</form>

@push('style')
    <style>
        .limit-alert {
            background: #ff5200;
            padding: 5px 10px;
            border-radius: 0px 0px;
            display: block;
            width: 100%;
            color: #fff !important;
        }
    </style>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            let sendId, sendMinAmount, sendMaxAmount, sendAmount, sendCurrency, sendCurrencyBuyRate;
            let receivedId, receivedAmount, receivedCurrency, receiveCurrencySellRate;

            const EXCHANGE_FORM = $('#exchange-form');

            @if (old('sending_currency'))
                sendAmount = "{{ old('sending_amount') }}";
                sendAmount = parseFloat(sendAmount);
                setTimeout(() => {
                    $('#send').trigger('change');
                });
            @endif

            @if (old('receiving_currency'))
                setTimeout(() => {
                    $('#receive').trigger('change');
                });
            @endif

            EXCHANGE_FORM.on('change', '#send', function(e) {
                sendId = parseInt($(this).val());
                sendMinAmount = parseFloat($(this).find(':selected').data('min'));
                sendMaxAmount = parseFloat($(this).find(':selected').data('max'));
                sendCurrency = $(this).find(':selected').data('currency');
                sendCurrencyBuyRate = parseFloat($(this).find(':selected').data('buy'));

                $("#currency-limit").removeClass('d-none').text(`Limit: ${sendMinAmount.toFixed(2)} - ${sendMaxAmount.toFixed(2)} ${sendCurrency}`);

                validation()
                calculationReceivedAmount();
            });

            EXCHANGE_FORM.on('change', '#receive', function(e) {
                receivedId = parseInt($(this).val());
                receiveCurrencySellRate = parseFloat($(this).find(':selected').data('sell'));
                receivedCurrency = $(this).find(':selected').data('currency');

                let minAmount = parseFloat($(this).find(':selected').data('min'));
                let maxAmount = parseFloat($(this).find(':selected').data('max'));
                let reserveAmount = parseFloat($(this).find(':selected').data('reserve'))

                $("#currency-limit-received").removeClass('d-none').text(`Limit: ${minAmount.toFixed(2)} - ${maxAmount.toFixed(2)} ${receivedCurrency} | Reserve ${reserveAmount.toFixed(2)} ${receivedCurrency}`);

                if (!sendId) {
                    return false;
                }

                validation();
                calculationReceivedAmount();
            });

            EXCHANGE_FORM.on('input', '#sending_amount', function(e) {
                this.value = this.value.replace(/^\.|[^\d\.]/g, '');
                sendAmount = parseFloat(this.value);

                validation();

                calculationReceivedAmount();
            });

            EXCHANGE_FORM.on('input', '#receiving_amount', function(e) {

                if (!sendId) {
                    this.value = this.value.replace('');
                    return false;
                }

                this.value = this.value.replace(/^\.|[^\d\.]/g, '');
                receivedAmount = parseFloat(this.value);

                validation();
                calculationSendAmount();
            });


            const validation = () => {
                let error = true;

                if (sendId && receivedId && sendId == receivedId) {
                    error = true;
                    notify('error', `@lang('Send & received currency can not be same')`)
                } else {
                    error = false;
                }

                if (error) {
                    EXCHANGE_FORM.find("button[type=submit]").addClass('disabled')
                    EXCHANGE_FORM.find("button[type=submit]").attr('disabled', true)
                } else {
                    EXCHANGE_FORM.find("button[type=submit]").removeClass('disabled')
                    EXCHANGE_FORM.find("button[type=submit]").attr('disabled', false)
                }
            }

            const calculationReceivedAmount = () => {

                if (!sendId && !receivedId && !sendCurrencyBuyRate && !receiveCurrencySellRate) {
                    return false;
                }
                let amountReceived = (sendCurrencyBuyRate / receiveCurrencySellRate) * sendAmount;
                $("#receiving_amount").val(amountReceived.toFixed(2))
            }

            const calculationSendAmount = () => {

                if (!sendId && !receivedId && !sendCurrencyBuyRate && !receiveCurrencySellRate) {
                    return false;
                }
                let amountReceived = (receiveCurrencySellRate / sendCurrencyBuyRate) * receivedAmount;
                $("#sending_amount").val(amountReceived.toFixed(2))
            }

        })(jQuery);
    </script>
@endpush
