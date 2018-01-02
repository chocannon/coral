<?php
// +----------------------------------------------------------------------
// | 报文辅助类
// +----------------------------------------------------------------------
// | Author: chocannon
// +----------------------------------------------------------------------
namespace Coral\Utility;

class Package{
    /**
     * 打包
     * @param  string $data  待打包数据
     * @return [type]       [description]
     */
    public static function encode(string $data, string $eof = "\r\n"){
        $result = base64_encode(gzcompress($data));
        return pack("N", strlen($result)) . $result . $eof;
    }


    /**
     * 拆包
     * @param  string $data 接受的打包数据
     * @return [type]       [description]
     */
    public static function decode(string $data, string $eof = "\r\n"){
        $data = substr(trim($data, $eof), 4);
        return json_decode(gzuncompress(base64_decode($data)), true);
    }
}