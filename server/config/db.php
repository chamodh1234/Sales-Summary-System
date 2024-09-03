<?php
class db
{

    public static $dbconn;
    public static function sqlconn()
    {
        if (!db::$dbconn) {
            db::$dbconn = new mysqli('localhost', 'root', '#samaranayake123#', 'amddb', '3306');
        }
    }

   public static function crud($query)
    {
        db::sqlconn();
        $result = db::$dbconn->query($query);
        return $result;
    }

    public static function get(){
        db::sqlconn();
        return db::$dbconn;
    }
}
?>