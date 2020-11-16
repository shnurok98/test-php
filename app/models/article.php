<?php
namespace Models;

use Core\Db;

class Article {

    private $conn;
    private $tableName = "articles";
    private $limit = 10;
    private $offset = 0;

    public $id;
    public $href;
    public $title;
    public $body;
    public $description;
    public $product;
    public $views;
    public $time_create;

    // Конструктор для соединения с базой данных 
    public function __construct(){
        $db = new Db();
        $this->conn = $db->getconnection();
    }

    /**
     * Создание статьи
     */
    public function create(array $data) {
        $q = "INSERT INTO `$this->tableName` (`href`, `title`, `body`, `description`, `product`, `views`, `time_create`) 
                VALUES (:href, :title, :body, :description, :product, :views, :time_create);";
        $q = $this->conn->prepare($q);

        $q->bindParam(":href", $data['href']);
        $q->bindParam(":title", $data['title']);
        $q->bindParam(":body", $data['body']);
        $q->bindParam(":description", $data['description']);
        $q->bindParam(":product", $data['product']);
        $q->bindParam(":views", $data['views']);
        $q->bindParam(":time_create", $data['time_create']);

        if ($q->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Возвращает список статей
     * $params - ассоциативный массив с параметрами из uri
     */
    public function getAll(array $params = NULL) {

        $q = "SELECT * FROM `$this->tableName` "; // ORDER BY `time_create` DESC LIMIT $this->limit;";

        $views = '';
        $time_create = '';
        $href = '';

        if ($params) {
            // print_r($params);
            foreach ($params as $key => $val) {
                switch ($key) {
                    case 'views':
                        $op = substr($val, 0, 2);
                        $zn = substr($val, 2);
                        if ($op == 'eq') $views = '`views` =' . $zn;
                        if ($op == 'gt') $views = '`views` >' . $zn;
                        if ($op == 'lt') $views = '`views` <' . $zn;
                        $views .= ' AND ';
                        // echo 'views:  ' . $views;
                        break;
                    case 'time_create':
                        // 86 400 000 - один день в timestamp
                        $date = (int) $val;
                        $dateEnd = $date + 86400000;
                        $time_create = " `time_create` > $date AND `time_create` < $dateEnd AND ";

                        break;
                    case 'href':
                        $q .= ', `products` ';
                        $href = " articles.product = products.name AND products.href = '" . $val . "' AND ";
                        break;
                    case 'limit':
                        $this->limit = (int) $val;
                        break;
                    case 'offset':
                        $this->offset = (int) $val;
                        break;    
                    default:
                        // return FALSE;
                        break;
                }
            }

            if ( ($views != '') || ($time_create != '') || ($href != '')) {
                $qPrm = ' WHERE ' . $views . $time_create . $href;
                $qPrm = substr($qPrm, 0, -4);
            }
        }
        
        $q .= "$qPrm ORDER BY `time_create` DESC LIMIT $this->limit OFFSET $this->offset;";
        
        // echo 'SQL QUERY: ' . $q;
        $res = $this->conn->query($q);
        // $res->execute();
        if ($res !== FALSE) {
            $res = $res->fetchAll();
            return $res;
        } else {
            $this->$conn->errorInfo();
        }
        
        // print_r($res);
    }

    /**
     * Возвращает статью по id
     */
    public function getById(int $id) {
        // Получаем статью и сразу же инкремент показов
        $q = "SELECT * FROM `$this->tableName` WHERE `id` = $id; UPDATE `$this->tableName` SET `views` = `views` + 1 WHERE `id` = $id;";

        $res = $this->conn->query($q);

        if ($res !== FALSE) {
            $res = $res->fetchAll();
            return $res;
        } else {
            $this->$conn->errorInfo();
        }
    }
    
}

