{{-- Listen for payment status changes --}}
<script>
    var pay_status = "{{ $payment->status }}";
    var payment_id = "{{ $payment->id }}";
    var checkInterval;

    function check_payment_status() {
        console.log("payment status...");
        if (pay_status === 'pending') {
            $.ajax({
                type: "POST",
                url: "{{ route('payments.check_status', $payment->id) }}",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    if (data.payment_status === "success") {
                        $(".main-content").hide();
                        $("#success_pay_box").show();
                        console.log("Success payment");
                        pay_status = 'success';
                        clearInterval(checkInterval);
                    }
                },
                error: function (xhr) {
                    console.error("Error:", xhr.responseText);
                }
            });
        }
    }

    if (pay_status === 'pending') {
        check_payment_status();
        checkInterval = setInterval(check_payment_status, 3000);
    }
</script>

{{-- Countdown Timer and changes payments status --}}
<script>
    const expiresAt = new Date("{{ $payment->expires_at }}").getTime();
    const now = new Date().getTime();
    let countdownTime = Math.floor((expiresAt - now) / 1000);
    const totalTime = countdownTime;
    const countdownElement = document.getElementById("countdown");
    const timerProgress = document.querySelector(".timer-progress");

    function startCountdown() {
        countdownInterval = setInterval(() => {
            if (pay_status === "success") {
                $(".main-content").hide();
                $(".payment-timer").hide();
                $("#success_pay_box").show();
                clearInterval(countdownInterval);
                return;
            }

            if (countdownTime <= 0) {
                clearInterval(countdownInterval);
                countdownElement.textContent = "QR expired";
                timerProgress.style.width = "0%";
                document.querySelector(".payment-timer").style.display = "none";
                document.querySelector(".qr-code").innerHTML =
                    "<div style='color: red; font-weight: bold;' class='text-center'>" +
                    "QR code expired.<br>" +
                    "<a href='{{ route('orders.create', ['type' => 'event', 'id' => $product_id]) }}' class='btn btn-danger mt-2'>Generate New QR</a></div>";
                pay_status = 'expired';
                $.ajax({
                    type: "POST",
                    url: "{{ route('payments.update_status', $payment->id) }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: "expired"
                    },
                    success: function () {
                        pay_status = 'expired';
                        console.log("Payment expired successfully");
                        clearInterval(countdownInterval);
                    },
                    error: function (xhr) {
                        console.error("Error updating payment status:", xhr.responseText);
                    }
                });
                return;
            }

            const minutes = Math.floor(countdownTime / 60);
            const seconds = countdownTime % 60;
            countdownElement.textContent = `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
            timerProgress.style.width = (countdownTime / totalTime) * 100 + "%";
            countdownTime--;

        }, 1000);
    }

    if (pay_status !== "expired" && "{{ $payment->status }}" !== "expired") {
        startCountdown();
    }
</script>