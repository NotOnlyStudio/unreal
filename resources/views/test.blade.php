<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>
<body>
    
<form action="/charge" method="post" id="payment-form">
  <div class="form-row">
    <label for="card-element">
      Credit or debit card
    </label>
    <div id="card-element">
      <!-- A Stripe Element will be inserted here. -->
    </div>

    <!-- Used to display Element errors. -->
    <div id="card-errors" role="alert"></div>
  </div>

  <button>Submit Payment</button>
</form>

<script src="https://js.stripe.com/v3/"></script>
<script>
var stripe = Stripe('pk_test_51IfcA5B6Pg0b0VT8UZQ5KxeZhoBbGKpuRtl1ATTuTwfnhcxnAvkVC3RsiMc8ev3d3TwAeoqUdT8ZiMY4Mnh3LvCk00HurO4XRX');
var elements = stripe.elements();
var style = {
  base: {
    // Add your base input styles here. For example:
    fontSize: '16px',
    color: '#32325d',
  },
};

// Create an instance of the card Element.
var card = elements.create('card', {style: style});

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');
</script>
</body>
</html>