<?php

namespace App\Extensions;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Session\DatabaseSessionHandler;
use Illuminate\Support\Facades\DB;
use Carbon;

class MySessionHandler extends DatabaseSessionHandler {

    public function __construct(ConnectionInterface $connection, $table, $minutes, Container $container = null) {
        parent::__construct($connection, $table, $minutes, $container);
        $this->container = app();
    }

    public function gc($lifetime) {
        $sessions = $this->getQuery()
                //->where('last_activity', '<=', Carbon::now()->getTimestamp() - $lifetime)
                ->where('last_activity', '<=', time() - $lifetime)
                ->get()
        ;
        foreach ($sessions as $session) {
            $this->clearCart($session->id);
            DB::table('sessions')->where('id', $session->id)->delete();
        }
    }

    public function clearCart($sessionId) {
        DB::table('cart_rows')
                ->where('session_id', $sessionId)
                ->whereNull('customer_id')
                ->delete()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data) {
        $payload = $this->getDefaultPayload($data);

        if (!$this->exists) {
            $this->read($sessionId);
        }
        $payload['user_agent'] = $this->userAgent();
        $payload['ip_address'] = $this->ipAddress();
        $payload['user_id'] = $this->userId();
        if ($this->exists) {
            $this->performUpdate($sessionId, $payload);
        } else {
            $this->performInsert($sessionId, $payload);
        }

        return $this->exists = true;
    }

}
