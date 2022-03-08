<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class CompanyDao
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function findDataCompanyByCompany($id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM companies WHERE id_company = :id_company;");
    $stmt->execute(['id_company' => $id_company]);

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

    $company = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("company", array('company' => $company));
    return $company;
  }

  public function insertCompany($dataCompany)
  {
    $connection = Connection::getInstance()->getConnection();
    try {
      $stmt = $connection->prepare("INSERT INTO companies (name_commercial, company, state, city, country, address, telephone, nit, comision_ventas, margen_rentabilidad,
                                                horas_trabajo, dia_mes, logo, gastos_totales_mes, inicio_licencia, expiracion_licencia, licencia_activa, 
                                                productos_licenciados, gastos_especificos, creador)
                                    VALUES (:name_commercial, :company, :state, :city, :country, :address, :telephone, :nit, :comision_ventas, :margen_rentabilidad,
                                                :horas_trabajo, :dia_mes, :logo, :gastos_totales_mes, :inicio_licencia, :expiracion_licencia, :licencia_activa, 
                                                :productos_licenciados, :gastos_especificos, :creador)");
      $stmt->execute([
        'name_commercial' => ucfirst(strtolower($dataCompany['nameCommercial'])),   'inicio_licencia' => $dataCompany['inicioLicencia'],
        'company' => ucfirst(strtolower($dataCompany['company'])),                  'expiracion_licencia' => $dataCompany['expiracionLicencia'],
        'state' => ucfirst(strtolower($dataCompany['state'])),                      'dia_mes' => $dataCompany['diaMes'],
        'country' => ucfirst(strtolower($dataCompany['country'])),                  'logo' => $dataCompany['logo'],
        'city' => ucfirst(strtolower($dataCompany['city'])),                        'gastos_totales_mes' => $dataCompany['gastosTotalesMes'],
        'address' => ucfirst(strtolower($dataCompany['address'])),                  'licencia_activa' => 1,
        'telephone' => $dataCompany['telephone'],                                   'productos_licenciados' => $dataCompany['productosLicenciados'],
        'nit' => $dataCompany['nit'],                                               'gastos_especificos' => $dataCompany['gastosEspecificos'],
        'comision_ventas' => $dataCompany['comisionVentas'],                        'creador' => $dataCompany['creador'],
        'margen_rentabilidad' => $dataCompany['margenRentabilidad'],
        'horas_trabajo' => $dataCompany['horasTrabajo']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 1;
    } catch (\Exception $e) {
      $message = $e->getMessage();
      if ($e->getCode() == 23000)
        $message = 'Nit duplicado. Ingrese una nuevo Nit';

      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function updateCompany($dataCompany)
  {
    $connection = Connection::getInstance()->getConnection();
    try {
      $stmt = $connection->prepare("UPDATE companies SET name_commercial = :name_commercial, company = :company, state = :state, city = :city, country = :country, address = :address,
                                            telephone = :telephone, nit = :nit, comision_ventas = :comision_ventas, margen_rentabilidad = :margen_rentabilidad, horas_trabajo = :horas_trabajo, dia_mes = :dia_mes,
                                            logo = :logo, gastos_totales_mes = :gastos_totales_mes, licencia_activa = :licencia_activa, inicio_licencia = :inicio_licencia, expiracion_licencia = :expiracion_licencia,
                                            productos_licenciados = :productos_licenciados, gastos_especificos = :gastos_especificos, creador = :creador
                                    WHERE id_company = :id_company");
      $stmt->execute([
        'name_commercial' => ucfirst(strtolower($dataCompany['nameCommercial'])),   'inicio_licencia' => $dataCompany['inicioLicencia'],
        'company' => ucfirst(strtolower($dataCompany['company'])),                  'expiracion_licencia' => $dataCompany['expiracionLicencia'],
        'state' => ucfirst(strtolower($dataCompany['state'])),                      'dia_mes' => $dataCompany['diaMes'],
        'country' => ucfirst(strtolower($dataCompany['country'])),                  'logo' => $dataCompany['logo'],
        'city' => ucfirst(strtolower($dataCompany['city'])),                        'gastos_totales_mes' => $dataCompany['gastosTotalesMes'],
        'address' => ucfirst(strtolower($dataCompany['address'])),                  'licencia_activa' => 1,
        'telephone' => $dataCompany['telephone'],                                   'productos_licenciados' => $dataCompany['productosLicenciados'],
        'nit' => $dataCompany['nit'],                                               'gastos_especificos' => $dataCompany['gastosEspecificos'],
        'comision_ventas' => $dataCompany['comisionVentas'],                        'creador' => $dataCompany['creador'],
        'margen_rentabilidad' => $dataCompany['margenRentabilidad'],
        'horas_trabajo' => $dataCompany['horasTrabajo'],
        'id_company' => $dataCompany['id_company']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 2;
    } catch (\Exception $e) {
      $message = $e->getMessage();
      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function deleteCompany($id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT * FROM companies WHERE id_company = :id_company");
    $stmt->execute(['id_company' => $id_company]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      $stmt = $connection->prepare("DELETE FROM companies WHERE id_company = :id_company");
      $stmt->execute(['id_company' => $id_company]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }
}
