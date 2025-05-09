<?php

namespace Marvel\Traits;

use Exception;
use Illuminate\Http\Request;
use Marvel\Database\Models\Order;
use Marvel\Database\Models\Settings;
use Marvel\Enums\OrderStatus;
use Marvel\Enums\PaymentStatus;
use Marvel\Facades\Payment;

trait PaymentStatusManagerWithOrderTrait
{
    use OrderStatusManagerWithPaymentTrait, PaymentTrait;


    /**
     * stripe
     *
     * @param  mixed $order
     * @param  mixed $request
     * @param  mixed $settings
     * @return void
     */
    public function stripe($order, $request, $settings): void
    {
        try {
            $chosen_intent = '';
            // for single gateway options
            // if (isset($order->payment_intent)) {
            //     foreach ($order->payment_intent as $key => $intent) {
            //         if (strtoupper($settings->options['paymentGateway']) === $order->payment_gateway) {
            //             $chosen_intent = $intent;
            //         }
            //     }
            // }

            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($request['payment_gateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $intent_secret = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['client_secret'] : null;
            $payment_intent_id = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;

            if (isset($intent_secret) && isset($payment_intent_id)) {
                $retrieved_intent = Payment::retrievePaymentIntent($payment_intent_id);
                $retrieved_intent_status = $retrieved_intent->status;

                switch ($retrieved_intent_status) {
                    case 'succeeded':
                        $this->paymentSuccess($order);
                        break;

                    case 'requires_action':
                        $this->paymentProcessing($order);
                        break;

                    case 'requires_payment_method':
                        $this->paymentFailed($order);
                        break;
                }
            }
        } catch (Exception $e) {
            throw new \Exception(SOMETHING_WENT_WRONG);
        }
    }

    /**
     * Status change for paypal
     *
     * @throws Exception
     */
    public function paypal(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';

            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($request['payment_gateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }


            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $payment = Payment::verify($paymentId);
                if ($payment) {
                    $paymentStatus = $payment["status"];
                    switch (strtolower($paymentStatus)) {
                        case "completed":
                            $this->paymentSuccess($order);
                            break;
                        case "payer_action_required":
                            $this->paymentProcessing($order);
                            break;
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }

    /**
     * Status change for razorpay
     *
     * @throws Exception
     */
    public function razorpay(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';

            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($request['payment_gateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $paymentStatus = Payment::verify($paymentId);
                if ($paymentStatus) {
                    switch (strtolower($paymentStatus)) {
                        case "paid":
                            $this->paymentSuccess($order);
                            break;
                        case "attempted":
                            $this->paymentProcessing($order);
                            break;
                        case "failed":
                            $this->paymentFailed($order);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }


    /**
     * Status change for mollie
     *
     * @throws Exception
     */
    public function mollie(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';

            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (ucfirst($request['payment_gateway']) === $intent->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $paymentStatus = Payment::verify($paymentId);
                if ($paymentStatus) {
                    switch (strtolower($paymentStatus)) {
                        case "paid":
                            $this->paymentSuccess($order);
                            break;
                        case "pending":
                            $this->paymentAwaitingForApproval($order);
                            break;
                        case "failed":
                            $this->paymentFailed($order);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }
    public function sslcommerz(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';

            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($request['payment_gateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $paymentStatus = Payment::verify($paymentId);
                if ($paymentStatus) {
                    switch (strtolower($paymentStatus)) {
                        case "valid":
                            $this->paymentSuccess($order);
                            break;
                        case "validated":
                            $this->paymentSuccess($order);
                            break;
                        case "pending":
                            $this->paymentAwaitingForApproval($order);
                            break;
                        case "failed":
                            $this->paymentFailed($order);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }

    /**
     * Status change for paystack
     *
     * @throws Exception
     */
    public function paystack(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';

            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($request['payment_gateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $paymentStatus = Payment::verify($paymentId);
                if ($paymentStatus) {
                    switch (strtolower($paymentStatus)) {
                        case "success":
                            $this->paymentSuccess($order);
                            break;
                        case "failed":
                            $this->paymentFailed($order);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }
    public function iyzico(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';
            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($request['payment_gateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $paymentStatus = Payment::verify($paymentId);
                if ($paymentStatus) {
                    switch (strtolower($paymentStatus)) {
                        case "success":
                            $this->paymentSuccess($order);
                            break;
                        case "failed":
                            $this->paymentFailed($order);
                        case "init_threeds":
                            $this->paymentProcessing($order);
                        case "callback_threeds":
                            $this->paymentProcessing($order);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }

    /**
     * Status change for xendit
     *
     * @throws Exception
     */
    public function xendit(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';
            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($request['payment_gateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $paymentStatus = Payment::verify($paymentId);
                if ($paymentStatus) {
                    switch (strtolower($paymentStatus)) {
                        case "paid":
                            $this->paymentSuccess($order);
                            break;
                        case "failed":
                            $this->paymentFailed($order);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }
    /**
     * Status change for coinbase
     *
     * @throws Exception
     */
    public function coinbase(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';
            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($request['payment_gateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $paymentStatus = Payment::verify($paymentId);
                if ($paymentStatus) {
                    switch (strtolower($paymentStatus)) {
                        case "new":
                            $this->paymentAwaitingForApproval($order);
                            break;
                        case "pending":
                            $this->paymentProcessing($order);
                            break;
                        case "completed":
                            $this->paymentSuccess($order);
                            break;
                        case "failed":
                            $this->paymentFailed($order);
                            break;
                        case "expired":
                            $this->paymentFailed($order);
                            break;
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }

    /**
     * Status change for bitpay
     *
     * @throws Exception
     */
    public function bitpay(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';
            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($request['payment_gateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $paymentStatus = Payment::verify($paymentId);
                if ($paymentStatus) {
                    switch (strtolower($paymentStatus)) {
                        case "complete":
                            $this->paymentSuccess($order);
                            break;
                        case "confirmed":
                            $this->paymentSuccess($order);
                            break;
                        case "paid":
                            $this->paymentProcessing($order);
                            break;
                        case "new":
                            $this->paymentProcessing($order);
                            break;
                        case "expired":
                            $this->paymentFailed($order);
                            break;
                        case "invalid":
                            $this->paymentFailed($order);
                            break;
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }

    /**
     * Status change for bkash
     *
     * @throws Exception
     */
    public function bkash(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';
            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($request['payment_gateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $paymentStatus = Payment::verify($paymentId);
                if ($paymentStatus) {
                    switch (strtolower($paymentStatus)) {
                        case "completed":
                            $this->paymentSuccess($order);
                            break;
                        case "failed":
                            $this->paymentFailed($order);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }

    /**
     * Status change for paymongo
     *
     * @throws Exception
     */
    public function paymongo(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';
            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($request['payment_gateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $paymentStatus = Payment::verify($paymentId);
                if ($paymentStatus) {
                    switch (strtolower($paymentStatus)) {
                        case "paid":
                            $this->paymentSuccess($order);
                            break;
                        case "chargeable":
                            $this->paymentAwaitingForApproval($order);
                            break;
                        case "pending":
                            $this->paymentAwaitingForApproval($order);
                            break;
                        case "failed":
                            $this->paymentFailed($order);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT . $e->getMessage());
        }
    }

    /**
     * Status change for flutterwave
     *
     * @throws Exception
     */
    public function flutterwave(Order $order, Request $request, Settings $settings): void
    {
        try {
            $chosen_intent = '';
            // for multi-gateway options
            if (isset($order->payment_intent)) {
                foreach ($order->payment_intent as $key => $intent) {
                    if (strtoupper($request['payment_gateway']) === $order->payment_gateway) {
                        $chosen_intent = $intent;
                    }
                }
            }

            $paymentId = isset($chosen_intent->payment_intent_info) ? $chosen_intent->payment_intent_info['payment_id'] : null;
            if (isset($paymentId)) {
                $paymentStatus = Payment::verify($paymentId);
                if ($paymentStatus) {
                    switch (strtolower($paymentStatus)) {
                        case "successful":
                            $this->paymentSuccess($order);
                            break;
                        case "failed":
                            $this->paymentFailed($order);
                    }
                }
            }
        } catch (Exception $e) {
            throw new Exception(SOMETHING_WENT_WRONG_WITH_PAYMENT);
        }
    }

    /**
     * alipay
     *
     * @param  Order  $order
     * @param  Request  $request
     * @param  Settings  $settings
     * @return void
     */
    public function alipay(Order $order, Request $request, Settings $settings): void
    {
        try {
            $payment_intent = $this->processPaymentIntent($request, $settings);
            $this->orderStatusManagementOnPayment($order, OrderStatus::PROCESSING, PaymentStatus::SUCCESS);
        } catch (Exception $e) {
            throw new HttpException(400, PAYMENT_FAILED);
        }
    }

    /**
     * Update DB status after payment success
     *
     * @param $order
     * @return void
     */
    protected function paymentSuccess($order): void
    {
        $order->order_status = OrderStatus::COMPLETED;
        $order->payment_status = PaymentStatus::SUCCESS;
        $order->save();
        try {
            $children = json_decode($order->children);
        } catch (\Throwable $th) {
            $children = $order->children;
        }
        if (is_array($children) && count($children)) {
            foreach ($order->children as $child_order) {
                $child_order->order_status = OrderStatus::COMPLETED;
                $child_order->payment_status = PaymentStatus::SUCCESS;
                $child_order->save();
            }
        }
        $this->orderStatusManagementOnPayment($order, $order->order_status, $order->payment_status);
    }

    /**
     * Update DB status after payment processing
     *
     * @param $order
     * @return void
     */
    protected function paymentProcessing($order): void
    {
        $order->order_status = OrderStatus::PENDING;
        $order->payment_status = PaymentStatus::AWAITING_FOR_APPROVAL;
        $order->save();
        $this->orderStatusManagementOnPayment($order, $order->order_status, $order->payment_status);
    }

    /**
     * paymentAwaitingForApproval
     *
     * @param  mixed $order
     * @return void
     */
    public function paymentAwaitingForApproval($order): void
    {
        $order->order_status = OrderStatus::PENDING;
        $order->payment_status = PaymentStatus::AWAITING_FOR_APPROVAL;
        $order->save();
        $this->orderStatusManagementOnPayment($order, $order->order_status, $order->payment_status);
    }

    /**
     * Update DB status after payment failed
     *
     * @param $order
     * @return void
     */
    protected function paymentFailed($order): void
    {
        $order->order_status = OrderStatus::FAILED;
        $order->payment_status = PaymentStatus::FAILED;
        $order->save();
        $this->orderStatusManagementOnPayment($order, $order->order_status, $order->payment_status);
    }
}
