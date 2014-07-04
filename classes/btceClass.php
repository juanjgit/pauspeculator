<?php

/**
 * @package btce API
 * @author https://bxmediaus.com - BX MEDIA - PHP + Bitcoin. We are ready to work on your next bitcoin project. Only high quality coding. https://bxmediaus.com
 * @version 0.1
 * @access public
 * @license http://www.opensource.org/licenses/LGPL-3.0
 */

class Btce
{
        private $key;
        private $secret;
        public $ticker;   // Current ticker (getTicker())
        /**
         * btce::__construct()
         * Sets required key and secret to allow the script to function
         * @param btce API Key $key
         * @param btce Secret $secret
         * @return
         */
        public function __construct($key, $secret)
        {
                if (isset($secret) && isset($key))
                {
                        $this->key = $key;
                        $this->secret = $secret;
                } else
                        die("NO KEY/SECRET");
        }
        /**
         * btce::btce_query()
         * 
         * @param API Path $path
         * @param POST Data $req
         * @return Array containing data returned from the API path
         */
        public function btce_query($method, array $req = array())
        {
 
            $req['method'] = $method;
            $mt = explode(' ', microtime());
            $req['nonce'] = $mt[1];

            // generate the POST data string
            $post_data = http_build_query($req, '', '&');

            $sign = hash_hmac('sha512', $post_data, $this->secret);

            // generate the extra headers
            $headers = array(
                            'Sign: '.$sign,
                            'Key: '.$this->key,
            );

            // our curl handle (initialize if required)
            static $ch = null;
            if (is_null($ch)) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; BTCE PHP client; '.php_uname('s').'; PHP/'.phpversion().')');
            }
            curl_setopt($ch, CURLOPT_URL, 'https://btc-e.com/tapi/');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            // run the query
            $res = curl_exec($ch);
            if ($res === false) throw new Exception('Could not get reply: '.curl_error($ch));
            $dec = json_decode($res, true);
            if (!$dec) throw new Exception('Invalid data received, please make sure connection is working and requested API exists');
            return $dec;
        }
        
        public function btce_query_public($url)
        {
                // our curl handle (initialize if required)
                static $ch = null;
                if (is_null($ch))
                {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_USERAGENT,
                                'Mozilla/4.0 (compatible; PHP client; ' . php_uname('s') . '; PHP/' .
                                phpversion() . ')');
                }
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);  // man-in-the-middle defense by verifying ssl cert.
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);  // man-in-the-middle defense by verifying ssl cert.

                // run the query
                $res = curl_exec($ch);
                if ($res === false)
                        throw new \Exception('Could not get reply: ' . curl_error($ch));
                $dec = json_decode($res, true);
                if (is_null($dec))
                        throw new \Exception('Invalid data received, please make sure connection is working and requested API exists');
                return $dec;
        }
        /**
         * btce::ticker()
         * Returns current ticker from btce
         * @return $ticker
         */
        function ticker($cambio) {
                $ticker = $this->btce_query_public('https://btc-e.com/api/2/'.$cambio.'/ticker');
                $this->ticker = $ticker; // Another variable to contain it.
                return $ticker;

        }
}
