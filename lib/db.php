<?php

/*
 * toSelect($tableName,$fields,$tj)
 * 查询数据表，参数：
 * $tableName :要查询的数据表的名称
 * $fields : 要查询哪几个字段
 * $tj:查询条件
 * 返回：将查询的结果组装成XML后返回
 * 黄文军 2012/3/8
 */

function toSelect($tableName, $fields, $tj) {
    //return createXml(readdbByRs($tableName,$fields,$tj));
    return array_to_json(readdbByRs($tableName, $fields, $tj));
}

/*
 * toInsert($tableName,$fields,$values)
 * 将数据写入到表中
 * $tableName :要查询的数据表的名称
 * $fields : 要查询哪几个字段
 * $values:查询条件
 * 返回：失败返回错误信息
 * 			如果表内有自动增长的字段，则返回插入数据的这个新值。否则返回0
 * 黄文军 2012/3/8 * 2012/4/17 重写 
 */

function toInsert($tableName, $fields, $values) {
    include 'init.php';
    $vals = "";
    $filedsAry = explode(",", $fields);
    $valuesAry = explode(",", $values);
    for ($i = 0; $i < count($filedsAry); $i++) {
        $vals .= ":" . trim($filedsAry[$i]) . ",";
    }
    $vals = substr($vals, 0, -1);
    $sql = "insert into " . $tableName . "(" . $fields . ") values (" . $vals . ")";
    try {
        $rs = $conn->prepare($sql);
        for ($i = 0; $i < count($filedsAry); $i++) {
            $rs->bindValue(":" . $filedsAry[$i], $valuesAry[$i], PDO::PARAM_STR);
        }
        $ok = $rs->execute();
        $lastId = $conn->lastInsertId();
        $rs = null;
        if (!$ok) {
            return("Error insert ! ");
        } else {
            return $lastId;
        }
    } catch (Exception $e) {
        if ($e->getCode() == 23000)
            return "E_23000";
        return 'Error! 增加数据时出错: \n ' . $e->getMessage() . ' \n ' . $sql;
    }
}

/*
 * toUpdate($tableName,$fields,$values,$tj)
 * 更新数据表中的数据。可以同时更新多个字段。每个字段和值之间用逗号分隔开
 * $tableName :要查询的数据表的名称
 * $fields : 要查询哪几个字段
 * $values: 与字段对应的值
 * $tj :更新条件。为防止出错，条件不允许为空。如果无任何条件，则将它设为 "1=1"
 * 返回：失败返回错误信息，否则返回空
 * 黄文军 2012/3/8
 */

function toUpdate($tableName, $fields, $values, $tj) {
    include 'init.php';
    if (trim($tj) == "") {
        return "Error: 条件不允许为空";
    }
    $sql = "update $tableName set ";
    $fields = explode(",", $fields);
    $values = explode(",", $values);
    for ($i = 0; $i < count($fields); $i++) {
        $sql .= "$fields[$i]=:" . $fields[$i] . ",";
    }
    $sql = substr($sql, 0, -1);
    if (trim($tj) != "")
        $sql .= " where $tj";
    try {
        $rs = $conn->prepare($sql);
        for ($i = 0; $i < count($fields); $i++) {
            $rs->bindValue(":" . $fields[$i], $values[$i], PDO::PARAM_STR);
        }
        $rs->execute();
        $rs = null;
        return "ok";
    } catch (Exception $e) {
        if ($e->getCode() == 23000)
            return "Error_23000";
        return 'Error:更新数据时出错: \n ' . $e->getMessage() . ' \n ' . $sql;
    }
}

/*
 * toDelete($tableName,$tj)
 * 删除数据表中的数据。
 * $tableName :要查询的数据表的名称
 * $tj :条件。为防止出错，条件不允许为空。如果无任何条件，则将它设为 "1=1"
 * 返回：失败返回错误信息，否则返回空
 * 黄文军 2012/3/8
 */

function toDelete($tableName, $tj) {
    include 'init.php';
    if (trim($tj) == "") {
        return "Error: 条件不允许为空";
    }
    $sql = "delete from $tableName where $tj";
    try {
        $conn->exec($sql);
        return 'ok,删除成功！';
    } catch (Exception $e) {
        return 'Error:删除数据时出错:\n' . $e->getMessage() . '\n' . $sql;
    }
}

/*
 * toInsertOrUpdate($tableName,$fields,$values,$keyFieldName,$keyValue)
 * 更新或是插入数据。它会对$keyFieldName的值进行检查，如果存在则更新。否则插入。
 * 如果$keyValue的值为空，则直接插入数据 （2013/4/19修改）
 * $tableName :要查询的数据表的名称
 * $fields ：字段集，可以有多个字段，由逗号分隔开。
 * $values：值集，可以有多个字段，由逗号分隔开。
 * $keyFieldName ： 要查找的关键字段。
 * $keyValue：关键字段的值
 * 黄文军 2012/3/8
 */

function toInsertOrUpdate($tableName, $fields, $values, $keyFieldName, $keyValue) {
    if (strlen(trim($keyValue)) == 0) {
        return toInsert($tableName, $fields, $values);
    } else {
        $tj = $keyFieldName . "='" . $keyValue . "'";
        $rs = readdbByRs($tableName, $keyFieldName, $tj);
        $jls = count($rs);
        if ($jls > 0) {
            return toUpdate($tableName, $fields, $values, $tj);
        } else {
            return toInsert($tableName, $fields, $values);
        }
    }
}

/*
 * readdbByRs($tabName,$resuField='',$tj='')
 * 从数据表中读取数据
 * $tableName :要查询的数据表的名称
 * $fields : 要查询哪几个字段
 * $tj :条件
 * 返回：RS 数据集
 * 黄文军 2012/3/8
 */

function readdbByRs($tabName, $fields = '', $tj = '') {
    include 'init.php';
    $result = "";
    $fields = (empty($fields)) ? '*' : $fields;
    $tj = (empty($tj)) ? '' : ' where ' . $tj;
    $sql = 'select ' . $fields . ' from ' . $tabName . $tj;
    try {
        $rs = $conn->prepare($sql);
        $rs->execute();
        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
        $rs = null;
    } catch (Exception $e) {
        $rs = null;
        throw new Exception($e);
        return 'Error:' . $e;
    }
    return $result;
}

/**
 * 运行一条SQL语句,无返回数据
 * 黄文军 2012/4/19
 * @param str $_sql 要执行的SQL语句
 * @return 无
 */
function runSql($_sql) {
    include 'init.php';
    try {
        $rs = $conn->prepare($_sql);
        $rs->execute();
        $rs = null;
    } catch (Exception $e) {
        $rs = null;
        throw new Exception($e);
        return 'Error:' . $e;
    }
}

/**
 * 运行一条SQL语句,返回查询的数据结果
 * 黄文军 2012/4/19
 * @param str $_sql 要执行的SQL语句
 * @return 查询出来的数据集
 */
function execSql($_sql) {
    include 'init.php';
    $result = "";
    try {
        $rs = $conn->prepare($_sql);
        $rs->execute();
        $result = $rs->fetchAll(PDO::FETCH_ASSOC);
        $rs = null;
        return $result;
    } catch (Exception $e) {
        $rs = null;
        throw new Exception($e);
        return 'Error:' . $e;
    }
}

/*
 * $procName: 要执行的存储过程的名称
 * $Params  : 所有的参数名称,以","号分隔
 * $Values  : 参数对应的值,以","号分隔
 * $len     :参数的长度,以","号分隔
 * $needResult: 是否需要返回结果，用户需要保证执行结果有可返回的数据
 * 黄文军 2012/3/8 ,2012/4/19修改
 * */

function runProcedure($procName, $Params = '', $Values = "", $len = "", $needResult = 0) {
    include 'init.php';
    //对条件进行分解
    if (strpos($Params, ",") === false) {
        $arrParam = array($Params);
        $arrValues = array($Values);
        $arrLen = array($len);
    } else {
        $arrParam = split(",", $Params);
        $arrValues = split(",", $Values);
        $arrLen = split(",", $len);
    }
    $cs = "";
    $idx = 0;
    foreach ($arrParam as $f) {
        $cs .= ":" . $f . ",";
        $arrParam[$idx] = ":" . $arrParam[$idx];
        $idx++;
    }
    $cs = substr($cs, 0, -1);
    try {
        $rs = $conn->prepare("CALL $procName($cs)");
        $idx = 0;
        foreach ($arrParam as $f) {
            $rs->bindParam($arrParam[$idx], $arrValues[$idx], PDO::PARAM_STR, $arrLen[$idx]);
            $idx++;
        }
        $rs->execute();
        if ($needResult == 1) {
            $result = $rs->fetchAll(PDO::FETCH_ASSOC);
            $rs = null;
            return $result;
        }
    } catch (Exception $e) {
        $rs->close();
        throw new Exception($e);
        return 'error';
    }
}

/*
 * 将数组转换成JSON 格式
 */

function array_to_json($array) {
    if (!is_array($array)) {
        return false;
    }
    $associative = count(array_diff(array_keys($array), array_keys(array_keys($array))));
    if ($associative) {
        $construct = array();
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $key = "key_$key";
            }
            $key = "'" . addslashes($key) . "'";
            if (is_array($value)) {
                $value = array_to_json($value);
            } else if (!is_numeric($value) || is_string($value)) {
                $value = "'" . preg_replace('/\s+/', '', addslashes(trim($value))) . "'";
            }
            $construct[] = "$key: $value";
        }
        $result = "{ " . implode(", ", $construct) . " }";
    } else { // If the array is a vector (not associative):
        $construct = array();
        foreach ($array as $value) {
            if (is_array($value)) {
                $value = array_to_json($value);
            } else if (!is_numeric($value) || is_string($value)) {
                $value = "'" . preg_replace('/\s+/', '', addslashes(trim($value))) . "'";
            }
            $construct[] = $value;
        }
        $result = "[ " . implode(", ", $construct) . " ]";
    }
    return $result;
}

?>