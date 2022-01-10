<?php

/*
 *      Author : Muhammad Shahab Ul Hassan
 *      Nick   : PrinceLord Prime
 *      Date   : 11 August 2018
 *      Time   : 03:37 PM Pakistan Standard Time
 *
 *      Project Name : Tajweed Quran
 */


class Igniter_controller extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('main_model');
    }

    public function ignite(){

        //return $this->load->view('coming_soon.php');
        return $this->load->view('home.php');
    }

    public function page($page){

        switch ($page){
            case 'login':
                $this->login('Login');
                break;
            case 'logout':
                $this->logout();
                break;
            case 'verification':
                $this->verification();
                break;
            case 'registration':
                $this->sign_up('Registration');
                break;
            case 'home':
                $this->ignite();
                break;
            case 'dashboard':
                $_SESSION['title'] = 'Dash Board';
                $this->dashboard($_SESSION);
                break;
            case 'attendance':
                $_SESSION['title'] = 'Attendance';
                $this->attendance($_SESSION);
                break;
            case 'daily':
                $_SESSION['title'] = 'Daily';
                $this->daily($_SESSION);
                break;

            default:
                $this->error();
        }
    }

    public function error(){
        return $this->load->view('Error404.php');
    }

    public function logout(){
        session_destroy();
        session_unset();
        redirect('../page/login');
    }

    public function login($title = ''){
        $this->load->view('master_layout/header.php',['title' => $title]);
        $this->load->view('login/login.php');
        $this->load->view('master_layout/footer.php');
    }

    public function verification(){
        //print_r($this->input->post());
        $posted_data = $this->input->post();
        $user_authentication = $this->main_model->get_user($posted_data['email'],$posted_data['password']);
        if(!empty($user_authentication)){
            $_SESSION['username'] = $user_authentication['user_id'];
            $_SESSION['display_name'] = $user_authentication['name'];
            $_SESSION['profile_pic'] = $user_authentication['user_id'].'.jpg';
            $_SESSION['email'] = $user_authentication['email'];

            redirect('../page/dashboard');
        } else {
            echo "<br>Invalid User";
        }

    }

    public function sign_up($title = ''){
        $this->load->view('master_layout/header.php',['title' => $title]);
        $this->load->view('login/registration.php');
        $this->load->view('master_layout/footer.php');
    }

    public function dashboard($data){
        $this->load->view('master_layout/dash_header.php',$data);
        $this->load->view('master_layout/dash_menu.php',['main_menu' => $this->main_model->dash_main_menu()]);
        $this->load->view('master_layout/dash_footer.php');
    }

    public function attendance($data){
        $this->load->view('master_layout/dash_header.php',$data);
        $this->load->view('master_layout/dash_menu.php',['main_menu' => $this->main_model->dash_main_menu()]);
        $this->load->view('attendance.php',['attendance_result' => $this->main_model->get_attendance('',true)]);
        $this->load->view('master_layout/dash_footer.php');
    }

    public function daily($data){
        $this->load->view('master_layout/dash_header.php',$data);
        $this->load->view('master_layout/dash_menu.php',['main_menu' => $this->main_model->dash_main_menu()]);
        $this->load->view('attendance.php',['attendance_result' => $this->main_model->get_attendance('',true)]);
        $this->load->view('master_layout/dash_footer.php');
    }

    public function devtest(){
        print_r($_COOKIE);
        //print_r($this->main_model->get_user('theprincelordprime@gmail.com','prince123'));
        //$this->login('Login');
        //$this->sign_up('Registration');
        //echo 'Dev Testing';
        //$this->main_model->test_model();
        //$this->main_model->get_attendance('princelord');
        //print_r($this->main_model->dash_main_menu());
    }


}