<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\modules\bookkeeping\forms\TransactionDebitCreditForm $transactionForm
 * @var array $categories
 */

use bulldozer\App;
use frontend\assets\QRAsset;

$bundle = QRAsset::register($this);

$this->title = 'Сканирование чека';
$this->params['breadcrumbs'][] = ['label' => 'Кошельки', 'url' => ['/bookkeeping/bills/index']];
$this->params['breadcrumbs'][] = ['label' => $bill->name, 'url' => ['/bookkeeping/bills/view', 'id' => $bill->id]];
$this->params['breadcrumbs'][] = 'Сканирование чека';

?>
<?php if (!$transactionForm->hasErrors()): ?>
    <p id="loading">Инициализация камеры...</p>

    <div class="row">
        <div class="col-md-12">
            <button id="toggle-btn" class="btn btn-primary" style="display: none;">
                <i class="fa fa-camera" aria-hidden="true"></i>
            </button>

            <video id="preview"></video>
        </div>
    </div>
<?php endif ?>

<?= $this->render('_form_debit_credit', [
    'transactionForm' => $transactionForm,
    'categories' => $categories,
    'isNewRecord' => true,
]) ?>

<?php if (!$transactionForm->hasErrors()): ?>
    <style>
        #w0 {
            display: none;
        }
    </style>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            let cameras = [];
            let currentCamera = 0;
            let scanner = new Instascan.Scanner({
                video: document.getElementById('preview'),
                mirror: false
            });

            function parseQuery(queryString) {
                let query = {};
                let pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');
                for (let i = 0; i < pairs.length; i++) {
                    let pair = pairs[i].split('=');
                    query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
                }
                return query;
            }

            function formatNumber(input) {
                if (input >= 10) {
                    return input;
                }

                return '0' + input;
            }

            scanner.addListener('scan', function (content) {
                scanner.stop();

                $('#preview').parent().hide();

                $.ajax({
                    type: 'POST',
                    url: '/api/check/parse',
                    dataType: 'json',
                    data: JSON.stringify({
                        data: content
                    }),
                    processData: false,
                    headers: {
                        'Authorization':'Bearer <?= App::$app->user->getIdentity()->access_token ?>',
                        'Content-Type':'application/json'
                    },
                    success: function (data) {
                        $('#loading').hide();
                        $('#w0').show();

                        $('#transactiondebitcreditform-sum').val(data.sum);
                        $('#transactiondebitcreditform-date').val(data.date);
                        $('#transactiondebitcreditform-check_id').val(data.id);
                    },
                    always: function() {
                        $('#loading').html('Обработка данных...');
                        $('#loading').show();
                    },
                    error: function() {
                        alert('Произошла ошибка');
                    }
                });
            });

            function updateCanvasSize() {
                let width = $(document).width();
                width -= 30;

                if (width > 500) {
                    width = 500;
                }

                $('#preview').width(width);
            }

            updateCanvasSize();

            $(window).resize(function() {
                updateCanvasSize();
            });

            $('#toggle-btn').click(function() {
                currentCamera++;

                if (currentCamera >= cameras.length) {
                    currentCamera = 0;
                }

                scanner.start(cameras[currentCamera]);
            });

            Instascan.Camera.getCameras().then(function (_cameras) {
                $('#loading').hide();

                if (_cameras.length > 0) {
                    cameras = _cameras;
                    scanner.start(_cameras[currentCamera]);

                    if (_cameras.length > 1) {
                        $('#toggle-btn').show();
                    }
                } else {
                    console.error('No cameras found.');
                }
            }).catch(function (e) {
                console.error(e);
            });
        });
    </script>
<?php endif ?>