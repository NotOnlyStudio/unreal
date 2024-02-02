
<?php $__env->startSection("title","Profit"); ?>
<?php $__env->startSection('errors'); ?>
    <?php
        $user = Auth::user();
    ?>
    <?php if($user->location == null): ?>
        <div class="alert alert-warning w-100">
            To receive payments, you must specify the region of residence.
        </div>
    <?php endif; ?>
    <?php if($user->stripe_success != 1 || $user->stripe_account == null): ?>
        <div class="alert alert-danger w-100">
            To receive payments, you need to go through an additional mini-registration.
            <br>
            <?php if($user->location == null): ?>
                But first you must specify the region of residence.
            <?php else: ?>
                To do this, follow <a href="/stripe/register">this link</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
<?php $__env->stopSection(); ?>
<?php $__env->startSection("content"); ?>
    <div class="d-flex content-right">
        <div class="container d-flex flex-column">
            <?php
                $money = 0;
                for ($i = 0; $i <= count($purchasesCount); $i++) {
                    $money += isset($purchasesCount[$i]['price']) ? round(json_encode($purchasesCount[$i]['price']), 1)  : 0;
                }

            ?>
            <div class="d-flex purchases">
                <purchases-block :models="<?php echo e(json_encode($models)); ?>" :summary="<?php echo e($money); ?>"></purchases-block>
                <charts :purchases-count="<?php echo e(json_encode($purchasesCount)); ?>"></charts>
            </div>
            <p class="block-title mt-4">Withdrawal</p>
            <div class="d-flex withdrawal">

                <div class="transfers mx-0">
                    <div class="transfers__header border-standart">
                        <span>date</span>
                        
                        <span>Status</span>
                        <span>Summary</span>
                        
                    </div>
                    <div class="transfers__content border-standart">
                        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <?php
                                    //                                    $stripe = json_decode($tr->stripe_info);
                                ?>

                                <span><?php echo e(date('m.d.Y',strtotime($tr->created_at))); ?></span>
                                
                                <span><?php echo e($tr->status == 1 ? "Payed" : "Error"); ?></span>
                                <span><?php echo e($tr->sum); ?> USD</span>
                                
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>


                <transfer-block :wallet-score="<?php echo e($money); ?>"></transfer-block>
            </div>
        </div>
    </div>


    <div class="modal fade bepaidwallet " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">

        <svg class="spinner" style="position:absolute; bottom: 0;top: 0;left: 0;right: 0;margin: auto">
            <circle cx="20" cy="20" r="18"></circle>
        </svg>

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h1 class="m-0">Payout</h1>
                    <h1 style="color: gray"><?php echo e($money); ?>$</h1>

                    <div class="card">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="cardNumber" placeholder="Card number"
                                   oninput="formatCardNumber()" onpaste="formatCardNumber()"
                                   onchange="validateCardNumber()" maxlength="19">
                            <label for="cardNumber" class="label" id="cardNumberLabel">Card number</label>
                            <div id="cardNumberError" class="error-message"></div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="expirationDate" placeholder="MM/YY"
                                   oninput="checkInputs()"
                                   onkeydown="formatExpirationDate(event)"
                                   onpaste="formatExpirationDateOnPaste(event)"
                                   onchange="validateInput('expirationDate', 'expirationDateError', 'expirationDateLabel')"
                                   maxlength="7">
                            <label for="expirationDate" class="label" id="expirationDateLabel">Card expiration
                                date</label>
                            <div id="expirationDateError" class="error-message"></div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary" onclick="withdrawToCard()" id="withdrawButton"
                            disabled>
                        Withdraw to card
                    </button>
                    <div id="errorBlock" style="margin-top:15px; color: red; text-align: center"></div>
                    <div id="succBlock" style="margin-top:15px; color: green; text-align: center"></div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function formatExpirationDateOnPaste(event) {
            // Предотвращаем вставку
            event.preventDefault();

            // Получаем вставленный текст
            var pastedText = (event.clipboardData || window.clipboardData).getData('text');

            // Удаляем все символы, кроме цифр
            var cleanText = pastedText.replace(/\D/g, '');

            // Если вставлено 4 цифры, форматируем как MM/YY
            if (cleanText.length === 4) {
                var formattedText = cleanText.slice(0, 2) + ' / ' + cleanText.slice(2);
                document.getElementById('expirationDate').value = formattedText;
            }
        }

        function checkInputs() {
            var cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, ''); // Убираем пробелы
            var expirationDate = document.getElementById('expirationDate').value;
            var withdrawButton = document.getElementById('withdrawButton');

            if (/ \/$/.test(expirationDate)) {
                document.getElementById('expirationDate').value = expirationDate.slice(0, -2);
            }

            if (cardNumber.length === 16 && expirationDate.length === 7) {
                withdrawButton.classList.add('filled');
                document.getElementById('withdrawButton').removeAttribute('disabled');
            } else {
                withdrawButton.classList.remove('filled');
                document.getElementById('withdrawButton').setAttribute('disabled', 'disabled');
            }
        }

        function formatExpirationDate(event) {
            var expirationDateInput = document.getElementById('expirationDate');
            var key = event.key;

            // Если введены две цифры, добавляем символ "/" после них
            if (expirationDateInput.value.length === 2 && key !== 'Backspace') {
                expirationDateInput.value += ' / ';
                event.preventDefault(); // Предотвращаем ввод символа после добавления "/"
            }
        }

        function formatCardNumber() {
            var cardNumberInput = document.getElementById('cardNumber');
            var formattedValue = cardNumberInput.value.replace(/\s/g, ''); // Убираем пробелы
            var formattedString = '';

            for (var i = 0; i < formattedValue.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedString += ' '; // Добавляем пробел после каждой группы из 4 цифр
                }
                formattedString += formattedValue[i];
            }
            cardNumberInput.value = formattedString;
            checkInputs()
        }

        function validateInput(inputId, errorId, labelId) {
            let inputValue = document.getElementById(inputId).value.replace(/\s/g, ''); // Убираем все пробелы
            let inputElement = document.getElementById(inputId);
            let labelElement = document.getElementById(labelId);

            if (!inputValue) {
                document.getElementById(errorId).innerText = 'Это поле обязательно для заполнения';
                inputElement.classList.add('with-error');
                labelElement.classList.add('with-errorlabel');
            } else {
                document.getElementById(errorId).innerText = '';
                inputElement.classList.remove('with-error');
                labelElement.classList.remove('with-errorlabel');
            }
        }

        function validateCardNumber() {
            let cardNumberInput = document.getElementById('cardNumber');
            let cardNumberValue = cardNumberInput.value.replace(/\s/g, '');
            let cardNumberLabel = document.getElementById('cardNumberLabel');

            if (cardNumberValue.length !== 16) {
                document.getElementById('cardNumberError').innerText = 'Номер карты должен содержать 16 цифр';
                cardNumberInput.classList.add('with-error');
                cardNumberLabel.classList.add('with-errorlabel');
            } else {
                document.getElementById('cardNumberError').innerText = '';
                cardNumberInput.classList.remove('with-error');
                cardNumberLabel.classList.remove('with-errorlabel');
            }
        }

        function clearErrors(inputId) {
            var errorElement = document.getElementById(inputId + 'Error');
            var inputElement = document.getElementById(inputId);
            var labelElement = document.getElementById(inputId + 'Label');

            errorElement.innerText = '';
            inputElement.classList.remove('with-error');
            labelElement.classList.remove('with-errorlabel');
        }

        async function withdrawToCard() {
            var cardNumber = document.getElementById('cardNumber').value;
            var expirationDate = document.getElementById('expirationDate').value;
            var errorBlock = document.getElementById('errorBlock');
            var succBlock = document.getElementById('succBlock');

            // Проверка наличия данных
            if (!cardNumber) {
                document.getElementById('cardNumberError').innerText = 'Введите номер карты';
                return;
            }

            if (!expirationDate) {
                document.getElementById('expirationDateError').innerText = 'Введите срок действия карты';
                return;
            }

            // Очистка блока ошибок
            document.getElementById('cardNumberError').innerText = '';
            document.getElementById('expirationDateError').innerText = '';
            errorBlock.innerText = '';
            succBlock.innerText = ''

            withdrawButton.setAttribute('disabled', 'disabled');
            document.querySelector('.spinner').style.zIndex = '99999';
            document.querySelector('.modal-dialog').style.display = 'none';


            axios.post('/bepaid/wallet', {
                cardNumber: cardNumber,
                expirationDate: expirationDate
            })
                .then(function (response) {
                    document.querySelector('.spinner').style.zIndex = '0';
                    document.getElementById('withdrawButton').removeAttribute('disabled');
                    document.querySelector('.modal-dialog').style.display = 'flex';
                    // Ваш код для обработки успешного ответа
                    console.log(response);
                    succBlock.innerText = response.data.message;

                    // Устанавливаем таймер на 3 секунды перед обновлением страницы
                    setTimeout(function () {
                        location.reload();
                    }, 5000);
                })
                .catch(function (error) {
                    document.querySelector('.spinner').style.zIndex = '0';
                    document.getElementById('withdrawButton').removeAttribute('disabled');
                    document.querySelector('.modal-dialog').style.display = 'flex';
                    console.log(error)
                    // Ваш код для обработки ошибки
                    // Ошибка от сервера с кодом состояния
                    if (error.response.status === 422) {
                        // Ошибки валидации
                        const errors = error.response.data.errors;

                        if (errors.cardNumber) {
                            document.getElementById('cardNumberError').innerText = errors.cardNumber[0];
                        }

                        if (errors.expirationDate) {
                            document.getElementById('expirationDateError').innerText = errors.expirationDate[0];
                        }
                    } else {
                        // Другие ошибки сервера
                        errorBlock.innerText = error.response.data.message;
                    }
                });
        }
    </script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentZ'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.cabinet", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Аяз\Desktop\unreal\resources\views/cabinet/profit.blade.php ENDPATH**/ ?>