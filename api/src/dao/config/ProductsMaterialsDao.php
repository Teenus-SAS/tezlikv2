<?php

namespace tezlikv2\dao;

use tezlikv2\Constants\Constants;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class ProductsMaterialsDao
{
    private $logger;

    public function __construct()
    {
        $this->logger = new Logger(self::class);
        $this->logger->pushHandler(new RotatingFileHandler(Constants::LOGS_PATH . 'querys.log', 20, Logger::DEBUG));
    }

    public function productsmaterials($idProduct, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();
        $stmt = $connection->prepare("SELECT  pm.id_product_material, m.id_material, m.reference, m.material, m.unit, pm.quantity, m.cost FROM products p 
                                    INNER JOIN products_materials pm ON pm.id_product = p.id_product 
                                    INNER JOIN materials m ON m.id_material = pm.id_material 
                                  WHERE pm.id_product = :id_product AND pm.id_company = :id_company");
        $stmt->execute(['id_product' => $idProduct, 'id_company' => $id_company]);
        $productsmaterials = $stmt->fetchAll($connection::FETCH_ASSOC);
        $this->logger->notice("products", array('products' => $productsmaterials));
        return $productsmaterials;
    }

    // Consultar si existe el product_material en la BD
    public function findAExistingProductMaterial($dataProductMaterial)
    {
        // Obtener id del producto
        $product = new ProductsDao();
        $findProduct = $product->findAExistingProduct($dataProductMaterial['referenceProduct']);

        // Obtener id de la materia prima
        $material = new MaterialsDao();
        $findMaterial = $material->findAExistingRawMaterial($dataProductMaterial['refRawMaterial']);

        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT id_product_material FROM products_materials
                                      WHERE id_product = :id_product AND id_material = :id_material");
        $stmt->execute([
            'id_product' => $findProduct['id_product'],
            'id_material' => $findMaterial['id_material']
        ]);
        $findProductMaterial = $stmt->fetch($connection::FETCH_ASSOC);

        if ($findProductMaterial == false) {
            $dataFindProductMaterial = array_merge($findProduct, $findMaterial);
            return $dataFindProductMaterial;
        } else {
            $dataFindProductMaterial = array_merge($findProductMaterial, $findProduct, $findMaterial);
            return $dataFindProductMaterial;
        }
    }

    // Insertar productos materia prima general
    public function generalInsertProductsMaterials($dataProductMaterial, $idMaterial, $idProduct, $id_company)
    {
        $connection = Connection::getInstance()->getConnection();
        try {
            $stmt = $connection->prepare("INSERT INTO products_materials (id_material, id_company, id_product, quantity)
                                    VALUES (:id_material, :id_company, :id_product, :quantity)");
            $stmt->execute([
                'id_material' => $idMaterial,
                'id_company' => $id_company,
                'id_product' => $idProduct,
                'quantity' => $dataProductMaterial['quantity']
            ]);

            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    // Actualizar productos materia prima general
    public function generalUpdateProductsMaterials($dataProductMaterial, $idProductMaterial, $idMaterial, $idProduct)
    {
        $connection = Connection::getInstance()->getConnection();

        try {
            $stmt = $connection->prepare("UPDATE products_materials SET id_material = :id_material, id_product = :id_product, quantity = :quantity
                                    WHERE id_product_material = :id_product_material");
            $stmt->execute([
                'id_product_material' => $idProductMaterial,
                'id_material' => $idMaterial,
                'id_product' => $idProduct,
                'quantity' => $dataProductMaterial['quantity']
            ]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        } catch (\Exception $e) {
            $message = $e->getMessage();

            $error = array('info' => true, 'message' => $message);
            return $error;
        }
    }

    public function insertProductsMaterialsByCompany($dataProductMaterial, $id_company)
    {
        $this->generalInsertProductsMaterials($dataProductMaterial, $dataProductMaterial['material'], $dataProductMaterial['idProduct'], $id_company);
    }

    public function updateProductsMaterials($dataProductMaterial)
    {
        $this->generalUpdateProductsMaterials($dataProductMaterial, $dataProductMaterial['idProductMaterial'], $dataProductMaterial['material'], $dataProductMaterial['idProduct']);
    }

    // Insertar o Actualizar productos materias prima importados
    public function insertOrUpdateProductsMaterials($dataProductMaterial, $id_company)
    {
        $dataFindProductMaterial = $this->findAExistingProductMaterial($dataProductMaterial);

        if (empty($dataFindProductMaterial['id_product_material'])) {
            // Insertar
            $this->generalInsertProductsMaterials($dataProductMaterial, $dataFindProductMaterial['id_material'], $dataFindProductMaterial['id_product'], $id_company);
        } else
            // Actualizar
            $this->generalUpdateProductsMaterials($dataProductMaterial, $dataFindProductMaterial['id_product_material'], $dataFindProductMaterial['id_material'], $dataFindProductMaterial['id_product']);
    }

    public function deleteProductMaterial($dataProductMaterial)
    {
        $connection = Connection::getInstance()->getConnection();

        $stmt = $connection->prepare("SELECT * FROM products_materials WHERE id_product_material = :id_product_material");
        $stmt->execute(['id_product_material' => $dataProductMaterial['idProductMaterial']]);
        $rows = $stmt->rowCount();

        if ($rows > 0) {
            $stmt = $connection->prepare("DELETE FROM products_materials WHERE id_product_material = :id_product_material");
            $stmt->execute(['id_product_material' => $dataProductMaterial['idProductMaterial']]);
            $this->logger->info(__FUNCTION__, array('query' => $stmt->queryString, 'errors' => $stmt->errorInfo()));
        }
    }
}
