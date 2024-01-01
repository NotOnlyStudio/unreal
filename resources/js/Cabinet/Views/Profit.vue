<template>
        <div class="container">

            <div class="d-flex container flex-column">
                <div class="d-flex flex-column">
                    <div class="d-flex purchases">
                        <div class="user-purchases">
                            <div class="d-flex flex-column">
                                <div class="user-purchases__header">
                                    <p>model</p>
                                    <p>amount</p>
                                    <p>Income</p>
                                </div>
                                <div class="user-purchases__content">
                                    <div><span>Sofa minotti</span><span>9. 728</span><span>10.858.05 USD</span></div>
                                    <div><span>Hem Sofa</span><span>8. 043</span><span>10.858 USD</span></div>
                                    <div><span>Togo Sofa 198</span><span>7. 122</span><span>9.233.123 USD</span></div>
                                    <div><span>Eames Lounge Cha...</span><span>5. 614</span><span>4.543.123 USD</span></div>
                                    <div><span>Bold Chair</span><span>2. 390</span><span>3.213.321 USD</span></div>
                                    <div><span>MC2 Side Table</span><span>1. 678</span><span>3.213.321 USD</span></div>
                                    <div><span>Pachira-Money Tra...</span><span>1. 305</span><span>3.213.321 USD</span></div>
                                    <div><span>Au Coeur Della Vill...</span><span>1. 305</span><span>3.213.321 USD</span></div>
                                    <div><span>Togo set 1</span><span>732</span><span>1.599.5 USD</span></div>
                                    <div><span>Lorem 1</span><span>1.123</span><span>231 USD</span></div>
                                    <div><span>Lorem 2</span><span>1.123</span><span>231 USD</span></div>
                                    <div><span>Lorem 3</span><span>1.123</span><span>231 USD</span></div>
                                    <div><span>Lorem 5</span><span>1.123</span><span>231 USD</span></div>
                                    <div><span>Lorem 5</span><span>1.123</span><span>231 USD</span></div>
                                    <div><span>Lorem 5</span><span>1.123</span><span>231 USD</span></div>
                                    <div><span>Lorem 5</span><span>1.123</span><span>231 USD</span></div>
                                    <div><span>Lorem 5</span><span>1.123</span><span>231 USD</span></div>
                                </div>
                                <p class="user-purchases-total ">
                                    Summary: <span>53. 884. 08 USD</span>
                                </p>
                            </div>
                        </div>
                        <div class="analytics border-standart p-3">
                            <img src="img/analytics.svg" alt="">
                        </div>
                    </div>
                    <p class="block-title">Withdrawal</p>
                    <div class="d-flex withdrawal">
                        <div class="d-flex flex-column">
                            <div class="paymend__form paymend__form-profile ">
                                <ul id="myTab" role="tablist" class="nav nav-tabs">
                                    <li role="presentation" class="nav-item visa"><button id="visa-tab" data-bs-toggle="tab" data-bs-target="#visa" type="button" role="tab" aria-controls="visa" aria-selected="false" class="nav-link"><img src="/img/icons/visa.svg" alt="Visa"></button></li>
                                    <li role="presentation" class="nav-item mastercard"><button id="mastercard-tab" data-bs-toggle="tab" data-bs-target="#mastercard" type="button" role="tab" aria-controls="mastercard" aria-selected="false" class="nav-link"><img src="/img/icons/mastercard.svg" alt="Visa"></button></li>
                                    <li role="presentation" class="nav-item paypal"><button id="paypal-tab" data-bs-toggle="tab" data-bs-target="#paypal" type="button" role="tab" aria-controls="paypal" aria-selected="true" class="nav-link active"><img src="/img/icons/paypal.svg" alt=""></button></li>
                                    <li role="presentation" class="nav-item webmoney"><button id="webmoney-tab" data-bs-toggle="tab" data-bs-target="#webmoney" type="button" role="tab" aria-controls="webmoney" aria-selected="false" class="nav-link"><img src="/img/icons/webmoney.svg" alt=""></button></li>
                                    <li role="presentation" class="nav-item payoneer"><button id="payoneer-tab" data-bs-toggle="tab" data-bs-target="#payoneer" type="button" role="tab" aria-controls="payoneer" aria-selected="false" class="nav-link"><img src="/img/icons/payoneer.svg" alt=""></button></li>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div id="visa" role="tabpanel" aria-labelledby="home-tab" class="tab-pane fade">
                                        <h1>visa</h1>
                                    </div>
                                    <div id="mastercard" role="tabpanel" aria-labelledby="profile-tab" class="tab-pane fade">
                                        <h1>mastercard</h1>
                                    </div>
                                    <div id="paypal" role="tabpanel" aria-labelledby="contact-tab" class="tab-pane fade active show">
                                        <div class="paypal-container">
                                            <div class="name"><input type="text" placeholder="Name" name="" id="" class=" form-control name__inp"></div>
                                            <div class="surname"><input type="text" placeholder="Surname" name="" id="" class=" form-control surname__inp"></div>
                                            <div class="birthday"><input type="text" placeholder="Birthday" name="" id="" class=" form-control birthday__inp"></div>
                                            <div class="country">
                                                <select name="" id="" class="country form-control ">
                                                    <option value="country" selected="selected" disabled="disabled">Country</option>
                                                    <option value="USA">USA</option>
                                                    <option value="Australia">Australia</option>
                                                </select>
                                            </div>
                                            <div class="email"><input type="text" placeholder="Your PayPal e-mail" class="email__inp form-control"></div>
                                            <div class="payment_area">
                                                <div class="price"><span>9.00</span><span>USD</span></div>
                                                <button class="btn btn-bordered">Transfer</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="webmoney" role="tabpanel" aria-labelledby="contact-tab" class="tab-pane fade">
                                        <h1>webmoney</h1>
                                    </div>
                                    <div id="payoneer" role="tabpanel" aria-labelledby="contact-tab" class="tab-pane fade">
                                        <h1>payoneer</h1>
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0">PayPal fee current exchange rate ~ -8%, up to 2 working days</p>
                        </div>
                        <div class="transfers">
                            <div class="transfers__header border-standart">
                                <span>date</span>
                                <span>transaction</span>
                                <span>System</span>
                                <span>Account</span>
                                <span>Status</span>
                                <span>Summary</span>
                                <span>Invoice</span>
                            </div>
                            <div class="transfers__content border-standart">
                                <div>
                                    <span>13.03.2021</span>
                                    <span>3213fas</span>
                                    <span>PayPal</span>
                                    <span>lucyma19..</span>
                                    <span>Payed</span>
                                    <span>5. 332 USD</span>
                                    <span>34d2f</span>
                                </div>
                                <div>
                                    <span>13.04.2021</span>
                                    <span>3213fas</span>
                                    <span>PayPal</span>
                                    <span>213fsd..</span>
                                    <span>Payed</span>
                                    <span>5. 212 USD</span>
                                    <span>31d2f</span>
                                </div>
                                <div>
                                    <span>11.04.2021</span>
                                    <span>3232fas</span>
                                    <span>Visa</span>
                                    <span>212fsd..</span>
                                    <span>Payed</span>
                                    <span>432 USD</span>
                                    <span>44d2f</span>
                                </div>
                                <div>
                                    <span>09.04.2021</span>
                                    <span>3a32fas</span>
                                    <span>Visa</span>
                                    <span>2s2fsd..</span>
                                    <span>Payed</span>
                                    <span>5. 22 USD</span>
                                    <span>44d2f</span>
                                </div>
                                <div>
                                    <span>13.03.2021</span>
                                    <span>3213fas</span>
                                    <span>PayPal</span>
                                    <span>lucyma19..</span>
                                    <span>Payed</span>
                                    <span>5. 332 USD</span>
                                    <span>34d2f</span>
                                </div>
                                <div>
                                    <span>13.04.2021</span>
                                    <span>3213fas</span>
                                    <span>PayPal</span>
                                    <span>213fsd..</span>
                                    <span>Payed</span>
                                    <span>5. 212 USD</span>
                                    <span>31d2f</span>
                                </div>
                                <div>
                                    <span>11.04.2021</span>
                                    <span>3232fas</span>
                                    <span>Visa</span>
                                    <span>212fsd..</span>
                                    <span>Payed</span>
                                    <span>432 USD</span>
                                    <span>44d2f</span>
                                </div>
                                <div>
                                    <span>09.04.2021</span>
                                    <span>3a32fas</span>
                                    <span>Visa</span>
                                    <span>2s2fsd..</span>
                                    <span>Payed</span>
                                    <span>5. 22 USD</span>
                                    <span>44d2f</span>
                                </div>
                                <div>
                                    <span>13.03.2021</span>
                                    <span>3213fas</span>
                                    <span>PayPal</span>
                                    <span>lucyma19..</span>
                                    <span>Payed</span>
                                    <span>5. 332 USD</span>
                                    <span>34d2f</span>
                                </div>
                                <div>
                                    <span>13.04.2021</span>
                                    <span>3213fas</span>
                                    <span>PayPal</span>
                                    <span>213fsd..</span>
                                    <span>Payed</span>
                                    <span>5. 212 USD</span>
                                    <span>31d2f</span>
                                </div>
                                <div>
                                    <span>11.04.2021</span>
                                    <span>3232fas</span>
                                    <span>Visa</span>
                                    <span>212fsd..</span>
                                    <span>Payed</span>
                                    <span>432 USD</span>
                                    <span>44d2f</span>
                                </div>
                                <div>
                                    <span>09.04.2021</span>
                                    <span>3a32fas</span>
                                    <span>Visa</span>
                                    <span>2s2fsd..</span>
                                    <span>Payed</span>
                                    <span>5. 22 USD</span>
                                    <span>44d2f</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


</template>

<script>
    export default {
        name: "Profit"
    }
</script>

<style scoped>

</style>
