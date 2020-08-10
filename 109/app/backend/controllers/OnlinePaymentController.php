<?php namespace Modules\Backend\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Modules\PhalconVn\General;
use Modules\Models\Users;
use Modules\Models\UserHistoryTransfer;
use Modules\OnlinePayment\NganLuong;

/**
 * Modules\Controllers\UsersController
 *
 * CRUD to manage users
 */
class OnlinePaymentController extends Controller
{
    public function indexAction()
    {
        echo 123;
        die;
    }

    public function nganLuongProcessAction()
    {
        if ($this->request->isGet()) {
            $nganLuong = new NganLuong();

            $transaction_info = $this->request->get('transaction_info');
            $order_code = $this->request->get('order_code');
            $price = $this->request->get('price');
            $payment_id = $this->request->get('payment_id');
            $payment_type = $this->request->get('payment_type');
            $error_text = $this->request->get('error_text');
            $secure_code = $this->request->get('secure_code');
            $token_nl = $this->request->get('token_nl');
            echo $price;
            $checkPay = $nganLuong->verifyPayment($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code);
            echo $checkPay;
            die;
            if ($checkPay) {
                $checkUserHistoryTransfer = UserHistoryTransfer::findFirstByCode($order_code);

                if (!$checkUserHistoryTransfer) {
                    $orderCodeinfo = explode('_', $order_code);
                    $userId = $orderCodeinfo[2];
                    echo $userId;
                    //save balance
                    $user = Users::findFirstById($userId);
                    $balance = $user->balance + $price;
                    $user->assign(['balance' => $balance]);
                    $user->save();

                    //save history
                    $userHistoryTransfer = new UserHistoryTransfer();
                    $userHistoryTransfer->assign([
                        'user_id' => $userId,
                        'online_payment_type' => 1,
                        'code' => $order_code,
                        'amount' => $price,
                        'payment_id' => $payment_id,
                        'payment_type' => $payment_type,
                        'error_text' => $error_text,
                        'secure_code' => $secure_code,
                        'token_nl' => $payment_id,
                    ]);

                    $userHistoryTransfer->save();

                    $this->flashSession->success('Giao dịch thành công. Số dư hiện tại của bạn là ' . number_format($balance, 0, ',', '.'));
                    return $this->response->redirect(ACP_NAME . '/users/historyTransfer/' . $userId);
                }
            }
        }
        $this->view->disable();
    }
}
