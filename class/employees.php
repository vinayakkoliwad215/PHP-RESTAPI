<?php

class Employees
{

    // Connection
    private $con;

    // Db connection
    public function __construct($db)
    {
        $this->con = $db;
    }

    // GET ALL
    public function fetchAllData($table)
    {

        $query  = "SELECT * FROM  $table";
        
        $result = $this->con->query($query);
        
        if ($result) {
            
            if ($result->num_rows > 0) {
                
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                
                $data = [
                    'status' => 200,
                    'message' => 'Employee record fetch successfully',
                    'data'  => $rows,
                ];
                
                header("HTTP/1.0 20 OK");
            }else{
                $data = [
                    'status' => 400,
                    'message' => 'No Employee found',
                ];
                header("HTTP/1.0 400 No Employee found");
            }
        }else{
            $data = [
                'status' => 500,
                'message' => 'Internal server error',
            ];
            header("HTTP/1.0 500 Internal server error");
        }
        return json_encode($data);
    }


    public function selectDataById($table, $id)
    {
        try {
            if (!empty($id)) {
                $stmt   = "SELECT * FROM $table Where id = '$id'";
                $result = $this->con->query($stmt);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_all(MYSQLI_ASSOC);
                    $data = [
                        'status' => 200,
                        'message' => 'Single record fetch successfully',
                        'data'  => $row,
                    ];
                    header("HTTP/1.0 200 OK");
                } else {
                    $data = [
                        'status' => 404,
                        'message' => 'No Employee found',
                    ];
                    header("HTTP/1.0 404 No Employee found");
                }
            }else{
                $data = [
                    'status' => 404,
                    'message' => 'Employee Id is required',
                ];
                header("HTTP/1.0 404 Employee Id is required");  
            }
            return json_encode($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }



    public function insertEmpyoyeeData($table, $post)
    {

        if (!empty($post)) {

            $emp_id     = $post['emp_id'];
            $full_name  = $post['full_name'];
            $job_title  = $post['job_title'];
            $department = $post['department'];
            $gender     = $post['gender'];
            $age        = $post['age'];
            $hire_date  = date('Y-m-d', strtotime($post['hire_date']));
            $annual_salary = $post['annual_salary'];
            $bonus      = $post['bonus'];
            $city       = $post['city'];

            if (!empty($emp_id) && !empty($full_name) && !empty($job_title) && !empty($department) && !empty($gender) && !empty($age) && !empty($hire_date) && !empty($city)) {

                $query = "INSERT INTO $table (emp_id, full_name, job_title, department, gender, age, hire_date, annual_salary, bonus, city) VALUES('$emp_id', '$full_name', '$job_title', '$department', '$gender', '$age', '$hire_date', '$annual_salary', '$bonus', '$city')";

                $result = $this->con->query($query);

                if ($result) {
                    $data = [
                        'status' => 200,
                        'message' => 'Employee created successfully.',
                    ];
                    header("HTTP/1.0 200 created");
                } else {
                    $data = [
                        'status' => 500,
                        'message' => 'Internal server error',
                    ];
                    header("HTTP/1.0 500 Internal server error");
                }
            }else{
                $data = [
                    'status' => 422,
                    'message' => 'All fields are required',
                ];
                header("HTTP/1.0 404 unprocessable entity");
            }
            
        }else{
            $data = [
                'status' => 500,
                'message' => 'Something went wrong',
            ];
            header("HTTP/1.0 404 Something went wrong");
        }
        return json_encode($data); 
    }
    public function deleteEmployeeId($table, $id)
    {
        try {
            if (!empty($id)) {
                $query  = "DELETE FROM $table WHERE id = '$id' LIMIT 1";
                $result = $this->con->query($query);
                if ($result) {
                    $data = [
                        'status' => 200,
                        'message' => 'Record deleted successfully',
                    ];
                    header("HTTP/1.0 200 OK");
                } else {
                    $data = [
                        'status' => 500,
                        'message' => 'Internal server error',
                    ];
                    header("HTTP/1.0 500 Internal server error");
                }
            } else {
                $data = [
                    'status' => 404,
                    'message' => 'Employee Id is required',
                ];
                header("HTTP/1.0 404 Not found");
            }
            return json_encode($data);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function updateEmpyoyeeData($table, $post, $getId){

        if (!empty($post)) {

            if (isset($getId) && !empty($getId)) {

                $id         = $getId['id'];

                $emp_id     = $post['emp_id'];
                $full_name  = $post['full_name'];
                $job_title  = $post['job_title'];
                $department = $post['department'];
                $gender     = $post['gender'];
                $age        = $post['age'];
                $hire_date  = date('Y-m-d', strtotime($post['hire_date']));
                $annual_salary = $post['annual_salary'];
                $bonus      = $post['bonus'];
                $city       = $post['city'];
                
                $query="UPDATE $table SET emp_id='$emp_id', full_name='$full_name', job_title='$job_title', department='$department',
                gender='$gender', age='$age', hire_date='$hire_date', annual_salary='$annual_salary', bonus='$bonus', city='$city' WHERE id='$id'";
          
                $result = $this->con->query($query);

                if ($result) {
                    $data = [
                        'status' => 200,
                        'message' => 'Employee updated successfully.',
                    ];
                    header("HTTP/1.0 200 success");
                } else {
                    $data = [
                        'status' => 404,
                        'message' => 'Employee not updated',
                    ];
                    header("HTTP/1.0 404 Employee not updated");
                }
            }else{
                $data = [
                    'status' => 404,
                    'message' => 'Employee Id is not found',
                ];
                header("HTTP/1.0 404 Not found");
            }
        } else {
            $data = [
                'status' => 404,
                'message' => 'Something went wrong',
            ];
            header("HTTP/1.0 404 Something went wrong");
        }
        return json_encode($data); 
    }
}
?>