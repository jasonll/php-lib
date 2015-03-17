<?php
 /**
 * @class PaymentPlugin
 * @brief 支付插件抽象类 基类
 */
abstract class paymentPlugin
{
	public $method              = "post";//表单提交模式
	public $name                = null;  //支付插件名称
	public $version             = 1.0;   //版本
	public $callbackUrl         = '';    //支付完成后，同步回调地址
	public $serverCallbackUrl   = '';    //异步通知地址
	public $merchantCallbackUrl ='';	 //支付中断返回

	/**
	* @brief 构造函数
	* @param $payment_id 支付方式ID
	*/
	public function __construct($payment_id)
	{
		//回调函数地址
		$this->callbackUrl         = BASE_URL.("/block/callback/_id/".$payment_id);
		//回调业务处理地址
		$this->serverCallbackUrl   = BASE_URL.("/block/server_callback/_id/".$payment_id);
		//中断支付返回
		$this->merchantCallbackUrl = BASE_URL.("/block/merchant_callback/_id/".$payment_id);
	}

	/**
	 * @brief 记录支付平台的交易号
	 * @param $orderNo string 订单编号
	 * @param $tradeNo string 交易流水号
	 * @return boolean
	 */
	protected function recordTradeNo($orderNo,$tradeNo)
	{
		//$orderDB  = new IModel('order');
		//$orderDB->setData(array('trade_no' => $tradeNo));
		//return $orderDB->update('order_no = "'.$orderNo.'"');
	}

	/**
	 * @brief 开始支付
	 */
	public function doPay($sendData)
	{
echo <<< OEF
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head></head>
			<body>
				<p>please wait...</p>
				<form action="{$this->getSubmitUrl()}" method="{$this->method}">
OEF;
					foreach($sendData as $key => $item)
					{
echo <<< OEF
					<input type='hidden' name='{$key}' value='{$item}' />
OEF;
					}
echo <<< OEF
				</form>
			</body>
			<script type='text/javascript'>
				window.document.forms[0].submit();
			</script>
		</html>
OEF;
	}

	/**
	 * @brief 返回配置参数
	 */
	public function configParam()
	{
		return array(
			'M_PartnerId'  => '商户ID号',
			'M_PartnerKey' => '商户KEY密钥',
		);
	}

	/**
	 * 异步通知停止
	 */
	abstract public function notifyStop();

	/**
	 * 获取提交地址
	 * @return string Url提交地址
	 */
	abstract public function getSubmitUrl();

	/**
	 * 获取要发送的数据数组结构
	 * @param $payment array 要传递的支付信息
	 * @return array
	 */
	abstract public function getSendData($paymentInfo);

	/**
	 * 同步支付回调
	 * @param $ExternalData array  支付接口回传的数据
	 * @param $paymentId    int    支付接口ID
	 * @param $money        float  交易金额
	 * @param $message      string 信息
	 * @param $orderNo      string 订单号
	 */
	abstract public function callback($ExternalData,&$paymentId,&$money,&$message,&$orderNo);

	/**
	 * 同步支付回调
	 * @param $ExternalData array  支付接口回传的数据
	 * @param $paymentId    int    支付接口ID
	 * @param $money        float  交易金额
	 * @param $message      string 信息
	 * @param $orderNo      string 订单号
	 */
	abstract public function serverCallback($ExternalData,&$paymentId,&$money,&$message,&$orderNo);
}