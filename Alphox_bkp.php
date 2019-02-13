<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Alphox_bkp
*
* Author: Alphox
*		  
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  07.02.2019
*
* Description:  Codeigniter library to perform database backups automatically by saving the backup file on the server and / or sending it to the administrator via email.
*
* 
*
*/

class Alphox_bkp
{

/**
	 *
	 * backup folder
	 **/
	public $db_backup_path = 'backups/databases/'; //directory which will hold the backups
/**
	 *
	 *
	 **/	
	public $day_bkp = 5; //days of backup interval; 0 = at each login o visit 
/**
	 *
	 * 
	 **/	
	public $n_db = 3; //max number of files backup to store 
/**
     *
     * 
     **/    
    public $send_to_mail = TRUE ; //enable sending by email . If TRUE remeber to set email parameter

    public  $from_email = 'email@email.com'; // sender email 
    public  $name_email = 'Alphox_bkp'; // sender name
    public  $to_email = 'email@email.com'; 
    public  $subject = 'Backup using Alphox_bkp';

        // if protocol is smtp
    public $config = array( 
            'smtp_host' => 'ssl://smtps.etc-etc-etc', 
            'smtp_user' => 'xxxx@yyyyy.it' , 
            'smtp_pass' => 'password',  
            'smtp_port' => 'xxx',  
            'smtp_timeout' => '5', 
            'protocol' => 'smtp', 
            'mailtype' => 'html',
            'charset' => 'UTF-8',
                                );
        // if protocol is mail
    /*public $config = array( 
             
            'protocol' => 'mail', 
            'mailtype' => 'html',
            'charset' => 'UTF-8',
                                );*/

public function __construct()
	{
		$CI =& get_instance();
	$CI->load->helper('file', 'text', 'form','string');
    $CI->load->dbutil();
    $CI->load->library('zip');
    $CI->load->library('email');
    $CI->load->dbutil();
    $CI->load->library('zip');

     }  

public function backup()
    {
       
       
       $CI =& get_instance();
        $file_name2 = '_db';
        $file_name1 = date("d_m_Y_H_i_s");

        $date_ref = date("Y-m-d H:i:s", strtotime("-".$this->day_bkp." day"));
        $CI->db->where('created_date >', $date_ref);
        $CI->db->order_by('created_date', 'DESC');
        $CI->db->limit(1);
        $row = $CI->db->get('backup')->row();
        
        if (!($row)) {

        $file_name = $file_name1 . $file_name2 . '.zip';
        $prefs = array(
        'ignore' => array('backups'),
        'format' => 'zip', // gzip, zip, txt
        'filename' => $file_name, // File name - NEEDED ONLY WITH ZIP FILES
        'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
        'add_insert' => TRUE, // Whether to add INSERT data to backup file
        'newline' => "\n" // Newline character used in backup file
        );
        //Backup your entire database 
        $CI->load->dbutil();
        $backup = $CI->dbutil->backup($prefs);
        $file = $this->db_backup_path . $file_name;

        if (write_file($file, $backup)) {
        

        $data = array(
        'backup_name' => $file_name,
        'backup_location' => $this->db_backup_path,
        'created_date' => date('Y-m-d H:i:s')
                        );
        
        $CI->db->insert('backup', $data);

        // send backup via email to administrator 
        if ($this->send_to_mail)  {
            
        $CI->email->initialize($this->config); 
        $CI->email->set_newline("\r\n");
            
         
         

         //Load email library 
         $CI->load->library('email', $this->config); 
         $CI->email->from($this->from_email, $this->name_email);  
         $CI->email->to($this->to_email);
         $CI->email->subject($this->subject); 
         $CI->email->attach($file);
       
         $body = 'This mail was sent by Alphox_bkp for Codeigniter' ;
         $CI->email->message($body); 
         
          $CI->email->send();
   
                                        }
        // end send email

        // delete file and row of backup table
       
          $n_row = $CI->db->count_all('backup');

        If ($n_row>$this->n_db) {
        $CI->db->limit($n_row-$this->n_db);
        $CI->db->order_by('created_date', 'ASC');
        $todelete = $CI->db->get('backup')->result();

        foreach ($todelete as $to_delete) {

            //delete row from db table
            $CI->db->where('backup_id', $to_delete->backup_id);
        	$CI->db->delete('backup');

        	// delete file from backup directory
            $file_del = $this->db_backup_path . $to_delete->backup_name;
            if (file_exists($file_del)) { unlink($file_del); }
            
                    }// end foreach delete
                }// end if
                }// end if write
            }
                    
        



    }



}	