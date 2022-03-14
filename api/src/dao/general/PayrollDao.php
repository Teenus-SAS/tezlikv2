<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class PayrollDao
{
  private $logger;

  public function __construct()
  {
    $this->logger = new Logger(self::class);
    $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
  }

  public function findAllPayrollByCompany($id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $stmt = $connection->prepare("SELECT p.id_payroll, p.id_company, p.employee, p.salary, p.transport, p.extra_time, p.bonification, p.endowment, p.working_days_month, p.hours_day, p.factor_benefit, p.salary_net, p.type_contract, p.minute_value, pp.process 
                                  FROM payroll p 
                                  INNER JOIN process pp ON p.id_process = pp.id_process
                                  WHERE p.id_company = :id_company;");
    $stmt->execute(['id_company' => $id_company]);

    $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));

    $payroll = $stmt->fetchAll($connection::FETCH_ASSOC);
    $this->logger->notice("payroll", array('payroll' => $payroll));
    return $payroll;
  }

  public function insertPayrollByCompany($dataPayroll, $id_company)
  {
    $connection = Connection::getInstance()->getConnection();
    $salaryBasic = str_replace('.', '', $dataPayroll['basicSalary']);

    $payrollCalculate = $this->calculateValueMinute($salaryBasic, $dataPayroll);

    try {
      $stmt = $connection->prepare("INSERT INTO payroll (id_company, id_process, employee, salary, transport, extra_time, bonification, endowment,
                                                        working_days_month, hours_day, factor_benefit, salary_net, type_contract, minute_value)
                                    VALUES (:id_company, :id_process, :employee, :salary, :transport, :extra_time, :bonification, :endowment,
                                            :working_days_month, :hours_day, :factor_benefit, :salary_net, :type_contract, :minute_value)");
      $stmt->execute([
        'id_company' => $id_company,                  'employee' => ucfirst(strtolower($dataPayroll['employee'])),
        'id_process' => $dataPayroll['idProcess'],                'salary' => $salaryBasic,
        'transport' => $dataPayroll['transport'],                 'extra_time' => $dataPayroll['extraTime'],
        'bonification' => $dataPayroll['bonification'],           'endowment' => $dataPayroll['endowment'],
        'working_days_month' => $dataPayroll['workingDaysMonth'], 'hours_day' => $dataPayroll['workingHoursDay'],
        'factor_benefit' => $dataPayroll['factor'],               'type_contract' => $dataPayroll['typeFactor'],
        'salary_net' => $payrollCalculate['salaryNet'],           'minute_value' => $payrollCalculate['minuteValue'],
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 1;
    } catch (\Exception $e) {
      $message = $e->getMessage();
      if ($e->getCode() == 23000)
        $message = 'Registro duplicada. Ingrese una nuevo Registro';
      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function updatePayroll($dataPayroll)
  {
    $connection = Connection::getInstance()->getConnection();

    $payrollCalculate = $this->calculateValueMinute($dataPayroll['basicSalary'], $dataPayroll);

    try {
      $stmt = $connection->prepare("UPDATE payroll SET employee=:employee, id_process=:id_process, salary=:salary, transport=:transport, extra_time=:extra_time,
                                            bonification=:bonification, endowment=:endowment, working_days_month=:working_days_month,
                                            hours_day=:hours_day, factor_benefit=:factor_benefit, salary_net= :salary_net, type_contract=:type_contract, minute_value=:minute_value
                                    WHERE id_payroll = :id_payroll");
      $stmt->execute([
        'id_payroll' => $dataPayroll['idPayroll'],                'employee' => ucfirst(strtolower($dataPayroll['employee'])),
        'id_process' => $dataPayroll['idProcess'],                'salary' => $dataPayroll['basicSalary'],
        'transport' => $dataPayroll['transport'],                 'extra_time' => $dataPayroll['extraTime'],
        'bonification' => $dataPayroll['bonification'],           'endowment' => $dataPayroll['endowment'],
        'working_days_month' => $dataPayroll['workingDaysMonth'], 'hours_day' => $dataPayroll['workingHoursDay'],
        'factor_benefit' => $dataPayroll['factor'],                 'salary_net' => $payrollCalculate['salaryNet'],
        'type_contract' => $dataPayroll['typeFactor'],                   'minute_value' => $payrollCalculate['minuteValue']
      ]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
      return 2;
    } catch (\Exception $e) {
      $message = $e->getMessage();
      $error = array('info' => true, 'message' => $message);
      return $error;
    }
  }

  public function deletePayroll($id_payroll)
  {
    $connection = Connection::getInstance()->getConnection();

    $stmt = $connection->prepare("SELECT * FROM payroll WHERE id_payroll = :id_payroll");
    $stmt->execute(['id_payroll' => $id_payroll]);
    $rows = $stmt->rowCount();

    if ($rows > 0) {
      $stmt = $connection->prepare("DELETE FROM payroll WHERE id_payroll = :id_payroll");
      $stmt->execute(['id_payroll' => $id_payroll]);
      $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
    }
  }

  public function calculateValueMinute($salaryBasic, $dataPayroll)
  {
    /* Calcular salario neto */
    $salaryNet = intval($salaryBasic) * (1 + (intval($dataPayroll['factor']) / 100)) + intval($dataPayroll['bonification']) + intval($dataPayroll['endowment']);

    /* Total horas */
    $totalHoursMonth = intval($dataPayroll['workingDaysMonth']) * intval($dataPayroll['workingHoursDay']);
    $hourCost = $salaryNet / $totalHoursMonth;

    /* Calcular valor minuto salario */
    $minuteValue =  $hourCost / 60;

    /* retorna los valores calculados */
    $payrollCalculate = array('salaryNet' => $salaryNet, 'minuteValue' => $minuteValue);
    return $payrollCalculate;
  }
}
