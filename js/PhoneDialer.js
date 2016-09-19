


function callPhoneDialer(number) {
    // window.plugins.phoneDialer.dial(number);
    phonedialer.dial(
            number,
            function (err) {
                if (err == "empty")
                    alert("Unknown phone number");
                else
                    alert("Dialer Error:" + err);
            },
            function (success) {
                // alert(number);
            }
    );
}
