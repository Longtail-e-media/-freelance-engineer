<?php

class jobs extends DatabaseObject
{

    protected static $table_name = "tbl_jobs";
    protected static $db_fields = array(
        'id',
        'client_id',
        'job_type',
        'slug',
        'currency',
        'project_status',
        'title',
        'budget_type',
        'exact_budget',
        'budget_range_high',
        'budget_range_low',
        'deadline_date',
        'content',
        'archive_date',
        'admin_rating',
        'reviewed_admin',
        'sortorder',
        'status'
    );

    public $id;
    public $client_id;
    public $job_type;
    public $project_status;
    public $slug;
    public $currency;
    public $title;
    public $budget_type;
    public $exact_budget;
    public $budget_range_high;
    public $budget_range_low;
    public $deadline_date;
    public $content;
    public $archive_date;
    public $admin_rating;
    public $reviewed_admin;
    public $sortorder;
    public $status;

    //Get Facility Ttle
    public static function getFacility()
    {
        global $db;
        $sql = "SELECT facility_title FROM " . self::$table_name . " WHERE status=1 ORDER BY sortorder ASC";
        return self::find_by_sql($sql);
    }

    public static function homepageArticle($limit = '')
    {
        global $db;
        $lmt = !empty($limit) ? ' LIMIT ' . $limit : '';
        $sql = "SELECT * FROM " . self::$table_name . " WHERE status='1' ORDER BY sortorder ASC $lmt";
        return self::find_by_sql($sql);
    }

    public static function get_relatedsub_by($client_id = 0, $sid = '', $limit = '')
    {
        global $db;
        $cond = !empty($sid) ? ' AND id<> ' . $sid : '';
        $cond2 = !empty($limit) ? ' LIMIT ' . $limit : '';
        $sql = "SELECT * FROM " . self::$table_name . " WHERE client_id=$client_id AND status=1 $cond ORDER BY sortorder DESC $cond2 ";
        return self::find_by_sql($sql);
    }

    //Find a single row in the database where slug is provided.
    public static function find_by_slug($slug = 0)
    {
        global $db;
        $sql = "SELECT * FROM " . self::$table_name . " WHERE slug='$slug' LIMIT 1";
        $result_array = self::find_by_sql($sql);
        return !empty($result_array) ? array_shift($result_array) : false;
    }


    // homepage package list
    public static function getPackage_limit($client_id = 0, $limit = '')
    {
        global $db;
        $cond = !empty($limit) ? ' LIMIT ' . $limit : '';
        $sql = "SELECT * FROM " . self::$table_name . " WHERE client_id=$client_id AND status=1 ORDER BY sortorder DESC $cond ";
        return self::find_by_sql($sql);
    }

    public static function getPackage_limits($client_id = 0, $limit = '', $id = 0)
    {
        global $db;
        $cond2 = !empty($id) ? ' AND id<>' . $id : '';
        $cond = !empty($limit) ? ' LIMIT ' . $limit : '';
        $sql = "SELECT * FROM " . self::$table_name . " WHERE client_id='$client_id' $cond2 AND status='1' ORDER BY sortorder DESC $cond ";
        return self::find_by_sql($sql);
    }

    public static function getTotalSub($client_id = '')
    {
        global $db;
        $cond = !empty($client_id) ? ' AND client_id=' . $client_id : '';
        $query = "SELECT COUNT(id) AS tot FROM " . self::$table_name . " WHERE status=1 $cond ";
        $sql = $db->query($query);
        $ret = $db->fetch_array($sql);
        return $ret['tot'];
    }

    public static function get_relatedpkg($client_id = '', $notid = '', $limit = '')
    {
        global $db;
        $cond = !empty($client_id) ? ' AND client_id=' . $client_id : '';
        $cond2 = !empty($notid) ? ' AND id<>' . $notid : '';
        $cond3 = !empty($limit) ? ' LIMIT ' . $limit : '';
        $sql = "SELECT * FROM " . self::$table_name . " WHERE status=1 $cond $cond2 ORDER BY sortorder DESC " . $cond3;
        return self::find_by_sql($sql);
    }

    public static function get_homepage_jobs($limit = '')
    {
        global $db;
        $cond = !empty($limit) ? ' LIMIT ' . $limit : '';
        $sql = "SELECT * FROM " . self::$table_name . " WHERE status=1 ORDER BY sortorder DESC " . $cond;
        return self::find_by_sql($sql);
    }

    //FIND THE HIGHEST MAX NUMBER BY PARENT ID.
    static function find_maximum_byparent($field = "sortorder", $pid = "")
    {
        global $db;
        $result = $db->query("SELECT MAX({$field}) AS maximum FROM " . self::$table_name . " WHERE client_id={$pid}");
        $return = $db->fetch_array($result);
        return ($return) ? ($return['maximum'] + 1) : 1;
    }

    //FIND THE HIGHEST MAX NUMBER.
    public static function find_maximum($field = "sortorder")
    {
        global $db;
        $result = $db->query("SELECT MAX({$field}) AS maximum FROM " . self::$table_name);
        $return = $db->fetch_array($result);
        return ($return) ? ($return['maximum'] + 1) : 1;
    }

    public static function find_completed_jobs($client_id = 0)
    {
        global $db;
        $result = $db->query("SELECT COUNT(id) AS total FROM " . self::$table_name . " WHERE project_status=6 AND client_id='$client_id'");
        $return = $db->fetch_array($result);
        return ($return) ? ($return['total']) : 0;
    }

    //Find all the rows in the current database table.
    public static function find_all()
    {
        global $db;
        return self::find_by_sql("SELECT * FROM " . self::$table_name . " ORDER BY sortorder ASC");
    }

    public static function getallPackage()
    {
        global $db;
        return self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE status=1 ORDER BY sortorder DESC");
    }

    public static function field_by_id($id = 0, $fields = "")
    {
        global $db;
        $sql = "SELECT $fields FROM " . self::$table_name . " WHERE id={$id} LIMIT 1";
        $result = $db->query($sql);
        $return = $db->fetch_array($result);
        return ($return) ? $return[$fields] : '';
    }

    //Find a single row in the database where id is provided.
    public static function find_by_id($id = 0)
    {
        global $db;
        $result_array = self::find_by_sql("SELECT * FROM " . self::$table_name . " WHERE id={$id} LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    //Find rows from the database provided the SQL statement.
    public static function find_by_sql($sql = "")
    {
        global $db;
        $result_set = $db->query($sql);
        $object_array = array();
        while ($row = $db->fetch_array($result_set)) {
            $object_array[] = self::instantiate($row);
        }
        return $object_array;
    }

    //Instantiate all the attributes of the Class.
    private static function instantiate($record)
    {
        $object = new self;
        foreach ($record as $attribute => $value) {
            if ($object->has_attribute($attribute)) {
                $object->$attribute = $value;
            }
        }
        return $object;
    }

    //Check if the attribute exists in the class.
    private function has_attribute($attribute)
    {
        $object_vars = $this->attributes();
        return array_key_exists($attribute, $object_vars);
    }

    //Return an array of attribute keys and thier values.
    protected function attributes()
    {
        $attributes = array();
        foreach (self::$db_fields as $field):
            if (property_exists($this, $field)) {
                $attributes[$field] = $this->$field;
            }
        endforeach;
        return $attributes;
    }

    //Prepare attributes for database.
    protected function sanitized_attributes()
    {
        global $db;
        $clean_attributes = array();
        foreach ($this->attributes() as $key => $value):
            $clean_attributes[$key] = $db->escape_value($value);
        endforeach;
        return $clean_attributes;
    }

    //Save the changes.
    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }

    //Add  New Row to the database
    public function create()
    {
        global $db;
        $attributes = $this->sanitized_attributes();
        $sql = "INSERT INTO " . self::$table_name . "(";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes));
        $sql .= "')";
        if ($db->query($sql)) {
            $this->id = $db->insert_id();
            return true;
        } else {
            return false;
        }
    }

    //Update a row in the database.
    public function update()
    {
        global $db;
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = array();

        foreach ($attributes as $key => $value):
            $attribute_pairs[] = "{$key}='{$value}'";
        endforeach;

        $sql = "UPDATE " . self::$table_name . " SET ";
        $sql .= join(", ", array_values($attribute_pairs));
        $sql .= " WHERE id=" . $db->escape_value($this->id);
        $db->query($sql);
        return ($db->affected_rows() == 1) ? true : false;
        //return true;
    }
}

?>