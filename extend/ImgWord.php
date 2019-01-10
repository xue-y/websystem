<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/1/2
 * Time: 13:05
 */

class ImgWord
{
	// 百度 api https://ai.baidu.com/docs#/OCR-API/top
    // 获取token url
    const TOKEN_URL ='https://aip.baidubce.com/oauth/2.0/token';
    //从服务器接收缓冲完成前需要等待多长时间
    const CURLOPT_TIMEOUT=10;
    //等待成功连接服务器多久
    const CURLOPT_CONNECTTIMEOUT=30;
    // api key
    const API_KEY='你的 Api Key';
    // Secret Key
    const SECRET_KEY='你的 Secret Key';

    // 默认图片调用接口
    public $img_type_default="general_basic";

    // 获取access_token
    public function get_token()
    {
       $access_token=cache('access_token');
       if(!empty($access_token))
       {
           return $access_token;
       }

        $post_data['grant_type']    = 'client_credentials';
        $post_data['client_id']     = self::API_KEY;
        $post_data['client_secret'] = self::SECRET_KEY;
        $o = "";
        foreach ( $post_data as $k => $v )
        {
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);

        $res = $this->post_auth(self::TOKEN_URL, $post_data);
        $token=json_decode($res,true);
        if(isset($token['error']))
        {
            trace($token["error_description"],config('log.web_level'));
            exit($token);
        }
        cache('access_token',$token["access_token"],$token["expires_in"]);
        return $token["access_token"];
    }

    // 发送post 请求,获取access_token
    public function post_auth($url,$param)
    {
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且不输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS,$param);

        // 忽略 https 验证，否则返回 FALSE
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($curl, CURLOPT_TIMEOUT, self::CURLOPT_TIMEOUT);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, self::CURLOPT_CONNECTTIMEOUT);
        $data = curl_exec($curl);//运行curl
        curl_close($curl);
        return $data;
    }

     /**组装数据发送请求
     * @param $img_data array 要发送的数据
     * @param $img_type string 请求的图片类型接口
     * @return  array 请求回来的数据
     * */
    public function img_data($img_data,$img_type)
    {
        $token=$this->get_token();
        $header = ['Content-Type: application/x-www-form-urlencoded'];
        $api = 'https://aip.baidubce.com/rest/2.0/ocr/v1/'.$img_type.'?access_token='.$token;
        return $this->post_data($api,$img_data,$header);
    }

    /**
     * @param  string $url
     * @param  array $data HTTP POST BODY
     * @param  array $param HTTP URL
     * @param  array $headers HTTP header
     * @return array
     */
    public function post_data($url, $data, $headers=array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS,is_array($data) ? http_build_query($data) : $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::CURLOPT_TIMEOUT);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::CURLOPT_CONNECTTIMEOUT);
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($code === 0){
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);
        return array(
            'code' => $code,
            'content' => $content,
        );
    }

    //请求api 数据失败返回错误原因
    public function error_meg($error)
    {
        switch ($error["error_code"])
        {
            case 17:
            case 18:
            case 111:
            case 216201:
            case 216202:
            case 282103:
            case 282111:
            case 282112:
            case 282113:
            case 282114:
                return $error["error_msg"];
                break;
            case 216630:
            case 216631:
            case 216633:
            case 216634:
                return $error["error_msg"];
                trace($error["error_code"].'：'.$error["error_msg"],config('log.web_level'));
                break;
            default:
                trace($error["error_code"].'：'.$error["error_msg"],config('log.web_level'));
                return 'service unavailability';
        }
    }

    /**图片识别类型--分组
     * @param $api_name string  数组key值
     * @return array
     * */
    public function img_type($api_name=null)
    {
        //网络图片
        $arr['net']=[
            "webimage"=>"网络图片",  // 网络图片
        ];
        // 票据
        $arr['bill']=[
            "train_ticket"=>"火车票",
            "taxi_receipt"=>"出租车票",
            "vat_invoice"=>"增值税发票",
            "receipt"=>"其他/通用票据",// 票据识别
        ];
        $arr['cert']=[
            "driving_license"=>"驾驶证",// 证件号码
            "vehicle_license"=>"行驶证",
            "business_license"=>"营业执照",
            "accurate_basic"=>"其他/通用识别", // 高精度 通用
        ];
        // 通用格式处理
        $arr['default']=[
            'accurate_basic',$this->img_type_default,'receipt'
        ];

       return isset($arr[$api_name])?$arr[$api_name]:$this->img_type_default;
    }


    /** 根据不同的api 接口返回的数据进行特定处理--票据处理
     * @param  $field_name api接口分类名称
     * @return array 取得字段名称--转换成文字
     * */
    public function bill_field_word($field_name)
    {
        // 火车票
        $arr['train_ticket']=[
            'ticket_num'=>'车票号',
            'starting_station'=>'始发站',
            'train_num'=>'车次号',
            'destination_station'=>'到达站',
            'date'=>'出发日期',
            'ticket_rates'=>'车票金额',
            'seat_category'=>'席别',
            'name'=>'乘客姓名',
        ];
        // 出租车票
        $arr['taxi_receipt']=[
            'Date'=>'日期',
            'Fare'=>'实付金额',
            'InvoiceCode'=>'发票代号',
            'InvoiceNum'=>'发票号码',
            'TaxiNum'=>'车牌号',
            'Time'=>'上下车时间',
        ];
        // 增值税发票
        $arr['vat_invoice']=[
            'InvoiceType'=>'发票种类名称',
            'InvoiceCode'=>'发票代码',
            'InvoiceNum'=>'发票号码',
            'InvoiceDate'=>'开票日期',
            'TotalAmount'=>'合计金额',
            'TotalTax'=>'合计税额',
            'AmountInFiguers'=>'	价税合计(小写)',
            'AmountInWords'=>'价税合计(大写)',
            'SellerBank'=>'发票机构',
            'Checker'=>'发票操作员',
            'CheckCode'=>'校验码',
            'SellerName'=>'销售方名称',
            'SellerRegisterNum'=>'销售方纳税人识别号',
            'PurchaserName'=>'购方名称',
            'PurchaserRegisterNum'=>'购方纳税人识别号',
            'CommodityName'=>'货物名称',
            'word'=>'内容',
            'CommodityType'=>'规格型号',
            'CommodityUnit'=>'单位',
            'CommodityNum'=>'数量',
            'CommodityPrice'=>'单价',
            'CommodityAmount'=>'金额',
            'CommodityTaxRate'=>'税率',
            'CommodityTax'=>'税额',
            'row'=>'行号',
            'Remarks'=>'校验码',
            'Password'=>'密码区',
            'SellerAddress'=>'销售方地址'
        ];
        return  isset($arr[$field_name])?$arr[$field_name]:$field_name;
    }
}