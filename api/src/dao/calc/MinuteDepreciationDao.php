<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class MinuteDepreciationDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    public function calcMinuteDepreciationByMachine($nameMachine)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT (((cost - residual_value) /60) * years_depreciation)/60/60 AS minuteDepreciation 
                                      FROM machines WHERE machine = :machine");
        $stmt->execute(['machine' => $nameMachine]);
        $dataMachine = $stmt->fetch($connection::FETCH_ASSOC);

        // Modificar depreciacion por minuto
        $stmt = $connection->prepare("UPDATE machines SET minute_depreciation = :minute_depreciation WHERE machine = :machine");
        $stmt->execute(['minute_depreciation' => $dataMachine['minuteDepreciation'], 'machine' => $nameMachine]);
    }
}
