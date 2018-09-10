<?php

namespace App\Traits;

use ReCaptcha\ReCaptcha;

trait CaptchaTrait {

    public function captchaCheck($response) {
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $secret = config('app.RE_CAP_SECRET');

        $recaptcha = new ReCaptcha($secret);
        $resp = $recaptcha->verify($response, $remoteip);
        if ($resp->isSuccess()) {
            return 1;
        } else {
            return 0;
        }
    }

}
