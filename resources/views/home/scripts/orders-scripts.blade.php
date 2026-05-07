<script
    src="https://www.sandbox.paypal.com/sdk/js?client-id={{ config('settings.paypal.client_id') }}&currency=USD&intent=capture&components=buttons"></script>

{{-- Sepay Handle --}}
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const sepayRadio = document.getElementById('sepay');
        const paypalRadio = document.getElementById('paypal-radio');
        const paypalContainer = document.getElementById('paypal-button-container');
        const checkoutBtn = document.querySelector('.checkout-btn');

        function togglePaymentMethod() {
            if (paypalRadio.checked) {
                paypalContainer.style.display = 'block';
                checkoutBtn.style.display = 'none';
            } else {
                paypalContainer.style.display = 'none';
                checkoutBtn.style.display = 'block';

            }
        }

        sepayRadio.addEventListener('change', togglePaymentMethod);
        paypalRadio.addEventListener('change', togglePaymentMethod);
        togglePaymentMethod();
    });

    paypal.Buttons({
        fundingSource: paypal.FUNDING.PAYPAL,
        createOrder: function (data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: document.getElementById('order-summary-amount').dataset.priceUsd
                    }
                }]
            });
        },
        onApprove: function (data, actions) {
            return actions.order.capture().then(function (details) {
                console.log('Transaction completed by ' + details.payer.name.given_name);

                return fetch('/orders/paypal', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        orderID: data.orderID,
                        course_id: @json($course->id),
                        price: @json($price),
                        quantity: 1,
                        note: document.getElementById('note').value
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Transaction completed successfully!');
                            window.location.href = data.redirect_url;
                        } else {
                            alert('Transaction failed. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            });
        }
    }).render('#paypal-button-container');
</script> --}}


{{-- Change currency --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sepayRadio = document.getElementById('sepay');
        const paypalRadio = document.getElementById('paypal-radio');
        const subtotal = document.getElementById('subtotal-amount');
        const total = document.getElementById('total-amount');

        function updateCurrency() {
            if (paypalRadio.checked) {
                subtotal.textContent = '$' + subtotal.dataset.priceUsd + ' USD';
                total.textContent = '$' + total.dataset.priceUsd + ' USD';
                subtotal.style.color = '#0070e0';
                total.style.color = '#0070e0';
            } else {
                subtotal.textContent = subtotal.dataset.priceVnd + ' VND';
                total.textContent = total.dataset.priceVnd + ' VND';
                subtotal.style.color = '#111111';
                total.style.color = '#111111';
            }
        }

        sepayRadio.addEventListener('change', updateCurrency);
        paypalRadio.addEventListener('change', updateCurrency);
        updateCurrency();
    });
</script>