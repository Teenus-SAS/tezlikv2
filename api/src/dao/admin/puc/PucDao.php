<?php

namespace tezlikv2\dao;

use DateTime;
use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class PucDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }


    //OBTENER PUC TOTALES
    public function findAllCountsPUC()
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT * FROM puc ORDER BY id_puc ASC");
        $stmt->execute();
        $puc = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("puc", array('puc' => $puc));
        $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

        return $puc;
    }


    //ACTUALIZAR PUC
    public function updateCountsPUC($dataPuc)
    {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("UPDATE puc SET number_count = :number_count, count = :count
                                          WHERE id_puc = :id_puc");
            $stmt->execute([
                'id_puc' => trim($dataPuc['idPuc']),
                'number_count' => trim($dataPuc['numberCount']),
                'count' => ucfirst(strtolower(trim($dataPuc['count'])))
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }
}
