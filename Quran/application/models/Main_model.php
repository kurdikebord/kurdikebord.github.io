<?php

/*
 *      Author : Muhammad Shahab Ul Hassan
 *      Nick   : PrinceLord Prime
 *      Date   : 11 August 2018
 *      Time   : 03:37 PM Pakistan Standard Time
 *
 *      Project Name : Tajweed Quran
 */


class Main_model extends CI_Model{

    private $table_user_access = 'user_access';
    private $table_attendance = 'attendance';

    function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
    }

    public function test_model(){
        echo 'Testing Model';
    }

    public function dash_main_menu(){
        return [
                'Dashboard' => [
                                    'link' => 'dashboard',
                                    'icon' => 'pie-chart',
                                ],
                'Attendance' => [
                                    'link' => 'attendance',
                                    'icon' => 'table',
                                ],
                'Reports' => [
                                'icon' => 'bar-chart',
                                'Daily' => 'daily',
                                'Weekly' => 'weekly',
                                'Monthly' => 'monthly',
                             ],
                ];
    }

    public function get_attendance($user_id,$admin = false){
        $query = '';
        if($admin){
            $query = $this->db->get('attendance');
        } else {
            $query = $this->db->get_where('attendance',['user_id' => $user_id]);
        }
        $result = $query->result_array();
        return $result;
    }

    public function get_user($email,$password){
        $query = $this->db->get_where($this->table_user_access,['email' => $email,'password' => $password],1);
        $result = $query->result_array();
        if(isset($result[0]))
        {
            return $result[0];
        } else {
            return $result;
        }
    }




}